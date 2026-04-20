<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use App\Models\Patient;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ─── Dashboard ───────────────────────────────────────────
    public function dashboard()
    {
        $stats = [
            'total_patients'      => Patient::count(),
            'total_doctors'       => Doctor::count(),
            'total_appointments'  => Appointment::count(),
            'today_appointments'  => Appointment::today()->count(),
            'monthly_revenue'     => Consultation::whereMonth('created_at', now()->month)->sum('price'),
            'pending_appointments'=> Appointment::pending()->count(),
        ];

        // Appointments per month (last 6 months) for chart
        $appointmentsChart = Appointment::select(
            DB::raw('MONTH(scheduled_at) as month'),
            DB::raw('YEAR(scheduled_at) as year'),
            DB::raw('COUNT(*) as total')
        )
        ->where('scheduled_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year')->orderBy('month')
        ->get();

        // Revenue per month
        $revenueChart = Consultation::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('YEAR(created_at) as year'),
            DB::raw('SUM(price) as total')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year')->orderBy('month')
        ->get();

        // Appointments by specialty
        $specialtyChart = Appointment::join('doctors', 'appointments.doctor_id', '=', 'doctors.id')
            ->select('doctors.specialty', DB::raw('COUNT(*) as total'))
            ->groupBy('doctors.specialty')
            ->get();

        $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])
            ->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'stats', 'appointmentsChart', 'revenueChart', 'specialtyChart', 'recentAppointments'
        ));
    }

    // ─── USER MANAGEMENT ─────────────────────────────────────

    public function users(Request $request)
    {
        $query = User::query();
        if ($request->role) $query->where('role', $request->role);
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        $users = $query->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:20',
            'role'     => 'required|in:admin,doctor,secretary,patient',
            'password' => 'required|min:8|confirmed',
            // Doctor fields
            'specialty'           => 'required_if:role,doctor',
            'license_number'      => 'required_if:role,doctor|unique:doctors',
            'consultation_price'  => 'nullable|numeric|min:0',
            'consultation_duration'=> 'nullable|integer|min:10',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        match ($request->role) {
            'doctor' => Doctor::create([
                'user_id'               => $user->id,
                'specialty'             => $request->specialty,
                'license_number'        => $request->license_number,
                'description'           => $request->description,
                'consultation_price'    => $request->consultation_price ?? 200,
                'consultation_duration' => $request->consultation_duration ?? 30,
            ]),
            'patient'   => Patient::create(['user_id' => $user->id]),
            'secretary' => Secretary::create(['user_id' => $user->id, 'department' => $request->department]),
            default     => null,
        };

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => "required|email|unique:users,email,{$user->id}",
            'phone' => 'nullable|string|max:20',
            'role'  => 'required|in:admin,doctor,secretary,patient',
        ]);

        $user->update($request->only('name', 'email', 'phone', 'role', 'is_active'));

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update doctor info if applicable
        if ($user->isDoctor() && $user->doctor) {
            $user->doctor->update($request->only('specialty', 'license_number', 'description', 'consultation_price', 'consultation_duration'));
        }

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour.');
    }

    public function toggleUser(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activé' : 'désactivé';
        return back()->with('success', "Compte {$status} avec succès.");
    }

    public function destroyUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé.');
    }

    // ─── DOCTORS AVAILABILITY ────────────────────────────────

    public function doctorAvailability(Doctor $doctor)
    {
        $availabilities = $doctor->availabilities()->orderByRaw("FIELD(day_of_week, 'monday','tuesday','wednesday','thursday','friday','saturday','sunday')")->get();
        return view('admin.doctors.availability', compact('doctor', 'availabilities'));
    }

    public function updateAvailability(Request $request, Doctor $doctor)
    {
        $request->validate([
            'availabilities'                => 'array',
            'availabilities.*.day_of_week'  => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'availabilities.*.start_time'   => 'required|date_format:H:i',
            'availabilities.*.end_time'     => 'required|date_format:H:i|after:availabilities.*.start_time',
        ]);

        $doctor->availabilities()->delete();

        if ($request->availabilities) {
            foreach ($request->availabilities as $av) {
                DoctorAvailability::create([
                    'doctor_id'    => $doctor->id,
                    'day_of_week'  => $av['day_of_week'],
                    'start_time'   => $av['start_time'],
                    'end_time'     => $av['end_time'],
                    'is_available' => true,
                ]);
            }
        }

        return back()->with('success', 'Disponibilités mises à jour.');
    }
}

<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Secretary;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Admin ───────────────────────────────────────────────
        User::create([
            'name'     => 'Administrateur',
            'email'    => 'admin@cabinet.ma',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'phone'    => '0600000001',
            'is_active'=> true,
        ]);

        // ── Doctors ─────────────────────────────────────────────
        $doctorsData = [
            ['name'=>'Dr. Fatima Zahra Alami',  'email'=>'dr.alami@cabinet.ma',   'specialty'=>'Médecine Générale',     'license'=>'MG-001', 'price'=>200],
            ['name'=>'Dr. Youssef Bennani',      'email'=>'dr.bennani@cabinet.ma', 'specialty'=>'Cardiologie',           'license'=>'CA-002', 'price'=>400],
            ['name'=>'Dr. Sara El Mansouri',     'email'=>'dr.elmansouri@cabinet.ma','specialty'=>'Pédiatrie',           'license'=>'PE-003', 'price'=>300],
            ['name'=>'Dr. Karim Tazi',           'email'=>'dr.tazi@cabinet.ma',    'specialty'=>'Dermatologie',          'license'=>'DE-004', 'price'=>350],
            ['name'=>'Dr. Nadia Chraibi',        'email'=>'dr.chraibi@cabinet.ma', 'specialty'=>'Gynécologie-Obstétrique','license'=>'GO-005', 'price'=>380],
        ];

        $doctors = [];
        foreach ($doctorsData as $d) {
            $user = User::create([
                'name'=>$d['name'], 'email'=>$d['email'],
                'password'=>Hash::make('password'), 'role'=>'doctor',
                'phone'=>'06' . rand(10000000, 99999999), 'is_active'=>true,
            ]);
            $doctor = Doctor::create([
                'user_id'=>$user->id, 'specialty'=>$d['specialty'],
                'license_number'=>$d['license'], 'consultation_price'=>$d['price'],
                'consultation_duration'=>30, 'description'=>"Spécialiste en {$d['specialty']}",
            ]);

            // Set availabilities (Mon-Fri + Sat morning)
            $days = ['monday','tuesday','wednesday','thursday','friday'];
            foreach ($days as $day) {
                DoctorAvailability::create([
                    'doctor_id'=>$doctor->id, 'day_of_week'=>$day,
                    'start_time'=>'09:00', 'end_time'=>'17:00', 'is_available'=>true,
                ]);
            }
            DoctorAvailability::create([
                'doctor_id'=>$doctor->id, 'day_of_week'=>'saturday',
                'start_time'=>'09:00', 'end_time'=>'13:00', 'is_available'=>true,
            ]);

            $doctors[] = $doctor;
        }

        // ── Secretary ────────────────────────────────────────────
        $secUser = User::create([
            'name'=>'Samira Idrissi', 'email'=>'secretaire@cabinet.ma',
            'password'=>Hash::make('password'), 'role'=>'secretary',
            'phone'=>'0600000010', 'is_active'=>true,
        ]);
        Secretary::create(['user_id'=>$secUser->id, 'department'=>'Accueil']);

        // ── Patients ─────────────────────────────────────────────
        $patientsData = [
            ['name'=>'Mohammed Berrada',   'email'=>'m.berrada@gmail.com',   'gender'=>'male',   'blood'=>'A+',  'dob'=>'1985-03-15', 'cin'=>'BE123456'],
            ['name'=>'Aïcha Lahlou',       'email'=>'a.lahlou@gmail.com',    'gender'=>'female', 'blood'=>'O+',  'dob'=>'1992-07-22', 'cin'=>'LA234567'],
            ['name'=>'Hassan Ouazzani',    'email'=>'h.ouazzani@gmail.com',  'gender'=>'male',   'blood'=>'B+',  'dob'=>'1978-11-05', 'cin'=>'OU345678'],
            ['name'=>'Fatima Bensalem',    'email'=>'f.bensalem@gmail.com',  'gender'=>'female', 'blood'=>'AB+', 'dob'=>'2001-02-28', 'cin'=>'BE456789'],
            ['name'=>'Yassine El Fassi',   'email'=>'y.elfassi@gmail.com',   'gender'=>'male',   'blood'=>'O-',  'dob'=>'1969-09-14', 'cin'=>'EL567890'],
            ['name'=>'Zineb Cherkaoui',    'email'=>'z.cherkaoui@gmail.com', 'gender'=>'female', 'blood'=>'A-',  'dob'=>'1995-04-30', 'cin'=>'CH678901'],
            ['name'=>'Khalid Amrani',      'email'=>'k.amrani@gmail.com',    'gender'=>'male',   'blood'=>'B-',  'dob'=>'1988-12-10', 'cin'=>'AM789012'],
            ['name'=>'Nadia Kabbaj',       'email'=>'n.kabbaj@gmail.com',    'gender'=>'female', 'blood'=>'O+',  'dob'=>'1975-06-18', 'cin'=>'KA890123'],
            ['name'=>'Rachid Moussaoui',   'email'=>'r.moussaoui@gmail.com', 'gender'=>'male',   'blood'=>'A+',  'dob'=>'2005-08-25', 'cin'=>'MO901234'],
            ['name'=>'Leila Bensouda',     'email'=>'l.bensouda@gmail.com',  'gender'=>'female', 'blood'=>'AB-', 'dob'=>'1999-01-07', 'cin'=>'BS012345'],
        ];

        $patients = [];
        foreach ($patientsData as $p) {
            $user = User::create([
                'name'=>$p['name'], 'email'=>$p['email'],
                'password'=>Hash::make('password'), 'role'=>'patient',
                'phone'=>'06'.rand(10000000,99999999), 'is_active'=>true,
            ]);
            $patient = Patient::create([
                'user_id'=>$user->id, 'birth_date'=>$p['dob'], 'gender'=>$p['gender'],
                'blood_type'=>$p['blood'], 'cin'=>$p['cin'],
                'address'=>rand(1,99).' Rue '.['Hassan II','Mohammed V','Anfa','Zerktouni'][rand(0,3)].', Casablanca',
                'allergies'=> rand(0,1) ? 'Pénicilline' : null,
            ]);
            $patients[] = $patient;
        }

        // ── Appointments + Consultations ─────────────────────────
        $adminUser = User::where('role','admin')->first();
        $reasons = ['Douleur thoracique','Fièvre persistante','Consultation de routine','Maux de tête','Dermatologie','Suivi traitement','Bilan de santé','Grippe'];
        $diagnoses = ['Hypertension artérielle','Infection virale','Bonne santé','Migraine','Eczéma','Diabète type 2','Anémie ferriprive'];

        // Past appointments with consultations
        for ($i = 0; $i < 30; $i++) {
            $doctor  = $doctors[array_rand($doctors)];
            $patient = $patients[array_rand($patients)];
            $date    = now()->subDays(rand(1, 60))->setTime(rand(9,16), in_array(rand(0,1),['0']) ? 0 : 30);

            $appointment = Appointment::create([
                'patient_id'   => $patient->id,
                'doctor_id'    => $doctor->id,
                'created_by'   => $adminUser->id,
                'scheduled_at' => $date,
                'duration'     => 30,
                'status'       => 'completed',
                'reason'       => $reasons[array_rand($reasons)],
            ]);

            $consultation = Consultation::create([
                'appointment_id' => $appointment->id,
                'doctor_id'      => $doctor->id,
                'patient_id'     => $patient->id,
                'chief_complaint'=> $appointment->reason,
                'diagnosis'      => $diagnoses[array_rand($diagnoses)],
                'notes'          => 'Examen clinique normal. Patient en bon état général.',
                'weight'         => rand(50, 100),
                'height'         => rand(155, 190),
                'temperature'    => round(rand(365, 379) / 10, 1),
                'heart_rate'     => rand(60, 100),
                'blood_pressure_systolic'  => rand(100, 140),
                'blood_pressure_diastolic' => rand(60, 90),
                'price'          => $doctor->consultation_price,
                'payment_status' => 'paid',
            ]);

            $prescription = Prescription::create([
                'consultation_id' => $consultation->id,
                'doctor_id'       => $doctor->id,
                'patient_id'      => $patient->id,
            ]);

            PrescriptionItem::create([
                'prescription_id' => $prescription->id,
                'medication'      => ['Paracétamol 500mg','Amoxicilline 500mg','Ibuprofène 400mg','Oméprazole 20mg'][rand(0,3)],
                'dosage'          => ['500mg','250mg','400mg','20mg'][rand(0,3)],
                'frequency'       => ['3 fois/jour','2 fois/jour','1 fois/jour','Matin et soir'][rand(0,3)],
                'duration'        => ['5 jours','7 jours','10 jours','1 mois'][rand(0,3)],
                'instructions'    => 'À prendre pendant les repas',
            ]);
        }

        // Future appointments
        for ($i = 0; $i < 20; $i++) {
            $doctor  = $doctors[array_rand($doctors)];
            $patient = $patients[array_rand($patients)];
            $date    = now()->addDays(rand(1, 30))->setTime(rand(9,16), 0);

            Appointment::create([
                'patient_id'   => $patient->id,
                'doctor_id'    => $doctor->id,
                'created_by'   => $adminUser->id,
                'scheduled_at' => $date,
                'duration'     => 30,
                'status'       => ['confirmed','pending'][rand(0,1)],
                'reason'       => $reasons[array_rand($reasons)],
            ]);
        }

        $this->command->info('✅ Base de données peuplée avec succès!');
        $this->command->table(
            ['Rôle', 'Email', 'Mot de passe'],
            [
                ['Admin',      'admin@cabinet.ma',         'password'],
                ['Médecin',    'dr.alami@cabinet.ma',      'password'],
                ['Secrétaire', 'secretaire@cabinet.ma',    'password'],
                ['Patient',    'm.berrada@gmail.com',      'password'],
            ]
        );
    }
}

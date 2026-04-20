<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorAvailability;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_is_accessible()
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('Connexion');
    }

    /** @test */
    public function register_page_is_accessible()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertSee('Inscription');
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'password'  => bcrypt('password'),
            'role'      => 'patient',
            'is_active' => true,
        ]);
        Patient::create(['user_id' => $user->id]);

        $response = $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('patient.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create(['password' => bcrypt('correct-password')]);

        $response = $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function inactive_user_cannot_login()
    {
        $user = User::factory()->create([
            'password'  => bcrypt('password'),
            'is_active' => false,
        ]);

        $response = $this->post(route('login'), [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function patient_can_register()
    {
        $response = $this->post(route('register'), [
            'name'                  => 'Test Patient',
            'email'                 => 'test@example.com',
            'phone'                 => '0612345678',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('patient.dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'test@example.com', 'role' => 'patient']);
        $this->assertDatabaseHas('patients', []);
    }

    /** @test */
    public function authenticated_user_can_logout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('logout'));
        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }

    /** @test */
    public function guest_is_redirected_to_login_on_protected_routes()
    {
        $response = $this->get(route('appointments.index'));
        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function patient_cannot_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'patient']);
        Patient::create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_admin_dashboard()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(200);
    }
}


class AppointmentFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $adminUser;
    private User $doctorUser;
    private User $patientUser;
    private Doctor $doctor;
    private Patient $patient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser  = User::factory()->create(['role' => 'admin']);
        $this->doctorUser = User::factory()->create(['role' => 'doctor']);
        $this->patientUser = User::factory()->create(['role' => 'patient', 'is_active' => true]);

        $this->doctor = Doctor::create([
            'user_id'               => $this->doctorUser->id,
            'specialty'             => 'Médecine Générale',
            'license_number'        => 'MG-TEST-001',
            'consultation_price'    => 200,
            'consultation_duration' => 30,
        ]);

        $this->patient = Patient::create(['user_id' => $this->patientUser->id]);

        // Set availability
        DoctorAvailability::create([
            'doctor_id'    => $this->doctor->id,
            'day_of_week'  => 'monday',
            'start_time'   => '09:00',
            'end_time'     => '17:00',
            'is_available' => true,
        ]);
    }

    /** @test */
    public function authenticated_user_can_view_appointments_list()
    {
        $response = $this->actingAs($this->adminUser)->get(route('appointments.index'));
        $response->assertStatus(200);
        $response->assertSee('Rendez-vous');
    }

    /** @test */
    public function admin_can_create_appointment()
    {
        $scheduledAt = now()->next('Monday')->setTime(10, 0)->format('Y-m-d H:i:s');

        $response = $this->actingAs($this->adminUser)->post(route('appointments.store'), [
            'patient_id'   => $this->patient->id,
            'doctor_id'    => $this->doctor->id,
            'scheduled_at' => $scheduledAt,
            'reason'       => 'Consultation de routine',
            'notes'        => 'Test notes',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertDatabaseHas('appointments', [
            'patient_id' => $this->patient->id,
            'doctor_id'  => $this->doctor->id,
            'reason'     => 'Consultation de routine',
        ]);
    }

    /** @test */
    public function appointment_requires_future_date()
    {
        $response = $this->actingAs($this->adminUser)->post(route('appointments.store'), [
            'patient_id'   => $this->patient->id,
            'doctor_id'    => $this->doctor->id,
            'scheduled_at' => now()->subDays(1)->format('Y-m-d H:i:s'),
            'reason'       => 'Test',
        ]);

        $response->assertSessionHasErrors('scheduled_at');
    }

    /** @test */
    public function secretary_can_confirm_appointment()
    {
        $secretary = User::factory()->create(['role' => 'secretary']);

        $appointment = Appointment::create([
            'patient_id'   => $this->patient->id,
            'doctor_id'    => $this->doctor->id,
            'created_by'   => $this->adminUser->id,
            'scheduled_at' => now()->addDays(3),
            'duration'     => 30,
            'status'       => 'pending',
            'reason'       => 'Test',
        ]);

        $response = $this->actingAs($secretary)->patch(route('appointments.confirm', $appointment));

        $appointment->refresh();
        $this->assertEquals('confirmed', $appointment->status);
    }

    /** @test */
    public function available_slots_endpoint_returns_json()
    {
        $monday = now()->next('Monday')->format('Y-m-d');

        $response = $this->actingAs($this->adminUser)->getJson(
            route('appointments.slots', ['doctor_id' => $this->doctor->id, 'date' => $monday])
        );

        $response->assertStatus(200);
        $response->assertJsonStructure(['slots']);
    }

    /** @test */
    public function patient_can_only_see_own_appointments()
    {
        $otherPatientUser = User::factory()->create(['role' => 'patient']);
        $otherPatient     = Patient::create(['user_id' => $otherPatientUser->id]);

        // Create appointment for current patient
        $myAppointment = Appointment::create([
            'patient_id'   => $this->patient->id,
            'doctor_id'    => $this->doctor->id,
            'created_by'   => $this->patientUser->id,
            'scheduled_at' => now()->addDays(2),
            'duration'     => 30,
            'status'       => 'confirmed',
            'reason'       => 'My appointment',
        ]);

        // Create appointment for other patient
        $otherAppointment = Appointment::create([
            'patient_id'   => $otherPatient->id,
            'doctor_id'    => $this->doctor->id,
            'created_by'   => $otherPatientUser->id,
            'scheduled_at' => now()->addDays(3),
            'duration'     => 30,
            'status'       => 'confirmed',
            'reason'       => 'Other appointment',
        ]);

        $response = $this->actingAs($this->patientUser)->get(route('appointments.index'));

        $response->assertSee('My appointment');
        $response->assertDontSee('Other appointment');
    }
}

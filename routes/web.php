<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NurseDashboardController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\MedicineRequestController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\PatientVerificationController;
use App\Http\Controllers\HomeServiceController;
use App\Http\Controllers\SchedulingController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\GlobalSearchController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [AuthController::class, 'register']);

// Pending approval page — accessible while logged in but not yet approved
Route::get('/pending-approval', function () {
    if (!auth()->check()) return redirect()->route('login');
    if (auth()->user()->status !== 'pending') return redirect()->route('dashboard');
    return view('auth.pending');
})->name('pending.approval')->middleware('auth');

Route::middleware(['auth'])->group(function () {
    
    // Dashboard route checks the user's role and serves the correct view
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        
        if ($role === 'nurse') {
            return app(NurseDashboardController::class)->index();
        } elseif ($role === 'resident') {
            return app(\App\Http\Controllers\ResidentDashboardController::class)->index();
        } elseif ($role === 'bhw') {
            return app(\App\Http\Controllers\BhwDashboardController::class)->index();
        }
        
        // Fallback (shouldn't happen with valid roles, but good for safety)
        return view('dashboard');
    })->name('dashboard');

    // Global Search API (Nurse, BHW, Resident)
    Route::get('/api/search', [GlobalSearchController::class, 'search'])->name('global.search');

    // Nurse routes
    Route::get('/nurse/medicine-requests', [MedicineRequestController::class, 'index'])->name('nurse.medicine-requests');
    Route::get('/nurse/inventory', [NurseDashboardController::class, 'inventory'])->name('nurse.inventory');
    Route::get('/nurse/returned-medicines', [NurseDashboardController::class, 'returnedMedicines'])->name('nurse.returns');
    Route::get('/nurse/reports', [ReportsController::class, 'index'])->name('nurse.reports');
    Route::get('/nurse/patient-verification', [PatientVerificationController::class, 'index'])->name('nurse.patient-verification');
    Route::patch('/nurse/patient-verification/{user}/verify', [PatientVerificationController::class, 'verify'])->name('patient.verify');
    Route::patch('/nurse/patient-verification/{user}/reject', [PatientVerificationController::class, 'reject'])->name('patient.reject');
    Route::delete('/nurse/patient-verification/{user}', [PatientVerificationController::class, 'destroy'])->name('patient.destroy');

    // Medicine CRUD
    Route::post('/nurse/medicines', [MedicineController::class, 'store'])->name('medicines.store');
    Route::put('/nurse/medicines/{medicine}', [MedicineController::class, 'update'])->name('medicines.update');
    Route::delete('/nurse/medicines/{medicine}', [MedicineController::class, 'destroy'])->name('medicines.destroy');

    // Request actions (nurse/BHW accept/approve/reject/update-quantity/complete-claim)
    Route::patch('/nurse/requests/{medicineRequest}/accept', [MedicineRequestController::class, 'accept'])->name('requests.accept');
    Route::patch('/nurse/requests/{medicineRequest}/approve', [MedicineRequestController::class, 'approve'])->name('requests.approve');
    Route::patch('/nurse/requests/{medicineRequest}/reject', [MedicineRequestController::class, 'reject'])->name('requests.reject');
    Route::patch('/nurse/requests/{medicineRequest}/quantity', [MedicineRequestController::class, 'updateQuantity'])->name('requests.update-quantity');
    Route::post('/nurse/requests/{medicineRequest}/complete', [MedicineRequestController::class, 'complete'])->name('requests.complete');

    // Resident — submit a medicine request
    Route::post('/resident/requests/medicine', [MedicineRequestController::class, 'store'])->name('resident.requests.medicine.store');
    
    // Resident — submit a home service request
    Route::post('/resident/requests/home-service', [HomeServiceController::class, 'storeResident'])->name('resident.requests.home-service.store');

    // Resident — submit a schedule request
    Route::post('/resident/requests/schedule', [SchedulingController::class, 'storeResident'])->name('resident.requests.schedule.store');

    // Resident — mark notifications as read
    Route::post('/resident/notifications/read', [\App\Http\Controllers\ResidentDashboardController::class, 'markNotificationsRead'])->name('resident.notifications.read');

    // Resident — update profile
    Route::put('/resident/profile', [\App\Http\Controllers\ResidentDashboardController::class, 'updateProfile'])->name('resident.profile.update');

    // User Management
    Route::get('/nurse/users', [UserManagementController::class, 'index'])->name('nurse.users');
    Route::post('/nurse/users', [UserManagementController::class, 'store'])->name('users.store');
    Route::put('/nurse/users/{user}', [UserManagementController::class, 'update'])->name('users.update');
    Route::delete('/nurse/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy');

    // Home Services
    Route::get('/nurse/home-services', [HomeServiceController::class, 'index'])->name('nurse.home-services');
    Route::post('/nurse/home-services', [HomeServiceController::class, 'store'])->name('home-services.store');
    Route::patch('/nurse/home-services/{homeServiceRequest}/accept', [HomeServiceController::class, 'accept'])->name('home-services.accept');
    Route::patch('/nurse/home-services/{homeServiceRequest}/approve', [HomeServiceController::class, 'approve'])->name('home-services.approve');
    Route::patch('/nurse/home-services/{homeServiceRequest}/reject', [HomeServiceController::class, 'reject'])->name('home-services.reject');
    Route::patch('/nurse/home-services/{homeServiceRequest}/complete', [HomeServiceController::class, 'complete'])->name('home-services.complete');
    Route::patch('/nurse/home-services/{homeServiceRequest}/assign', [HomeServiceController::class, 'assign'])->name('home-services.assign');
    Route::delete('/nurse/home-services/{homeServiceRequest}', [HomeServiceController::class, 'destroy'])->name('home-services.destroy');
    Route::post('/nurse/home-services/clear', [HomeServiceController::class, 'clear'])->name('home-services.clear');

    Route::get('/nurse/scheduling', [SchedulingController::class, 'index'])->name('nurse.scheduling');
    Route::post('/nurse/scheduling', [SchedulingController::class, 'store'])->name('scheduling.store');
    Route::put('/nurse/scheduling/{schedule}', [SchedulingController::class, 'update'])->name('scheduling.update');
    Route::patch('/nurse/scheduling/{schedule}/confirm', [SchedulingController::class, 'confirm'])->name('scheduling.confirm');
    Route::patch('/nurse/scheduling/{schedule}/assign-bhw', [SchedulingController::class, 'assignToBhw'])->name('scheduling.assign-bhw');
    Route::delete('/nurse/scheduling/{schedule}', [SchedulingController::class, 'destroy'])->name('scheduling.destroy');

    // BHW — confirm assigned schedule
    Route::patch('/bhw/scheduling/{schedule}/confirm', [SchedulingController::class, 'confirmByBhw'])->name('bhw.scheduling.confirm');

    // BHW Walk-in Registration
    Route::post('/bhw/walkin', [\App\Http\Controllers\WalkinController::class, 'store'])->name('bhw.walkin.store');
    Route::get('/bhw/walkin/recent', [\App\Http\Controllers\WalkinController::class, 'recent'])->name('bhw.walkin.recent');

    // BHW Home Service 
    Route::patch('/bhw/home-services/{homeService}/accept', [\App\Http\Controllers\BhwDashboardController::class, 'acceptHomeService'])->name('bhw.home-services.accept');
    Route::patch('/bhw/home-services/{homeService}/approve', [\App\Http\Controllers\BhwDashboardController::class, 'approveHomeService'])->name('bhw.home-services.approve');
    Route::patch('/bhw/home-services/{homeService}/confirm', [\App\Http\Controllers\BhwDashboardController::class, 'confirmHomeService'])->name('bhw.home-services.confirm');
    Route::patch('/bhw/home-services/{homeService}/complete', [\App\Http\Controllers\BhwDashboardController::class, 'completeHomeService'])->name('bhw.home-services.complete');

    // Announcements Management (Nurse & BHW)
    Route::get('/nurse/announcements', [AnnouncementController::class, 'nurseIndex'])->name('nurse.announcements');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('announcements.destroy');

    // Consultations & Communicable Disease Cases (Dengue, HIV, TB, etc.)
    Route::get('/nurse/consultations', [\App\Http\Controllers\ConsultationController::class, 'index'])->name('nurse.consultations');
    Route::post('/nurse/consultations', [\App\Http\Controllers\ConsultationController::class, 'store'])->name('nurse.consultations.store');
    Route::delete('/nurse/consultations/{consultation}', [\App\Http\Controllers\ConsultationController::class, 'destroy'])->name('nurse.consultations.destroy');

    // Children & Infant Vaccinations (1st, 2nd, succeeding doses)
    Route::get('/nurse/vaccinations', [\App\Http\Controllers\ChildVaccinationController::class, 'index'])->name('nurse.vaccinations');
    Route::post('/nurse/vaccinations', [\App\Http\Controllers\ChildVaccinationController::class, 'store'])->name('nurse.vaccinations.store');
    Route::delete('/nurse/vaccinations/{childVaccination}', [\App\Http\Controllers\ChildVaccinationController::class, 'destroy'])->name('nurse.vaccinations.destroy');

    // BHW - mark notifications as read
    Route::post('/bhw/notifications/read', [\App\Http\Controllers\BhwDashboardController::class, 'markNotificationsRead'])->name('bhw.notifications.read');

    // Nurse - mark notifications as read
    Route::post('/nurse/notifications/read', [NurseDashboardController::class, 'markNotificationsRead'])->name('nurse.notifications.read');

});

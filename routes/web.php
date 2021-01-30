<?php

use App\Http\Controllers\Admin\AppointmentController;

use App\Http\Controllers\Site\FrontendController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\DoctorController;

// =====================================================================================================================
// Auth
// =====================================================================================================================
Auth::routes();

// =====================================================================================================================
// Site
// =====================================================================================================================
Route::get('/home', [FrontendController::class, 'index'])->name('home');
Route::redirect('/', 'home');

Route::get('/new-appointment/{doctorId}/{date}', [FrontendController::class, 'show_appointments_of_doctor_for_date'])->name('site.appointments.create');

Route::group(['middleware' => ['auth', 'patient']],function(){

    Route::post('/book/appointment',[FrontendController::class, 'bookAnAppointment'])->name('booking.appointment');

    Route::get('/my-booking','FrontendController@myBookings')->name('my.booking');

    Route::get('/user-profile','ProfileController@index');
    Route::post('/user-profile','ProfileController@store')->name('profile.store');
    Route::post('/profile-pic','ProfileController@profilePic')->name('profile.pic');
    Route::get('/my-prescription','FrontendController@myPrescription')->name('my.prescription');
});

// =====================================================================================================================
// Dashboard
// =====================================================================================================================
Route
//    ::name('dashboard.')
//    ->prefix('admin')
    ::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {
        Route::middleware(['doctor'], function () {
            // Appointments
            Route::resource('appointments', AppointmentController::class);
            Route::post('/appointments/check',[AppointmentController::class, 'check'])->name('appointments.check');
            Route::post('/appointments/update',[AppointmentController::class, 'updateTime'])->name('appointments.updateTime');
        });

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

        // Doctors
        Route::resource('doctors', DoctorController::class);

        // Departments
        Route::resource('departments',  DepartmentController::class);
});

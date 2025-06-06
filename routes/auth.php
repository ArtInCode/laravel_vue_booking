<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\RandomPostsController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\AppointmentItemController;

Route::middleware('guest')->group(function () {
    
    /*Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);*/
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware(['auth', 'verified', RoleMiddleware::class.':admin,editor'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');    

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');   
    
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard'); 

    Route::get('/dashboard/sliders/', [SliderController::class, 'index'])->name('slider.index'); 
    Route::get('/dashboard/sliders/create/', [SliderController::class, 'create'])->name('slider.create'); 
    Route::post('/dashboard/sliders/store/', [SliderController::class, 'store'])->name('slider.store');
    Route::post('/dashboard/sliders/update/{slider}', [SliderController::class, 'update'])->name('slider.update'); 
    Route::get('/dashboard/sliders/edit/{id}', [SliderController::class, 'edit'])->name('slider.edit'); 
    Route::post('/dashboard/sliders/delete/{id}', [SliderController::class, 'destroy'])->name('sliders.delete');

    Route::get('/dashboard/random-posts/', [RandomPostsController::class, 'index'])->name('random-post.index'); 
    Route::get('/dashboard/random-posts/create/', [RandomPostsController::class, 'create'])->name('random-post.create'); 
    Route::post('/dashboard/random-posts/store/', [RandomPostsController::class, 'store'])->name('random-post.store');
    Route::post('/dashboard/random-posts/update/{random_posts}', [RandomPostsController::class, 'update'])->name('random-post.update'); 
    Route::get('/dashboard/random-posts/edit/{id}', [RandomPostsController::class, 'edit'])->name('random-post.edit'); 
    Route::post('/dashboard/random-posts/delete/{id}', [RandomPostsController::class, 'destroy'])->name('random-posts.delete');

    Route::get('/dashboard/bookings/', [BookingController::class, 'index'])->name('bookings.index'); 
    Route::get('/dashboard/bookings/create/', [BookingController::class, 'create'])->name('bookings.create'); 
    Route::post('/dashboard/bookings/store/', [BookingController::class, 'store'])->name('bookings.store');
    Route::post('/dashboard/bookings/update/{id}', [BookingController::class, 'update'])->name('bookings.update'); 
    Route::get('/dashboard/bookings/edit/{id}', [BookingController::class, 'edit'])->name('bookings.edit'); 
    Route::post('/dashboard/bookings/delete/{id}', [BookingController::class, 'destroy'])->name('bookings.delete');

    Route::get('/dashboard/appointments/', [AppointmentController::class, 'index'])->name('appointments.index'); 
    Route::get('/dashboard/appointments/create/', [AppointmentController::class, 'create'])->name('appointments.create'); 
    Route::get('/dashboard/appointments/create-empty-appointment/', [AppointmentController::class, 'createEmpty'])->name('appointments.create.empty'); 
    Route::post('/dashboard/appointments/store/', [AppointmentController::class, 'storeAdminAppointment'])->name('appointments.store');
    Route::post('/dashboard/appointments/store-admin-app/', [AppointmentController::class, 'storeAdminAppointment'])->name('appointments.store.admin.app');
    Route::post('/dashboard/appointments/update/{bookings}', [AppointmentController::class, 'update'])->name('appointments.update'); 
    Route::get('/dashboard/appointments/edit/{id}', [AppointmentController::class, 'edit'])->name('appointments.edit'); 
    Route::post('/dashboard/appointments/delete/{id}', [AppointmentController::class, 'destroy'])->name('appointments.delete');
    Route::post('/dashboard/appointments/search-user/', [PagesController::class, 'searchUser'])->name('appointments.search.user');
    Route::post('/dashboard/appointments/get-booking-slots/', [SlotController::class, 'slostForBooking'])->name('appointments.booking.slots');
    
    Route::post('/dashboard/appointments-item/store/', [AppointmentItemController::class, 'store'])->name('appointments.items.store');
    Route::post('/dashboard/appointments-item/update/{id}', [AppointmentItemController::class, 'update'])->name('appointments.items.update');
    Route::post('/dashboard/appointments-item/delete/{id}', [AppointmentItemController::class, 'destroy'])->name('appointments.items.delete');


    Route::post('/dashboard/upload/', [MediaController::class, 'upload'])->name('media.apload');
    Route::get('/dashboard/media/list/', [MediaController::class, 'filesList'])->name('media.files');
    Route::post('/dashboard/media/delete-items/', [MediaController::class, 'deleteItems'])->name('media.deleteItems');
    

    Route::post('/dashboard/slots/store/', [SlotController::class, 'store'])->name('slot.store');
    Route::post('/dashboard/slots/update/{slot}', [SlotController::class, 'update'])->name('slot.update');
    Route::post('/dashboard/slots/delete/{id}', [SlotController::class, 'destroy'])->name('slot.delete');
    
});
Route::middleware(['auth', 'verified', RoleMiddleware::class.':admin'])->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);
});
Route::middleware(['auth', 'verified', RoleMiddleware::class.':admin,editor,member_1'])->group(function () {
    Route::get('/my-account/appointments/', [AppointmentController::class, 'myAppointments'])->name('appointments.myAppointments');
    Route::post('/my-account/appointments/cancel/{id}', [AppointmentController::class, 'myAppointmentCancel'])->name('appointments.myAppointment.cancel');  
}); 

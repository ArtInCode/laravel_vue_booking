<?php



use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\SlotController;
use App\Http\Controllers\AppointmentController;


Route::get('/', [PagesController::class, 'home'])->name('home');
Route::get('/bookings', [PagesController::class, 'bookings'])->name('bookings');
Route::get('/bookings/{slug}/{id}', [BookingController::class, 'bookings_item'])->name('booking.item');
Route::post('/slots/for-date', [SlotController::class, 'slostForDate'])->name('slostForDate');
Route::post('/bookings/create', [AppointmentController::class, 'saveUserAppointment'])->name('bookings.create.frontend');


require __DIR__.'/auth.php';

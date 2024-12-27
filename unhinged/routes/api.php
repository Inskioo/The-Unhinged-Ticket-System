<?php 

use App\Http\Controllers\TicketController;

Route::middleware('auth:sanctum')->group(function(){
    // all tickets in the system
    Route::get('/tickets', [TicketController::class, 'index']);

    // getting data on tickets by something
    Route::get('/tickets/assigned/{support_id?}', [TicketController::class, 'assigned']);
    Route::get('/tickets/resolved', [TicketController::class, 'resolved']);
    Route::get('/tickets/category/{type}', [TicketController::class, 'categorise']);
    Route::get('/tickets/priority/{level}', [TicketController::class, 'priority']);
    Route::get('/tickets/user/{user_id}', [TicketController::class, 'user']);

    // get data for a specific ticket
    Route::get('/tickets/{ticket}', [TicketController::class, 'display']);

    // Auth routes
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
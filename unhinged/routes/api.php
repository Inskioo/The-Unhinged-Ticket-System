<?php 

use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;

Route::middleware('check.admin.token', 'throttle:260,1')->group(function(){
    // all tickets in the system
    Route::get('/tickets', [TicketController::class, 'index']);

    // getting data on tickets by something
    Route::get('/tickets/assigned/{support_id?}', [TicketController::class, 'assigned']);
    Route::get('/tickets/resolved', [TicketController::class, 'resolved']);
    Route::get('/tickets/category/{type}', [TicketController::class, 'categorise']);
    Route::get('/tickets/priority/{level}', [TicketController::class, 'priority']);
    Route::get('/tickets/user/{user_id}', [TicketController::class, 'user']);

    // statsss <3
    Route::get('/tickets/stats/queue', [TicketController::class, 'queueStats']);
    Route::get('/tickets/stats/agents', [TicketController::class, 'agentStats']);
    Route::get('/tickets/user/{user}/counts', [TicketController::class, 'getUserTicketCounts']);

    // get data for a specific ticket
    Route::get('/tickets/{ticket}', [TicketController::class, 'display']);

    // user routes
    Route::get('/users/admin', [UserController::class, 'getAdminName']);
    Route::get('/users/support', [UserController::class, 'getSupportUserAll']);
    Route::get('/users/support/{id}', [UserController::class, 'getSupportUserById']);

    // action routes
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assignTicket']);
    Route::post('/tickets/{ticket}/resolve', [TicketController::class, 'resolveTicket']);
    Route::post('/tickets/{ticket}/type', [TicketController::class, 'setType']);
});
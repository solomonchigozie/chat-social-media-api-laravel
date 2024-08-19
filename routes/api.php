<?php

use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatController;
use App\Http\Controllers\API\Connections;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware("auth:api")->prefix('v1')->group(function() {
    Route::get("logout", [AuthController::class, 'logout']);
    Route::get('account/profile', [AccountController::class, 'profile']);

    //people
    Route::get('people/all', [Connections::class, 'viewallpeople']);
    Route::get('people/single/{id}', [Connections::class, 'viewsingleprofile']);

    //chats and messaging 
    Route::post('messages/start', [ChatController::class, 'startchat']);
    Route::get('messages/conversation/{receiver_id}', [ChatController::class, 'getsingleconversation']);
    Route::get('/messages/conversations', [ChatController::class, 'getallconversations']);


});
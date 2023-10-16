<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum'])->group( function () {
    Route::get('/logout', [UserController::class, 'logout']);

    Route::middleware(['editor-access'])->group( function () {
        Route::post('/notes', [NoteController::class, 'store']);
        Route::get('/notes', [NoteController::class, 'index']);
    });

    Route::middleware(['admin-access'])->group( function () {
        //Get semua notes yang ada
        Route::get('/admin/notes', [AdminController::class, 'index']);
        //Akses ke note dengan id
        Route::get('/admin/notes/{id}', [AdminController::class, 'show']);
        Route::patch('/admin/notes/{id}', [AdminController::class, 'update']);
        Route::delete('/admin/notes/{id}', [AdminController::class, 'destroy']);   
        
        Route::get('/admin/users', [AdminController::class, 'allUser']);
        Route::get('/admin/users/{id}', [AdminController::class, 'showUser']);
        Route::patch('/admin/users/{id}', [AdminController::class, 'updateUser']);
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
    });

    
    Route::middleware(['note-owner'])->group( function () {
        Route::get('/notes/{id}', [NoteController::class, 'show']);
        //Disini pake patch biar lebih enak, soalnya api doang
        Route::patch('/notes/{id}', [NoteController::class, 'update']);
        Route::delete('/notes/{id}', [NoteController::class, 'destroy']);    
    });
});

//Register
Route::post('/register', [UserController::class, 'register']);

//Login
Route::post('/login', [UserController::class, 'login']);
<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Public Routes
Route::post("register", [UserController::class, "signup"]);
Route::post("login", [UserController::class, "signin"]);

//Private Routes
Route::middleware('auth:sanctum')->group(function(){
    Route::prefix("user")->group(function(){
        Route::get("/", [UserController::class, "user"]);  
        Route::post("/logout", [UserController::class, "logout"]);  
        Route::put("/update", [UserController::class, "update"]);  
        Route::delete("/destroy", [UserController::class, "destroy"]);  
    });
    Route::get("tasks", [TaskController::class, "index"]);
    Route::prefix("task")->group(function(){
        Route::get("/{id}", [TaskController::class, "show"]);
        Route::post("/store", [TaskController::class, "store"]);
        Route::put("/{id}", [TaskController::class, "update"]);
        Route::delete("/{id}", [TaskController::class, "destroy"]);
    });
});

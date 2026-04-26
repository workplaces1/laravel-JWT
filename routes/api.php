<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::post("/user/register", [ApiController::class, "userRegister"]);
Route::post("/user/login", [ApiController::class, "userLogin"]);

Route::middleware('auth:api')->group(function () {
    Route::get("/user/profile", [ApiController::class, "profile"]);
});

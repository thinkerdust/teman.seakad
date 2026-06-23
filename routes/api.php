<?php

use App\Http\Controllers\Api\InvitationApiController;
use Illuminate\Support\Facades\Route;

Route::get('/invitation/{slug}', [InvitationApiController::class, 'show']);
Route::get('/music/recommendation/{theme}', [InvitationApiController::class, 'recommend']);

<?php

use App\Http\Controllers\Api\InvitationApiController;
use Illuminate\Support\Facades\Route;

Route::get('/invitation/{slug}', [InvitationApiController::class, 'show']);

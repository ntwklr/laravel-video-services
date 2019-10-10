<?php

use Illuminate\Support\Facades\Route;
use Ntwklr\VideoServices\Http\Controllers\ThumbController;

Route::get('thumb/{service}/{id}', ThumbController::class)->name('thumb');

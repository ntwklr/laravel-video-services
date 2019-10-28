<?php

use Illuminate\Support\Facades\Route;
use Ntwklr\VideoServices\Http\Controllers\Thumbnail;

Route::get('thumb/{service}/{id}/{file}', Thumbnail::class)->name('thumbnail');

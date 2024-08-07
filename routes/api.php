<?php

use App\Http\Controllers\Api\Blog\PostsController;
use App\Http\Controllers\Api\Website\ComplaintsController;
use App\Http\Controllers\Api\Website\ContactController;
use App\Http\Controllers\Api\Website\LuxeSearchController;
use App\Http\Controllers\Api\Website\PartnersController;
use App\Http\Controllers\Api\Website\PhotoGalleryController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('website')->as('website.')->group(function () {
    Route::prefix('photo-gallery')->as('photo-gallery.')->group(function () {
        Route::get('{galleryId}', [PhotoGalleryController::class, 'view'])->name('view');
    });

    Route::prefix('contact')->as('contact.')->group(function () {
        Route::post('', [ContactController::class, 'store'])->name('store');
        Route::post('complaints', [ComplaintsController::class, 'complaints'])->name('complaints');
    });
    Route::get('search-links', [LuxeSearchController::class, 'index'])->name('index');

    Route::get('partners-slider', [PartnersController::class, 'partnersSlider'])->name('partners-slider');
});

// middleware(['blog.protection'])->
Route::prefix('blog')->as('blog.')->group(function () {
    Route::prefix('posts')->as('posts.')->group(function () {
        Route::get('', [PostsController::class, 'index'])->name('index');
        Route::get('{slug}', [PostsController::class, 'getPostPerSlug'])->name('get-post-per-slug');

        Route::prefix('comments')->as('comments.')->group(function () {
            Route::get('{slug}', [PostsController::class, 'comments'])->name('comments');
            Route::post('{slug}', [PostsController::class, 'createComment'])->name('createComment');
        });
    });
});

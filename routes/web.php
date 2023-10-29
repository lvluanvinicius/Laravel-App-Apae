<?php

use App\Http\Controllers\Admin\TransparencyController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PartnersController;
use App\Http\Controllers\Admin\PhotoGalleryController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Web\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginDo'])->name('login-do');


// Website
Route::group([], function () {
    Route::get('', [HomeController::class, 'index'])->name('home');
});


// Web Cliente.
Route::prefix('meu-espaco')->as('client.')->middleware('auth:client')->group(function () {
    Route::get('', function () {
        return 'bem vindo cliente';
    })->name('index');
});


// Web Admin.
Route::prefix('admin')->as('admin.')->middleware('auth:web')->group(function () {
    Route::get('', [DashboardController::class, 'index'])->name('index');
    Route::prefix('users')->resource('users', UsersController::class);

    // Gerenciamento de rotas para portal da transparencia.
    Route::prefix('transparency')->as('transparency.')->group(function () {
        // GET
        Route::get('', [TransparencyController::class, 'index'])->name('index');
        Route::get('/create-folder-session/{folderYearId}', [TransparencyController::class, 'createFolderSession'])->name('create-folder-session');
        Route::get('/create-file-session/{folderSession}', [TransparencyController::class, 'createFileSession'])->name('create-file-session');
        Route::get('/create-file-session/file/{folderSession}', [TransparencyController::class, 'getFilesSession'])->name('get-file-session');

        // POST 
        Route::post('/create-folder-year', [TransparencyController::class, 'createFolderYear'])->name('create-folder-year');
        Route::post('/create-folder-session/{folderYearId}', [TransparencyController::class, 'createFolderSessionStore'])->name('create-folder-session-store');
        Route::post('/create-file-session/file/{folderSession}', [TransparencyController::class, 'createFileSessionStore'])->name('create-file-session-store');

        // DELETE
        Route::delete('/destroy-folder-year/{folderYearId}', [TransparencyController::class, 'destroyFolderYear'])->name('destroy-folder-year');
        Route::delete('/destroy-folder-session/{folderSession}', [TransparencyController::class, 'destroySessionFolder'])->name('destroy-folder-session');
        Route::delete('/destroy-file-session/{fileId}', [TransparencyController::class, 'destroyFilesSession'])->name('destroy-file-session');
    });

    // Gerenciamento de Galeria de Fotos.
    Route::prefix('photos-gallery')->as('photos-gallery.')->group(function () {
        // GET
        Route::get('', [PhotoGalleryController::class, 'index'])->name('index');
        Route::get('create-album', [PhotoGalleryController::class, 'createAlbum'])->name('create-album');
        Route::get('view-gallery/{galleryId}', [PhotoGalleryController::class, 'viewGallery'])->name('view-gallery');
        Route::get('add-image/{galleryId}', [PhotoGalleryController::class, 'addImage'])->name('add-image');
        Route::get('edit-gallery/{galleryId}', [PhotoGalleryController::class, 'editGallery'])->name('edit-gallery');

        // POST
        Route::post('create-album', [PhotoGalleryController::class, 'storeAlbum'])->name('store-album');
        Route::post('create-new-file-image/{galleryId}', [PhotoGalleryController::class, 'createNewFileImage'])->name('create-new-file-image');

        // PUT
        Route::put('update-album/{galleryId}', [PhotoGalleryController::class, 'updateAlbum'])->name('update-album');


        // DELETE
        Route::delete('delete-file-image/{imageId}', [PhotoGalleryController::class, 'destroyImage'])->name('delete-file-image');
        Route::delete('delete-gallery-image/{galleryId}', [PhotoGalleryController::class, 'destroyGalleryAlbum'])->name('delete-gallery-image');
    });

    // Gerenciamento de parceiros.
    Route::prefix('partners')->as('partners.')->group(function () {
        // GET
        Route::get('', [PartnersController::class, 'index'])->name('index');
        Route::get('create', [PartnersController::class, 'create'])->name('create');
        Route::get('show/{partnerID}', [PartnersController::class, 'show'])->name('show');

        // POST
        Route::post('', [PartnersController::class, 'store'])->name('store');

        // PUT
        Route::put('{partnerID}', [PartnersController::class, 'update'])->name('update');

        // DELETE
        Route::delete('{partnerID}', [PartnersController::class, 'destroy'])->name('destroy');
    });

    // Efetua a alteração do tema.
    Route::put('ui-theme', [SettingsController::class, 'iThemes'])->name('iThemes');
});
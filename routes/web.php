<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ComicController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/comic/{id}', [HomeController::class, 'show'])->name('comic.show');
Route::post('/save-comic-review', [HomeController::class, 'saveReview'])->name('comic.saveReview');




Route::group(['prefix' => '/'], function(){
    Route::group(['middleware' => 'guest'], function(){
        Route::get('register',[AccountController::class,'register'])->name('account.register');
        Route::post('register',[AccountController::class,'processRegister'])->name('account.processRegister');
        Route::get('login',[AccountController::class,'login'])->name('account.login');
        Route::post('login',[AccountController::class,'authenticate'])->name('account.authenticate');
    });
    Route::group(['middleware' => 'auth'], function(){
        Route::get('profile',[AccountController::class,'profile'])->name('account.profile');
        Route::get('logout',[AccountController::class,'logout'])->name('account.logout');
        Route::post('update-profile',[AccountController::class,'updateProfile'])->name('account.updateProfile');

        Route::group(['middleware'=>'check-admin'], function(){
            Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
            Route::post('admin/change-role/{id}', [AdminController::class, 'changeRole'])->name('admin.changeRole');

        });

        Route::group(['middleware'=>'check-publisher'], function(){
            Route::get('comics',[ComicController::class,'index'])->name('comic.index');
            Route::get('comics/create',[ComicController::class,'create'])->name('comic.create');
            Route::post('comics',[ComicController::class,'store'])->name('comic.store');
            Route::get('comics/edit/{id}',[ComicController::class,'edit'])->name('comic.edit');
            Route::post('comics/edit/{id}',[ComicController::class,'update'])->name('comic.update');
            Route::delete('comics',[ComicController::class,'delete'])->name('comic.delete');

            Route::get('reviews',[ReviewController::class,'index'])->name('account.reviews');
            Route::get('reviews/{id}',[ReviewController::class,'edit'])->name('account.reviews.edit');
            Route::post('reviews/{id}',[ReviewController::class,'updateReview'])->name('account.reviews.updateReview');
            Route::post('deleted-review',[ReviewController::class,'deleteReview'])->name('account.reviews.deleteReview');
        });


        Route::get('my-reviews',[AccountController::class,'myReviews'])->name('account.myReviews');

        Route::get('my-reviews/edit/{id}',[AccountController::class,'editReview'])->name('account.myReviews.editReview');
        Route::post('my-reviews/edit/{id}',[AccountController::class,'updateReview'])->name('account.myReviews.updateReview');
        Route::post('delete-my-review',[AccountController::class,'deleteReview'])->name('account.myReviews.deleteReview');

        Route::get('/comic/{id}/read', [ComicController::class, 'read'])->name('comic.read');

    });
});

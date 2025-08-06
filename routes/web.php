<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage and public pages
Route::get('/', [PublicController::class, 'home'])->name('public.home');
Route::get('/about', [PublicController::class, 'about'])->name('public.about');
Route::get('/search', [PublicController::class, 'search'])->name('public.search');

// Article routes
Route::get('/article/{slug}', [PublicController::class, 'showArticle'])->name('public.article.show');

// Category and tag filtering
Route::get('/category/{slug}', [PublicController::class, 'filterByCategory'])->name('public.category.filter');
Route::get('/tag/{slug}', [PublicController::class, 'filterByTag'])->name('public.tag.filter');

/*
|--------------------------------------------------------------------------
| Test Routes (Development Only)
|--------------------------------------------------------------------------
the 5 first routes ??????
*/

Route::get('/test', [TestController::class, 'basic'])->name('test.basic');
Route::get('/test-db', [TestController::class, 'database'])->name('test.database');
Route::get('/test-relations', [TestController::class, 'relations'])->name('test.relations');
Route::get('/test-controller', [TestController::class, 'controller'])->name('test.controller');
Route::get('/test-view', [TestController::class, 'view'])->name('test.view');
Route::get('/test-auth', [AuthController::class, 'testAuth'])->name('test.auth');
Route::get('/admin-direct', [AdminController::class, 'adminDirect'])->name('admin.direct');
Route::get('/test-create-article', [ArticleController::class, 'createArticle'])->name('test.create.article');
Route::get('/test-create-category', [CategoryController::class, 'createCategory'])->name('test.create.category');
Route::get('/test-create-tag', [TagController::class, 'createTag'])->name('test.create.tag');
Route::post('/test-store-category', [CategoryController::class, 'storeCategory'])->name('test.store.category');
Route::post('/test-store-tag', [TagController::class, 'storeTag'])->name('test.store.tag');
Route::post('/test-store-article', [ArticleController::class, 'storeArticle'])->name('test.store.article');
Route::put('/test-update-article/{article}', [ArticleController::class, 'updateArticle'])->name('test.update.article');
Route::patch('/test-update-profile', [ProfileController::class, 'updateProfile'])->name('test.update.profile');
Route::post('/test-store-multiple-tags', [TagController::class, 'storeMultipleTags'])->name('test.store.multiple.tags');
Route::post('/debug-multiple-tags', [TagController::class, 'debugMultipleTags'])->name('debug.multiple.tags');
Route::post('/simple-multiple-tags', [TagController::class, 'simpleMultipleTags'])->name('simple.multiple.tags');
Route::get('/test-article', [ArticleController::class, 'testArticle'])->name('test.article');
Route::get('/test-home', [PublicController::class, 'testHome'])->name('test.home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

// Routes d'authentification personnalisées
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.show');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:admin');

// Auth check for AJAX
Route::get('/auth/check', [AuthController::class, 'authenticate'])->name('auth.check');

/*
|--------------------------------------------------------------------------
| Admin Routes (Protected)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('analytics');
    Route::get('/analytics/export', [DashboardController::class, 'exportAnalytics'])->name('analytics.export');

    // Articles management
    Route::resource('articles', ArticleController::class);

    // Categories management
    Route::resource('categories', CategoryController::class);

    // Tags management
    Route::resource('tags', TagController::class);
    Route::post('/tags/store-multiple', [TagController::class, 'storeMultiple'])->name('tags.store-multiple');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


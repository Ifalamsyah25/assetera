<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [AssetController::class, 'index'])->name('dashboard');

    Route::middleware('role:admin,pimpinan,staff')->group(function () {
        Route::get('/assets', [AssetController::class, 'index'])->name('assets.index');
        Route::get('/assets/export', [AssetController::class, 'export'])->name('assets.export');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/maintenances', [MaintenanceController::class, 'index'])->name('maintenances.index');
    });

    Route::middleware('role:admin,staff')->group(function () {
        Route::get('/assets/create', [AssetController::class, 'create'])->name('assets.create');
        Route::get('/assets/input', [AssetController::class, 'createForm'])->name('assets.input');
        Route::post('/assets', [AssetController::class, 'store'])->name('assets.store');
        Route::get('/assets/{asset}/edit', [AssetController::class, 'edit'])->name('assets.edit');
        Route::put('/assets/{asset}', [AssetController::class, 'update'])->name('assets.update');
        Route::delete('/assets/{asset}', [AssetController::class, 'destroy'])->name('assets.destroy');

        Route::resource('transactions', TransactionController::class)->except(['index', 'show']);
        Route::resource('maintenances', MaintenanceController::class)->except(['index', 'show']);
    });

    Route::middleware('role:admin,pimpinan')->group(function () {
        Route::get('/reports/summary', [ReportController::class, 'summary'])->name('reports.summary');
        Route::get('/reports/activity', [ReportController::class, 'activity'])->name('reports.activity');
        Route::get('/reports/audit-logs', [ReportController::class, 'auditLogs'])->name('reports.audit-logs');
        Route::get('/reports/summary/export', [ReportController::class, 'exportSummary'])->name('reports.summary.export');
    });

    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/logout', function (Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    })->name('logout.get');
});

require __DIR__.'/auth.php';

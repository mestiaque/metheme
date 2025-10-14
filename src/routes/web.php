<?php

use Illuminate\Support\Facades\Route;
use Isotope\CRM\Http\Controllers\TaskController;
use Isotope\CRM\Http\Controllers\ProjectController;
use Isotope\CRM\Http\Controllers\ProposalController;
use Isotope\Metronic\Http\Middlewares\LocaleMiddleware;

Route::group(['prefix' => 'crm', 'as' => 'crm.', 'middleware' => ['web', 'auth', 'authorization', LocaleMiddleware::class]], function () {
    Route::resource('projects', ProjectController::class);
    Route::get('projects/delete/{id}', [ProjectController::class, 'delete'])->name('projects.delete');

    // Task routes
    Route::resource('tasks', TaskController::class);
    Route::get('tasks/{id}/delete', [TaskController::class, 'destroy'])->name('tasks.delete');
    
    Route::resource('proposals', ProposalController::class);
    Route::get('proposals/{id}/delete', [ProposalController::class, 'destroy'])->name('proposals.delete');
});
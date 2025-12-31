<?php

use App\Http\Controllers\MatchReportController;
use App\Livewire\EventLanding;
use App\Livewire\PlatformLanding;
use App\Livewire\PublicEvent;
use App\Models\GameMatch;
use Illuminate\Support\Facades\Route;
use App\Livewire\MatchOperator;
use App\Livewire\PublicMatch;
use App\Livewire\MatchScheduler;

Route::get('/', PlatformLanding::class)->name('platform.home');

Route::middleware(['auth'])->group(function () {
    
    Route::get('/operator/game/{game}', MatchOperator::class)->name('match.operator');
    
    Route::get('/app/drawing/{category}', App\Livewire\DrawingRoom::class)->name('drawing.room');
    Route::get('/app/scheduler/{category}', MatchScheduler::class)->name('match.scheduler');
    
});

Route::get('/game/{game}/report', [MatchReportController::class, 'download'])->name('game.report');


Route::prefix('{tenant:slug}/{event:slug}')
    ->name('public.')
    ->scopeBindings()
    ->group(function () {

        Route::get('/', EventLanding::class)->name('landing');

        Route::get('/match/{game}', PublicMatch::class)->name('match');

        Route::get('/{category}', PublicEvent::class)->name('category'); 

    });
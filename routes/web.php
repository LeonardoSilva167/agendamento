<?php

use App\Http\Controllers\VoyagerAgendamentosController;
use App\Http\Controllers\VoyagerAtendimentosController;
use App\Http\Controllers\VoyagerCategoriesController;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    return redirect('/admin');
});

Route::group(['prefix' => 'admin'], function () {
    
    Route::group(['prefix' => 'agendamentos'], function () {
        Route::get('selecionar-instalador/{id}', [VoyagerAgendamentosController::class, 'agendamentosSelecionarInstalador'])->name('agendamentos-selecionar-instalador');  
    });
    
    Route::group(['prefix' => 'atendimentos'], function () {
        Route::get('planejados', [VoyagerAtendimentosController::class, 'atendimentosPlanejados'])->name('atendimentos-planejados');       
        Route::get('executados', [VoyagerAtendimentosController::class, 'atendimentosExecutados'])->name('atendimentos-executados');
        Route::get('finalizar-atendiemento/{id}', [VoyagerAtendimentosController::class, 'atendimentosFinalizar'])->name('atendimentos-finalizar');  
    });
    
    Voyager::routes();
    
});

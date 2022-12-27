<?php

use App\Util\Constantes;
use Illuminate\Support\Facades\Auth;

function cargoIntalador(){
    return Auth::user()->cargo_usuario_id == Constantes::CARGO_INSTALADOR;
}

function cargoSupervisor(){
    return Auth::user()->cargo_usuario_id == Constantes::CARGO_SUPERVISOR;
}

function cargoSAdministrador(){
    return Auth::user()->cargo_usuario_id == Constantes::CARGO_ADMINISTRADOR;
}

function atendimentoFinalizado($status){
    return ($status == Constantes::ATENDIMENTO_FINALIZADO);
}

function atendimentoAguardando($status){
    return ($status == Constantes::ATENDIMENTO_AGUARDANDO);
}

function checkStatus($status){
    return ($status == Constantes::ATENDIMENTO_FINALIZADO || $status == Constantes::ATENDIMENTO_NAO_REALIZADO );
}

function distancia($lat1, $lon1, $lat2, $lon2) {

    $lat1 = deg2rad($lat1);
    $lat2 = deg2rad($lat2);
    $lon1 = deg2rad($lon1);
    $lon2 = deg2rad($lon2);
    
    $latD = $lat2 - $lat1;
    $lonD = $lon2 - $lon1;
    
    $dist = 2 * asin(sqrt(pow(sin($latD / 2), 2) +
    cos($lat1) * cos($lat2) * pow(sin($lonD / 2), 2)));
    $dist = $dist * 6371;
    return number_format($dist, 3, '.', '');
}
    
function isLoged(){
    return Auth::check();
}

function formataKilometros($km){
    return (floatval($km) > 1.000)? number_format($km, 1, '.', '') : $km;
}
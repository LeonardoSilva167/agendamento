<?php

namespace App\Http\Controllers;


use App\Models\Agendamento;
use App\Util\Constantes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoyagerAgendamentosController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{
    
    public function agendamentosSelecionarInstalador($id)
    {
        if(!isLoged()){return redirect()->route('voyager.login');}
        
        $id = $id;
        $agendamento = Agendamento::find($id);
        if(checkStatus($agendamento->status_id)){
            return redirect()->route('voyager.agendamentos.index');
        }

        $agendamentoModel = new Agendamento;
        $instaladores = $agendamentoModel->getInstaladores($id);        

        return view('vendor.voyager.agendamentos.agendamentos-add-instalador', compact( 'agendamento', 'instaladores'));
    }

}

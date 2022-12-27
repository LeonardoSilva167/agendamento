<?php

namespace App\Http\Controllers;

use App\Models\Atendimento;
use App\Util\Constantes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VoyagerAtendimentosController extends \TCG\Voyager\Http\Controllers\VoyagerBaseController
{

    protected $user;

    function __construct(Auth $auth)
    {
        $this->user = $auth::user();
    }


        /**
     * Carrega a tela de Atendimeentos Planejados
     *
     * @return void
     */
    public function atendimentosPlanejados()
    {
        if(!isLoged()){return redirect()->route('voyager.login');}

        $atendimentos = new Atendimento;
        $atendimentos = $atendimentos->getAtendimentosPlanejados();
        $podeFinalizar = self::permissaoFinalizarAtendimento();
        
        return view('vendor.voyager.atendimentos.atendimentos-planejados', compact('atendimentos','podeFinalizar'));
    }
    
    public function permissaoFinalizarAtendimento(){
        return cargoIntalador() || cargoSAdministrador();
    }

    public function atendimentosFinalizar($id){
        
        if(!isLoged()){return redirect()->route('voyager.login');}

        $atendimentos = new Atendimento;
        $status_atendimento = $atendimentos->getStatus(true);        
        $atendimento = Atendimento::find($id);
        
        if($atendimento){ 
            if(checkStatus($atendimento->status_atendimento_id) || cargoSupervisor() ){
                return redirect()->route('atendimentos-planejados');
            }
            return view('vendor.voyager.atendimentos.atendimentos-finalizar', compact('atendimento','status_atendimento'));
        }


    }

    /**
     * Carrega a tela de Atendimentos Executados
     *
     * @return void
     */
    public function atendimentosExecutados()
    {
        if(!isLoged()){return redirect()->route('voyager.login');}

        $atendimentos = new Atendimento;
        $atendimentos = $atendimentos->getAtendimentosExecutados();
        return view('vendor.voyager.atendimentos.atendimentos-executados', compact('atendimentos'));
    }


}

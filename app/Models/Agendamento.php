<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use App\Util\Constantes;
use Illuminate\Support\Facades\DB;

class Agendamento extends Model
{
     use HasFactory, SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = "agendamentos";
    
    public function scopeAgendamentoPorFornecedor($query)
    {
        // dd(Auth::user()->id);

        // Se o usuário for Supervisor
        if(cargoSupervisor()){
            return $query
            ->where('fornecedor_id',   Auth::user()->fornecedor_id)
            ->where('data_hora','>=', date('Y-m-d').' 00:00:00')
            ->orderBy('data_hora', 'asc');            
        }
        // Se o usuário for instalador
        else if(cargoIntalador() ){
            return $query
            ->where('instalador_id',   Auth::user()->id)
            ->whereBetween('data_hora', [date('Y-m-d').' 00:00:00', date('Y-m-d').' 23:59:59'])
            ->orderBy('data_hora', 'asc');            
        }
        else{
            return $query ->orderBy('data_hora', 'asc');
        }

        

    }

    /**
     * salva dados de Agendamento, os campos podem ser acessados por $this->campo
     *
     * @param array $options
     * @return void
     */
    public function save(array $options = [])
    {

        try {
            /**
             * so vai fazer update se existir atendimento e esse atendimento não estiver finalizado nem naõ aten
             */
            if(!$this->atendimentoExiste(trim($this->id))){
                DB::beginTransaction();
                $atendimento = new Atendimento;
                $atendimento->agendamento_id = $this->id;
                $atendimento->user_id = $this->instalador_id;
                $atendimento->status_atendimento_id = Constantes::ATENDIMENTO_AGUARDANDO;
                $atendimento->save();
                DB::commit();                
            }
        } catch (\Exception  $e) {
            DB::rollback();
        }

        parent::save();
    }

    /**
     * Verifica se existe atendimento criado para o agendamento.
     *
     * @param integer $id
     * @return void
     */
    public function atendimentoExiste($id = null){
        $sql = DB::table('public.atendimentos AS ate')->where('agendamento_id', $id);
        return $sql->first();
    }

    /**
     * Lista usuarios que possuem cargo de instalador
     *
     * @param integer $id
     * @return object
     */
    public function getInstaladores($id){
        
        $sql = DB::table('public.users AS u')
            ->selectRaw("u.name, u.id")
            ->join('public.agendamentos AS a', 'a.fornecedor_id', 'u.fornecedor_id')
            ->where('a.id', $id)
            ->where('u.cargo_usuario_id', Constantes::CARGO_INSTALADOR)
            ->orderBy('u.name');

        return $sql->get();
    }
}
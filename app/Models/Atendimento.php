<?php

namespace App\Models;

use App\Util\Constantes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use GeoIP as GeoIP;

class Atendimento extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $table = "atendimentos";

    public function save(array $options = [])
    {   

        // dd($this);
        // dd(distancia(-12.9813346,-38.4653612, -12.9741491,-38.4696483));
        // dd(distancia($this->latitude,$this->longitude, -8.0125318,-35.0080496));
        if($this->status_atendimento_id && $this->status_atendimento_id != Constantes::ATENDIMENTO_AGUARDANDO){
            $agendamento = new Agendamento;
            $agendamento = $agendamento::find($this->agendamento_id);
            try{
                DB::beginTransaction();        
                
                if(atendimentoFinalizado($this->status_atendimento_id)){
                    $agendamento->status_id = Constantes::AGENDAMENTO_FINALIZADO;
                }else{
                    $agendamento->status_id = Constantes::AGENDAMENTO_IMPRODUTIVO;
                }

                $agendamento->save();
                DB::commit();   

            }catch (\Exception  $e) {
                DB::rollback();
            }
            
            $this->distancia = distancia($this->latitude,$this->longitude, $agendamento->latitude,$agendamento->longitude);
            $this->data_hora_finalizado = date('Y-m-d h:i:s');
        }

        parent::save();

    }

    /**
     * Lista todos os atendimentos do instalado do dia atual
     *
     * @return object
     */
    public function getAtendimentosPlanejados(){
        $user = Auth::user();

        $sql = DB::table('public.atendimentos AS ate')
            ->selectRaw("
            ate.id
            ,ate.agendamento_id
            ,sa.descricao AS status
            ,ag.nome_cliente
            ,ag.telefone            
            , to_char(ag.data_hora, 'dd/mm/yyyy HH24:MI:SS') AS data_hora
            ,ag.endereco
            ,distancia
            ,foto_1
            ,foto_2
            ,foto_3
            ,foto_4
            ,foto_5
            ,u.name AS instalador_name")
            ->join('public.agendamentos AS ag', 'ag.id', '=', 'ate.agendamento_id')
            ->join('public.users AS u', 'u.id', '=', 'ate.user_id')
            ->join('public.status_atendimentos AS sa', 'sa.id', '=', 'ate.status_atendimento_id');
        if($user){
            if(cargoSupervisor() || cargoIntalador()){
                $sql->leftJoin('public.fornecedores AS f', 'f.id', '=', 'u.fornecedor_id');  
                
                // dd($user->cargo_usuario_id == Constantes::CARGO_INSTALADOR);
                if($user->cargo_usuario_id == Constantes::CARGO_INSTALADOR){
                    $sql->where('u.id', $user->id);
                    $sql->whereBetween('data_hora', [date('Y-m-d').' 00:00:00', date('Y-m-d').' 23:59:59']);
                    
                }
            }
        }
        $sql->where('ate.status_atendimento_id', '=', Constantes::ATENDIMENTO_AGUARDANDO);
        $sql->orderBy('ag.data_hora');
        return $sql->get();
    }
    
    /**
     * Lista todos os atendimentos até o dia atual
     *
     * @return object
     */
    public function getAtendimentosExecutados(){
        $user = Auth::user();
        $sql = DB::table('public.atendimentos AS ate')
            ->selectRaw("
            ate.id
            ,ate.agendamento_id
            ,sa.descricao AS status
            ,ag.nome_cliente
            ,ag.telefone            
            , to_char(ag.data_hora, 'dd/mm/yyyy HH24:MI:SS') AS data_hora
            ,ag.endereco
            ,distancia
            ,foto_1
            ,foto_2
            ,foto_3
            ,foto_4
            ,foto_5
            ,u.name AS instalador_name")
            ->join('public.agendamentos AS ag', 'ag.id', '=', 'ate.agendamento_id')
            ->join('public.users AS u', 'u.id', '=', 'ate.user_id')
            ->join('public.status_atendimentos AS sa', 'sa.id', '=', 'ate.status_atendimento_id');
        if($user){
            if(cargoSupervisor() || cargoIntalador()){
                $sql->leftJoin('public.fornecedores AS f', 'f.id', '=', 'u.fornecedor_id');               
                if($user->cargo_usuario_id == Constantes::CARGO_INSTALADOR){
                    $sql->where('u.id', $user->id);
                }
            }
        }
        
        $sql->where('ate.status_atendimento_id', '<>', Constantes::ATENDIMENTO_AGUARDANDO);
        $sql->whereBetween('data_hora', ['1990-01-01'.' 00:00:00', date('Y-m-d').' 23:59:59']);
        $sql->orderBy('ag.data_hora', 'desc');

        return  $sql->get();

    }

    public function getStatus($soImprodutivos = false){
        $sql = DB::table('public.status_atendimentos AS sa')
        ->selectRaw("id, descricao");
        if($soImprodutivos){
            $sql->where('improdutivo', 'true');
        }
        $sql->orderBy('descricao');
        return $sql->get();
    }
    
}

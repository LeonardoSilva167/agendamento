@php
//  dd($atendimento->foto_1);
if(!atendimentoAguardando($atendimento->status_atendimento_id)){
    header('Location: '.url('/').'/admin/atendimentos/planejados');
    exit();
}
@endphp

@extends('voyager::master')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-calendar"></i> Atendimentos / Finalizar Instalação
        </h1>
    </div>
@endsection

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form" class="form-edit-add" action="{{ route('voyager.atendimentos.update', $atendimento->id) }}"
                        method="POST" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="row form-group  col-md-12 ">
                                <div class="col-md-4">
                                    <label class="control-label" for="name">Foto do Número da Casa</label>
                                    <input type="file" name="foto_1" accept="image/*">
                                </div>
                                <div class="col-md-3">
                                    <div data-field-name="foto_1">
                                        <img src="@if($atendimento->foto_1) {{url('/storage/\/').$atendimento->foto_1}}  @endif" style="width:100px">
                                        {{-- <img src="@if($atendimento->foto_1) h ttp://localhost:8000/storage/{{$atendimento->foto_1}}  @endif" style="width:100px"> --}}
                                    </div>
                                </div>           
                            </div>
                            <div class="row form-group  col-md-12 ">
                                <div class="col-md-4">
                                    <label class="control-label" for="name">Foto do Aparelho</label>
                                    <input type="file" name="foto_2" accept="image/*">
                                </div>
                                <div class="col-md-3">
                                    <div data-field-name="foto_2">
                                        <img src="@if($atendimento->foto_2) {{url('/storage/\/').$atendimento->foto_2}}  @endif" style="width:100px">
                                    </div>
                                </div>           
                            </div>
                            <div class="row form-group  col-md-12 ">
                                <div class="col-md-4">
                                    <label class="control-label" for="name">Foto do Aplicativo na TV</label>
                                    <input type="file" name="foto_3" accept="image/*">
                                </div>
                                <div class="col-md-3">
                                    <div data-field-name="foto_3">
                                        <img src="@if($atendimento->foto_3) {{url('/storage/\/').$atendimento->foto_3}}  @endif" style="width:100px">
                                    </div>
                                </div>           
                            </div>
                            <div class="row form-group  col-md-12 ">
                                <div class="col-md-4">
                                    <label class="control-label" for="name">Foto Adicional</label>
                                    <input type="file" name="foto_4" accept="image/*">
                                </div>
                                <div class="col-md-3">
                                    <div data-field-name="foto_4">
                                        <img src="@if($atendimento->foto_4) {{url('/storage/\/').$atendimento->foto_4}}  @endif" style="width:100px">
                                    </div>
                                </div>           
                            </div>
                            <div class="row form-group  col-md-12 ">
                                <div class="col-md-4">
                                    <label class="control-label" for="name">Foto Adicional</label>
                                    <input type="file" name="foto_5" accept="image/*">
                                </div>
                                <div class="col-md-3">
                                    <div data-field-name="foto_5">
                                        <img src="@if($atendimento->foto_5) {{url('/storage/\/').$atendimento->foto_5}}  @endif" style="width:100px">
                                    </div>
                                </div>           
                            </div>
                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">Improdutivo</label>
                                <select class="form-control " name="status_atendimento_improdutivo_id" id="status_atendimento_improdutivo_id" tabindex="-1" aria-hidden="true">
                                    <option value="" data-select2-id="">Selecione</option>
                                    @foreach ($status_atendimento as $item)
                                    <option {{$atendimento->status_atendimento_id == $item->id? 'selected' : ''}}  value="{{$item->id}}">{{$item->descricao}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="row form-group  col-md-12 ">
                                <div class="col-md-6">
                                    <label class="control-label" for="name">Obs</label>
                                    <textarea class="form-control" name="obs" rows="5">{{$atendimento->obs}}</textarea>
                                </div>
                            </div>
                            <div class="row form-group  col-md-12 ">
                            </div>
                        </div>
                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="button" onclick="salvar(1)" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')

                            <button type="button" onclick="salvar(3)" id="finalizar" class="btn btn-success save">Finalizar</button>
                        </div>
                        <input type="hidden" name="status_atendimento_id">
                        <input type="hidden" name="agendamento_id" value="{{$atendimento->agendamento_id}}">
                        <input type="hidden" name="latitude">
                        <input type="hidden" name="longitude">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('javascript')
    <script>
        $(document).ready(function () {

        });

        function salvar(status){

            // var status_improdutivo = $("input[name='status_atendimento_improdutivo_id']").val();
            var status_improdutivo = $("#status_atendimento_improdutivo_id").val();
            console.log(status_improdutivo)

            status = status_improdutivo? status_improdutivo : status;          
            
            $("input[name='status_atendimento_id']").val(status);
            
            
            this.getLocation()
            setTimeout(() => {
                $("form").submit();     s           
            }, 3000);
        }
        
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
        }

        function showPosition(position) {
            
            $("input[name='latitude']").val(position.coords.latitude);
            $("input[name='longitude']").val(position.coords.longitude);
        }

    </script>
@endsection
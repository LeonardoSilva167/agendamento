@php

@endphp

@extends('voyager::master')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-calendar"></i> Agendamentos / Selecionar Instalador
        </h1>
    </div>
@endsection

@section('content')
    <div class="page-content edit-add container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <!-- form start -->
                    <form role="form" class="form-edit-add" action="{{ route('voyager.agendamentos.update', $agendamento->id) }}"
                        method="POST" enctype="multipart/form-data">
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="panel-body">
                            <div class="form-group  col-md-12 ">
                                <label class="control-label" for="name">Instalador</label>
                                <select class="form-control " name="instalador_id" tabindex="-1" aria-hidden="true">
                                    <option value="" data-select2-id="">Selecione</option>
                                    @foreach ($instaladores as $item)
                                    <option {{$agendamento->instalador_id == $item->id? 'selected' : ''}}  value="{{$item->id}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="panel-footer">
                            @section('submit-buttons')
                                <button type="submit" class="btn btn-primary save">{{ __('voyager::generic.save') }}</button>
                            @stop
                            @yield('submit-buttons')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

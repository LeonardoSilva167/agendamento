@php
    // dd(!filter_var("http://localhost:8000/storage/atendimentos\December2022\X07hXLLwp2n5noakjlNU.png", FILTER_VALIDATE_URL));
@endphp

@extends('voyager::master')

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-calendar"></i> Atendimentos / Planejados
        </h1>
        {{-- <a href="http://localhost:8000/admin/agendamentos/create" class="btn btn-success btn-add-new">
            <i class="voyager-plus"></i> <span>Add New</span>
        </a>
    
        <a class="btn btn-danger" id="bulk_delete_btn"><i class="voyager-trash"></i>
            <span>Bulk Delete</span></a> --}}
    </div>
@endsection
@section('content')
    <div class="page-content browse container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <form method="get" class="form-search">
                            <div id="search-input">
                                <div class="col-2">
                                    <select id="search_key" name="key" data-select2-id="search_key" tabindex="-1"
                                        class="select2-hidden-accessible" aria-hidden="true">
                                        <option value="id" data-select2-id="5">Número</option>
                                        <option value="status_id">Status Id</option>
                                        <option value="fornecedor_id">Fornecedor</option>
                                        <option value="agendamento_belongsto_fornecedore_relationship">Fornecedor</option>
                                        <option value="instalador_id">Instalador</option>
                                        <option value="agendamento_belongsto_user_relationship">Instalador</option>
                                        <option value="nome_cliente">Cliente</option>
                                        <option value="telefone">Telefone</option>
                                        <option value="data_hora">Data Hora</option>
                                        <option value="endereco">Endereco</option>
                                        <option value="agendamento_belongsto_status_agendamento_relationship">Status
                                        </option>
                                    </select><span class="select2 select2-container select2-container--default"
                                        dir="ltr" data-select2-id="4" style="width: 98.8889px;"><span
                                            class="selection"><span class="select2-selection select2-selection--single"
                                                role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                aria-disabled="false" aria-labelledby="select2-search_key-container"><span
                                                    class="select2-selection__rendered" id="select2-search_key-container"
                                                    role="textbox" aria-readonly="true" title="Número">Número</span><span
                                                    class="select2-selection__arrow" role="presentation"><b
                                                        role="presentation"></b></span></span></span><span
                                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                                <div class="col-2">
                                    <select id="filter" name="filter" data-select2-id="filter" tabindex="-1"
                                        class="select2-hidden-accessible" aria-hidden="true">
                                        <option value="contains" data-select2-id="7">contains</option>
                                        <option value="equals">=</option>
                                    </select><span class="select2 select2-container select2-container--default"
                                        dir="ltr" data-select2-id="6" style="width: 100px;"><span
                                            class="selection"><span class="select2-selection select2-selection--single"
                                                role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0"
                                                aria-disabled="false" aria-labelledby="select2-filter-container"><span
                                                    class="select2-selection__rendered" id="select2-filter-container"
                                                    role="textbox" aria-readonly="true"
                                                    title="contains">contains</span><span class="select2-selection__arrow"
                                                    role="presentation"><b
                                                        role="presentation"></b></span></span></span><span
                                            class="dropdown-wrapper" aria-hidden="true"></span></span>
                                </div>
                                <div class="input-group col-md-12">
                                    <input type="text" class="form-control" placeholder="Search" name="s"
                                        value="">
                                    <span class="input-group-btn">
                                        <button class="btn btn-info btn-lg" type="submit">
                                            <i class="voyager-search"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table id="dataTable" class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="dt-not-orderable">
                                                <input type="checkbox" class="select_all">
                                            </th>
                                            <th>Atendimento</th>
                                            <th>Agendamento</th>
                                            <th>Status</th>
                                            <th>Nome</th>
                                            <th>Telefone</th>
                                            <th>Dia / Horário</th>
                                            <th>Endereço</th>
                                            <th>Foto do Número da Casa</th>
                                            <th>Foto do Aparelho</th>
                                            <th>Foto do Aplicativo aberto</th>
                                            <th>Foto Adicional</th>
                                            <th>Foto Adicional</th>
                                            <th class="actions text-right dt-not-orderable">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($atendimentos as $atendimento)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="row_id" id="checkbox_{{$atendimento->id}}" value="{{$atendimento->id}}">
                                            </td>
                                            <td><div>{{$atendimento->id}}</div></td>
                                            <td><div>{{$atendimento->agendamento_id}}</div></td>
                                            <td><p>{{$atendimento->status}}</p></td>
                                            <td><p>{{$atendimento->nome_cliente}}</p></td>
                                            <td><p>{{$atendimento->telefone}}</p></td>
                                            <td><p>{{$atendimento->data_hora}}</p></td>
                                            <td><p>{{$atendimento->endereco}}</p></td>
                                            <td><img src="@if($atendimento->foto_1) {{url('/storage/\/').$atendimento->foto_1}}  @endif" style="width:100px"></td>
                                            <td><img src="@if($atendimento->foto_2) {{url('/storage/\/').$atendimento->foto_2}}  @endif" style="width:100px"></td>
                                            <td><img src="@if($atendimento->foto_3) {{url('/storage/\/').$atendimento->foto_3}}  @endif" style="width:100px"></td>
                                            <td><img src="@if($atendimento->foto_4) {{url('/storage/\/').$atendimento->foto_4}}  @endif" style="width:100px"></td>
                                            <td><img src="@if($atendimento->foto_5) {{url('/storage/\/').$atendimento->foto_5}}  @endif" style="width:100px"></td>
                                            <td class="no-sort no-click bread-actions">
                                                <a href="{{route('voyager.atendimentos.show', $atendimento->id)}}" title="View"
                                                    class="btn btn-sm btn-warning pull-right view">
                                                    <i class="voyager-eye"></i> <span
                                                        class="hidden-xs hidden-sm">View</span>
                                                    </a>
                                               @if ($podeFinalizar)
                                                <a href="{{route('atendimentos-finalizar', $atendimento->id)}}" title="Finalizar Atendimento"
                                                    class="btn btn-sm btn-success pull-right edit">
                                                    <i class="voyager-person"></i> <span
                                                        class="hidden-xs hidden-sm">Finalizar</span>
                                                </a>
                                               @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

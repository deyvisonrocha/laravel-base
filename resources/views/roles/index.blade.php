@extends('adminlte::page')

@section('title', trans('modules.roles.role'))

@section('content_header')
    <h1>{{ trans('modules.roles.role') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                @can('roles.create')
                    <a type="button" class="btn btn-primary" href="{{ route('roles.create') }}"><i class="fa fa-plus"></i> Adicionar</a>
                @endcan
                <div class="clearfix">&nbsp;</div>
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
</div>
@stop

@push('js')
    {!! $dataTable->scripts() !!}
@endpush

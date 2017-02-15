@extends('adminlte::page')

@section('title', trans('modules.users.user'))

@section('content_header')
    <h1>{{ trans('modules.users.user') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                @can('users.create')
                    <a type="button" class="btn btn-primary" href="{{ route('users.create') }}"><i class="fa fa-plus"></i> Adicionar</a>
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

@extends('adminlte::page')

@section('title', trans('modules.roles.role'))

@section('content_header')
    <h1>{{ trans('messages.show') }} {{ trans('modules.roles.role') }}</h1>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-body">
            <!-- Name Field -->
            <div class="form-group">
                {!! Form::label('id', trans('modules.roles.name')) !!}
                <p>{!! $role->name !!}</p>
            </div>

            <!-- Permissions Field -->
            <div class="form-group">
                {!! Form::label('id', trans('modules.permissions.permissions')) !!}
                @foreach($role->permissions as $permission)
                    <ul>
                        <li>{{ $permission['module'] . ' - ' . $permission['label'] }}</li>
                    </ul>
                @endforeach
            </div>

            <a href="{!! route('roles.index') !!}" class="btn btn-default">{{ trans('messages.form.back') }}</a>
        </div>
    </div>
@stop

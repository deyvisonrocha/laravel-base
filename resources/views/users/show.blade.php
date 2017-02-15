@extends('adminlte::page')

@section('title', trans('modules.users.user'))

@section('content_header')
    <h1>{{ trans('messages.show') }} {{ trans('modules.users.user') }}</h1>
@stop

@section('content')
    <div class="box box-primary">
        <div class="box-body">
            <div class="row" style="padding-left: 20px">
                <!-- Name Field -->
                <div class="form-group">
                    {!! Form::label('id', trans('modules.users.name')) !!}
                    <p>{!! $user->name !!}</p>
                </div>

                <!-- Role Field -->
                <div class="form-group">
                    {!! Form::label('id', trans('modules.roles.role')) !!}
                    <p>{!! $user->roles()->first()->name !!}</p>
                </div>

                <!-- E-mail Field -->
                <div class="form-group">
                    {!! Form::label('id', trans('modules.users.email')) !!}
                    <p>{!! $user->email !!}</p>
                </div>

                <a href="{!! route('users.index') !!}" class="btn btn-default">{{ trans('messages.form.back') }}</a>
            </div>
        </div>
    </div>
@stop

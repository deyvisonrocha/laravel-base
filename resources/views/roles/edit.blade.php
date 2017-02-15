@extends('adminlte::page')

@section('title', trans('modules.roles.role'))

@section('content_header')
    <h1>{{ trans('messages.edit') }} {{ trans('modules.roles.role') }}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {!! Form::model($role, ['route' => ['roles.update', $role->id], 'method' => 'patch']) !!}
                <div class="box-body">
                    {!! Form::hidden('id', $role->id) !!}
                    @include('roles.fields')
                </div>

                <div class="box-footer">
                    <!-- Submit Field -->
                    <div class="col-lg-offset-3 col-lg-6">
                        <a href="{!! route('roles.index') !!}" class="btn btn-default">{{ trans('messages.form.cancel') }}</a>
                        <div class="pull-right">
                            {!! Form::submit(trans('messages.form.save'), ['class' => 'btn btn-primary']) !!}
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

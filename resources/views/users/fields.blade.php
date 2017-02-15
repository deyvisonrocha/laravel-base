<div class="row">
    <!-- Name Field -->
    <div class="form-group col-md-4">
        {!! Form::label('name', trans('modules.users.name')) !!}
        {!! Form::text('name', null, ['class' => 'form-control']) !!}
    </div>

    <!-- Role Field -->
    <div class="form-group col-md-4">
        {!! Form::label('role_id', trans('modules.users.role_id')) !!}
        {!! Form::select('role_id', $roles, null, ['class' => 'form-control']) !!}
    </div>

    <!-- E-mail Field -->
    <div class="form-group col-md-4">
        {!! Form::label('email', trans('modules.users.email')) !!}
        {!! Form::text('email', null, ['class' => 'form-control']) !!}
    </div>
</div>

<div class="row">
    <!-- Password Field -->
    <div class="form-group col-md-4">
        {!! Form::label('password', trans('modules.users.password')) !!}
        {!! Form::password('password', ['class' => 'form-control']) !!}
    </div>
    <!-- Password Confirmation Field -->
    <div class="form-group col-md-4">
        {!! Form::label('password_confirmation', trans('modules.users.password_confirmation')) !!}
        {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
    </div>
</div>

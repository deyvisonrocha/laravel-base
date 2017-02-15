
<!-- Name Field -->
<div class="form-group">
    {!! Form::label('name', trans('modules.roles.name')) !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>

<!-- Permissions Field -->
<div class="form-group">
    {!! Form::label('permissions', trans('modules.permissions.permissions')) !!}
    <select class="permissions form-control multiselect_list" id="permissionSelect" name="permissions[]" multiple="multiple">
        @foreach($permissions as $module => $permissions)
            <optgroup label="{{ $module }}">
                @foreach($permissions as $permission)
                    <?php $selected = '' ?>
                    @if (isset($permissions_selected))
                        @foreach($permissions_selected as $permission_selected)
                            @if ($permission['id'] == $permission_selected))
                                <?php $selected = 'selected' ?>
                            @endif
                        @endforeach
                    @endif
                    <option value="{{ $permission['id'] }}" {{ $selected }} >{{ $permission['label'] }}</option>
                @endforeach
            </optgroup>
        @endforeach
    </select>
</div>

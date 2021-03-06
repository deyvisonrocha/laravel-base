{!! Form::open(['route' => ['users.destroy', $id], 'method' => 'delete']) !!}
    @can('users.show')
        <a type="button" href="{!! route('users.show', [$id]) !!}" class='btn btn-success btn-sm tooltips' data-toggle="tooltip" data-placement="top" title="{{ trans('messages.show') }}"><i class="fa fa-file-text-o"></i></a>
    @endcan
    @can('users.edit')
        <a href="{!! route('users.edit', [$id]) !!}" class='btn btn-primary btn-sm tooltips' data-toggle="tooltip" data-placement="top" title="{{ trans('messages.edit') }}"><i class="fa fa-edit"></i></a>
    @endcan
    @can('users.destroy')
        <!-- Button trigger modal -->
        <span data-toggle="modal" data-target="#deleteModal{{ $id }}">
            <button type="button" class="btn btn-danger btn-sm tooltips" data-toggle="tooltip" data-placement="top" title="{{ trans('messages.delete') }}">
                <i class="fa fa-trash-o"></i>
            </button>
        </span>

        <!-- Modal -->
        <div class="modal fade" id="deleteModal{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel{{ $id }}">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('messages.close') }}"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="deleteModalLabel{{ $id }}">Atenção!</h4>
                    </div>
                    <div class="modal-body">
                        Você tem certeza desta ação?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('messages.cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ trans('messages.delete') }}</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
{!! Form::close() !!}

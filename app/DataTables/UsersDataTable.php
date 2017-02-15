<?php

namespace App\DataTables;

use App\Models\User;
use Yajra\Datatables\Services\DataTable;

class UsersDataTable extends BaseDataTable
{
    protected $action_view = 'users.datatables_actions';

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->editColumn('role_id', function ($user) {
                return $user->roles()->first()->name;
            })
            ->addColumn('action', $this->action_view)
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = User::query();

        return $this->applyScopes($query);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'name',
            'role_id',
            'email',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'usersdatatables_' . time();
    }
}

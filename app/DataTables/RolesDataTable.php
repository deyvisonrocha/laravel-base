<?php

namespace App\DataTables;

use App\Models\Role;
use Yajra\Datatables\Services\DataTable;

class RolesDataTable extends BaseDataTable
{
    protected $action_view = 'roles.datatables_actions';

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = Role::query();

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
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'rolesdatatables_' . time();
    }
}

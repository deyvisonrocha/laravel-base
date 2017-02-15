<?php

namespace App\DataTables;

use Form;
use Yajra\Datatables\Services\DataTable;

class BaseDataTable extends DataTable
{
    protected $action_view = 'vendor.datatables.datatables_actions';
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return $this->datatables
            ->eloquent($this->query())
            ->addColumn('action', $this->action_view)
            ->make(true);
    }

    public function query()
    {
        return parent::query();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->addAction(['class' => 'col-md-1 text-nowrap', 'title' => trans('messages.tables.actions')])
                    ->ajax('')
                    ->parameters([
                        'dom' => 'Bfrtip',
                        'scrollX' => false,
                        'buttons' => [
                            [
                                'extend'  => 'excel',
                                'text'    => '<i class="fa fa-file-excel-o"></i> Excel',
                                'exportOptions' => ['columns' => ':visible']
                            ],
                            [
                                'text' => '<i class="fa fa-print"></i> Imprimir',
                                'extend' => 'print',
                                'exportOptions' => ['columns' => ':visible']
                            ],
                            [
                                'text' => '<i class="fa fa-column"></i> Colunas Visíveis',
                                'extend' => 'colvis'
                            ]
                        ],
                        'language' => ['url' => '//cdn.datatables.net/plug-ins/1.10.12/i18n/Portuguese-Brasil.json']
                    ]);
    }
}

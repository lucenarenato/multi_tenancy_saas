<?php

namespace App\DataTables\Superadmin;

use App\Models\PageSetting;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PageSettingDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return datatables()
            ->eloquent($query)
            ->addIndexColumn()
            ->addColumn('action', function (PageSetting $row) {
                return view('superadmin.page-settings.action', compact('row'));
            })
            ->rawColumns(['action']);
    }

    public function query(PageSetting $model): QueryBuilder
    {
        return $model->newQuery();
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->setTableId('pagesetting-table')
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->orderBy(1)
        ->language([
            "paginate" => [
                "next" => '<i class="ti ti-chevron-right"></i>',
                "previous" => '<i class="ti ti-chevron-left"></i>'
            ],
            'lengthMenu' => __('_MENU_ entries per page'),
            "searchPlaceholder" => __('Search...'), "search" => ""
        ])
        ->initComplete('function() {
            var table = this;
            var searchInput = $(\'#\'+table.api().table().container().id+\' label input[type="search"]\');
            searchInput.removeClass(\'form-control form-control-sm\');
            searchInput.addClass(\'dataTable-input\');
            var select = $(table.api().table().container()).find(".dataTables_length select").removeClass(\'custom-select custom-select-sm form-control form-control-sm\').addClass(\'dataTable-selector\');
        }')
        ->parameters([
            "dom" =>  "
                           <'dataTable-top row'<'dataTable-dropdown page-dropdown col-lg-2 col-sm-12'l><'dataTable-botton table-btn col-lg-6 col-sm-12'B><'dataTable-search tb-search col-lg-3 col-sm-12'f>>
                         <'dataTable-container'<'col-sm-12'tr>>
                         <'dataTable-bottom row'<'col-sm-5'i><'col-sm-7'p>>
                           ",
            'buttons'   => [
                ['extend' => 'create', 'className' => 'btn btn-light-primary no-corner me-1 add_module', 'action' => " function ( e, dt, node, config ) {
                    window.location = '" . route('pagesetting.create') . "';
               }"],
                [
                    'extend' => 'collection', 'className' => 'btn btn-light-secondary me-1 dropdown-toggle', 'text' => '<i class="ti ti-download"></i> Export', "buttons" => [
                        ["extend" => "print", "text" => '<i class="fas fa-print"></i> Print', "className" => "btn btn-light text-primary dropdown-item no-corner", "exportOptions" => ["columns" => [0, 1, 3]]],
                        ["extend" => "csv", "text" => '<i class="fas fa-file-csv"></i> CSV', "className" => "btn btn-light text-primary dropdown-item no-corner", "exportOptions" => ["columns" => [0, 1, 3]]],
                        ["extend" => "excel", "text" => '<i class="fas fa-file-excel"></i> Excel', "className" => "btn btn-light text-primary dropdown-item no-corner", "exportOptions" => ["columns" => [0, 1, 3]]],
                        ["extend" => "pdf", "text" => '<i class="fas fa-file-pdf"></i> PDF', "className" => "btn btn-light text-primary dropdown-item no-corner", "exportOptions" => ["columns" => [0, 1, 3]]],
                    ],
                ],
                ['extend' => 'reset', 'className' => 'btn btn-light-danger me-1'],
                ['extend' => 'reload', 'className' => 'btn btn-light-warning'],
            ],
            "scrollX" => true,
            "drawCallback" => 'function( settings ) {
                var tooltipTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=tooltip]")
                  );
                  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                  });
                  var popoverTriggerList = [].slice.call(
                    document.querySelectorAll("[data-bs-toggle=popover]")
                  );
                  var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
                    return new bootstrap.Popover(popoverTriggerEl);
                  });
                  var toastElList = [].slice.call(document.querySelectorAll(".toast"));
                  var toastList = toastElList.map(function (toastEl) {
                    return new bootstrap.Toast(toastEl);
                  });
            }'
        ])->language([
            'buttons' => [
                'create' => __('Create'),
                'export' => __('Export'),
                'print' => __('Print'),
                'reset' => __('Reset'),
                'reload' => __('Reload'),
                'excel' => __('Excel'),
                'csv' => __('CSV'),
            ]
        ]);
    }

    public function getColumns(): array
    {
        return [
            Column::make('No')->data('DT_RowIndex')->name('DT_RowIndex')->searchable(false)->orderable(false),
            Column::make('title')->title(__('Page Title')),
            Column::make('type')->title(__('Type')),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
        ];
    }

    protected function filename(): string
    {
        return 'PageSetting_' . date('YmdHis');
    }
}

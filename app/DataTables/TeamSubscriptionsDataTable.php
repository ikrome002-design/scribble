<?php

namespace App\DataTables;

use App\Models\TeamSubscription;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TeamSubscriptionsDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     * @return \Yajra\DataTables\EloquentDataTable
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->rawColumns(['action'])
            ->editColumn('First Name', function ($s) {
                return $s->client->fname;
            })
            ->editColumn('Last Name', function ($s) {
                return $s->client->lname;
            })
            ->editColumn('Email', function ($s) {
                return $s->client->email;
            })
            ->editColumn('opted_out', function ($s) {
                if ($s->opted_out == 'No') {
                    return 'No';
                } else {
                    return  'Yes, ' . $s->opted_out_date;
                }
            })
            ->addColumn('action', function ($s) {
                $html = "<a class='btn btn-complete btn-sm me-2' href='/team/subscription/$s->id/edit'><i
                                                        class='fa fa-pencil'></i>Edit</a>";
                $html .= "<a class='btn btn-danger btn-sm me-2 delete' href='/team/subscription/$s->id'><i
                                                        class='fa fa-trash'></i>Delete</a>";
                return $html;
            })

            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\TeamSubscription $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(TeamSubscription $model): QueryBuilder
    {

        return $model->newQuery();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('teamsubscriptions-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('Blfrtip')
            ->selectStyleSingle()
            ->buttons([
                Button::make('excel')->text('<i class="fa-solid fa-file-excel"></i> Excel'),
                Button::make('csv')->text('<i class="fa-solid fa-file-csv"></i> CSV'),
                Button::make('pdf')->text('<i class="fa-solid fa-file-pdf"></i> PDF'),
                Button::make('print'),
                Button::make('reset'),
                Button::make('reload'),
            ])
            ->parameters([
                "sPaginationType" => "full_numbers",
                "language" => [
                    "paginate" => [
                        "first" => '<i class="fa-solid fa-backward-fast"></i>',
                        "previous" => '<i class="fa-solid fa-backward-step"></i>',
                        "next" => '<i class="fa-solid fa-forward-step"></i>',
                        "last" => '<i class="fa-solid fa-forward-fast"></i>',
                    ]
                ],

                'responsive' => true,

            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::computed('First Name'),
            Column::computed('Last Name'),
            Column::computed('Email'),
            Column::make(['data' => 'team_members', 'name' => 'team_members', 'title' => 'team_members']),
            Column::make(['data' => 'sub_status', 'name' => 'sub_status', 'title' => 'status']),
            Column::make(['data' => 'opted_out', 'name' => 'opted_out', 'title' => 'opted out']),
            Column::make('team_recurring_date'),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->addClass('text-center'),

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'TeamSubscriptions_' . date('YmdHis');
    }
}

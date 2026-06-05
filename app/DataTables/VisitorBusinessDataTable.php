<?php

namespace App\DataTables;

use App\Models\VisitorBusiness;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class VisitorBusinessDataTable extends DataTable
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
            ->editColumn('pro_subscription_id', function ($p) {
                return $p->proSubscription->business_name;
            })
            ->addColumn('action', function ($p) {
                $html = '';
                $html .= "<a class='btn btn-complete btn-sm me-2' href='/visitors/per/business/$p->pro_subscription_id'><i
                                                        class='fa fa-eye'></i> View Visitors Per Subscrption</a>";
                $html .= "<a class='btn btn-primary btn-sm me-2' href='/visitors/per/business/minor/$p->id'><i
                                                        class='fa fa-eye'></i> View Visitors per Business</a>";
                if (auth('client')->check() || auth('admin')) {
                    $html .= "<a class='btn  btn-sm btn-success me-2' href='/visitorBusiness/$p->id/edit'>Edit</a>";
                    $html .= "<a class='btn btn-sm btn-danger delete' href='/visitorBusiness/$p->id'>delete</a>";
                }
                return $html;
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\VisitorBusiness $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(VisitorBusiness $model): QueryBuilder
    {
        if ($this->staff_id) {
            return $model->whereHas('ProSubscription', function ($q) {
                $q->whereHas('staffVisitorRole', function ($q) {
                    $q->where('staff_id', $this->staff_id)
                        ->where('view', 1);
                });
            })->orderBy('id', 'desc')->newQuery();
        }

        if ($this->cl_id) {
            return $model->whereHas('ProSubscription', function ($q) {
                $q->where('cl_id', $this->cl_id);
            })->orderBy('id', 'desc')->newQuery();
        }
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
            ->setTableId('visitorbusiness-table')
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
            Column::make(['data' => 'pro_subscription_id', 'name' => 'pro_subscription_id', 'title' => 'Subscription Business']),
            Column::make(['data' => 'business_name', 'name' => 'business_name', 'title' => 'Business Name']),
            Column::computed('action')
                ->exportable(false)
                ->printable(false)

        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'VisitorBusiness_' . date('YmdHis');
    }
}

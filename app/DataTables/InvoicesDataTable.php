<?php

namespace App\DataTables;

use App\InvoiceItems;
use App\Invoices;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class InvoicesDataTable extends DataTable
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
            ->rawColumns(['action', 'status'])
            ->addColumn('description', function ($in) {
                $items = InvoiceItems::where('invoice_no', $in->invoice_no)->get();
                $i = 0;
                $desc = '';
                foreach ($items as $item) {
                    $desc .= $item->description . ' ';
                    $i++;
                    if ($i == 2) break;
                }
                return $desc;
            })
            ->editColumn('status', function ($in) {
                if ($in->status == 'Unpaid') {
                    return
                        '<span class="label label-warning">Unpaid</span>';
                } elseif ($in->status == 'Paid') {
                    return      '<span class="label label-success">Paid</span>';
                } elseif ($in->status == 'Cancelled') {
                    return         '<span class="label label-danger">Cancelled</span>';
                } else {
                    return          '<span class="label label-info">Partially Paid</span>';
                }
            })
            ->addColumn('action', function ($in) {

                if ($this->cl_id) {
                    return  "<a href='/user/invoices/view/$in->invoice_no' class='btn btn-success btn-xs'><i class='fa fa-eye'></i>View|Pay</i></a>";
                }
                if (auth('admin')->check()) {
                    return  "<a href='/invoices/view/$in->id'  class='btn btn-success btn-xs me-2'><i class='fa fa-eye'></i>View</a>
                 <a href='/invoices/edit/$in->id'  class='btn btn-primary btn-xs me-2'><i class='fa fa-edit'></i>Edit</a>
                 <a href='/invoices/delete-invoice/$in->id' class='btn btn-danger btn-xs delete'><i class='fa fa-trash'></i>Delete</a>";
                }
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\invoice $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(invoices $model): QueryBuilder
    {
        if ($this->cl_id) {
            return  $model->where('cl_id', $this->cl_id)->orderBY('id', 'desc')->newQuery();
        }
        if (auth('admin')->check()) {
            return $model->orderBy('id', 'desc')->newQuery();
        }
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('invoices-table')
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
            Column::make('invoice_no'),
            Column::computed('description'),
            Column::make(['data' => 'duedate', 'name' => 'duedate', 'title' => 'Due Date']),
            column::make('status'),
            Column::make(['data' => 'total', 'name' => 'total', 'title' => 'Total (KES)']),
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
        return 'invoices_' . date('YmdHis');
    }
}

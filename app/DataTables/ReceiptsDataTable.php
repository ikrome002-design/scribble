<?php

namespace App\DataTables;

use App\Receipts;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use App\InvoiceItems;

class ReceiptsDataTable extends DataTable
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
            ->addColumn('description', function ($receipt) {
                $items = InvoiceItems::whereHas('invoice', function ($q) use ($receipt) {
                    $q->where('invoice_no', $receipt->invoice_no)
                        ->orWhereHas('massInvoices', function ($q) use ($receipt) {
                            $q->where('mass_invoice_no', $receipt->invoice_no);
                        });
                })->get();

                $i = 0;
                $desc = '';
                foreach ($items as $item) {
                    $desc .= $item->description . ' ';
                    $i++;
                    if ($i == 2) break;
                }
                return $desc;
            })
            ->addColumn('action', function ($in) {

                if ($this->cl_id) {
                    return  "<a href='/user/receipts/view/$in->receipt_no' class='btn btn-success btn-xs'><i class='fa fa-eye'></i>View</i></a>";
                }
                if (auth('admin')->check()) {
                    return  "<a href='/receipts/view/$in->id'  class='btn btn-success btn-xs me-2'><i class='fa fa-eye'></i>View</a>
                 <a href='/receipts/delete/$in->id' class='btn btn-danger btn-xs delete'><i class='fa fa-trash'></i>Delete</a>";
                }
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Receipt $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Receipts $model): QueryBuilder
    {
        if ($this->cl_id) {
            return  $model->where('cl_id', $this->cl_id)->orderBy('id', 'desc')->newQuery();
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
            ->setTableId('receipts-table')
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
            Column::make('receipt_no'),
            Column::make('invoice_no'),
            Column::computed('description'),
            Column::make(['data' => 'datepaid', 'name' => 'datepaid', 'title' => 'Date Paid']),
            column::make('mpesa_ref'),
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
        return 'Receipts_' . date('YmdHis');
    }
}

<?php

namespace App\DataTables;

use App\Models\Staff;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class StaffDataTable extends DataTable
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
            ->rawColumns(['image', 'action'])
            ->editColumn('image', function ($i) {
                if ($i->image) {
                    return "<img style='width:50px;height:auto' src='/private/staff/$i->image'/>";
                }
                return;
            })
            ->editColumn('staff_id', function ($v) {
                if ($v->staff_id) {

                    return   $v->staff()->first()->fname . ' ' . $v->staff()->first()->lname . ' (' . $v->staff()->first()->email . ')';
                } else {

                    return   'Business Owner';
                }
            })
            ->editColumn('pro_subscription_id', function ($v) {
                return $v->proSubscription()->first()->business_name;
            })
            ->addColumn('action', function ($s) {
                $html = '';
                if (auth('client')->check() || auth('admin')->check()) {
                    if ($this->work_history) {
                        if ($s->proSubscription()->first()->transactions) {
                            $html .= "<a class='btn btn-info btn-xs me-2' href='/staff/work/history/transactions/$s->id'>
                        <i class='fa fa-eye'></i>View Transactions</a>";
                        }
                        if ($s->proSubscription()->first()->visitors) {
                            $html .= "<a class='btn btn-complete btn-xs me-2' href='/staff/work/history/visitors/$s->id'>
                        <i class='fa fa-eye'></i>View Visitors</a>";
                        }
                        return  $html;
                    }
                    if ($this->assign_roles) {
                        $html .= "<a class='btn btn-info btn-xs me-2' href='/staff/staff/roles/$s->id'>
                        <i class='fa fa-edit'></i>Re Assign Staff Roles</a>";

                        $html .= "<a class='btn btn-complete btn-xs me-2' href='/staff/visitors/roles/$s->id'>
                        <i class='fa fa-edit'></i>Re Assign Visitors  Roles</a>";
                        $html .= "<a class='btn btn-success btn-xs' href='/staff/transactions/roles/$s->id'>
                        <i class='fa fa-edit'></i>Re Assign Transactions Roles</a>";
                    } else {

                        $html .= "<a class='btn btn-complete btn-xs me-2'
                                                        href='/staff/$s->id/edit'><i class='fa fa-edit'></i>
                                                        Manage
                                                    </a>";

                        $html .= "<button class='btn btn-info btn-xs send-otp me-2' id='$s->id'>
                                                        Send OTP
                                                    </button>";


                        $html .= "<a class='btn btn-danger btn-xs delete me-2'
                                                            href='/staff/$s->id'><i class='fa fa-trash'></i>
                                                            Delete
                                                        </a>";

                        $html .= "<input type='checkbox' name='staff_id[]' value='$s->id'
                                                    class='delete-selected text-danger'><label class='text-danger'>Select to
                                                    delete</label>";
                    }
                    return $html;
                }
                if (auth('staff')->check()) {
                    if ($this->work_history) {
                        if ($s->proSubscription()->first()->transactions) {
                            $html .= "<a class='btn btn-info btn-xs me-2' href='/staff/work/history/transactions/$s->id'>
                        <i class='fa fa-eye'></i>View Transactions</a>";
                        }
                        if ($s->proSubscription()->first()->visitors) {
                            $html .= "<a class='btn btn-complete btn-xs me-2' href='/staff/work/history/visitors/$s->id'>
                        <i class='fa fa-eye'></i>View Visitors</a>";
                        }
                        return  $html;
                    }

                    if ($this->assign_roles) {
                        if ($s->proSubscription->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->assign_roles ?? 0) {
                            $html .= "<a class='btn btn-info btn-xs me-2' href='/staff/staff/roles/$s->id'>
                        <i class='fa fa-edit'></i>Re Assign Staff Roles</a>";
                        } else {
                            $html .= "<a class='btn btn-info btn-xs me-2 disabled'><i class='fa fa-edit'></i>Re Assign Staff Roles</a>";
                        }

                        if ($s->proSubscription->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->assign_roles ?? 0) {
                            $html .= "<a class='btn btn-complete btn-xs me-2' href='/staff/visitors/roles/$s->id'>
                        <i class='fa fa-edit'></i>Re Visitor Visitor Roles</a>";
                        } else {
                            $html .=  "<a class='btn btn-complete btn-xs me-2 disabled' >
                        <i class='fa fa-edit'></i>Re Assign Visitor Roles</a>";
                        }

                        if ($s->proSubscription->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->assign_roles ?? 0) {
                            $html .= "<a class='btn btn-success btn-xs me-2' href='/staff/transactions/roles/$s->id'>
                        <i class='fa fa-edit'></i>Re Assign Transaction Roles</a>";
                        } else {
                            $html .= "<a class='btn btn-success btn-xs me-2 disabled' > <i class='fa fa-edit'></i>Re Assign Transaction Roles</a>";
                        }
                    } else {
                        if (auth('staff')->user()->staffStaffRole()->where('pro_subscription_id', $s->pro_subscription_id)->first()->edit ?? 0) {
                            $html .= "<a class='btn btn-complete btn-xs me-2'
                                                        href='/staff/$s->id/edit'><i class='fa fa-edit'></i>
                                                        Manage
                                                    </a>";
                        } else {
                            $html .= "<a class='btn btn-complete btn-xs me-2 disabled'><i class='fa fa-edit'></i>
                                                        Manage
                                                    </a>";
                        }
                        if (auth('staff')->user()->staffStaffRole()->where('pro_subscription_id', $s->pro_subscription_id)->first()->otp ?? 0) {
                            $html .= "<button class='btn btn-info btn-xs send-otp' id='$s->id'>
                                                        Send OTP
                                                    </button>";
                        } else {
                            $html .= "<button class='btn btn-info btn-xs send-otp disabled me-2' >
                                                        Send OTP
                                                    </button>";
                        }

                        if (auth('staff')->user()->staffStaffRole()->where('pro_subscription_id', $s->pro_subscription_id)->first()->delete ?? 0) {
                            if ($s->id != auth('staff')->user()->id)
                                $html .=  "<a class='btn btn-danger btn-xs delete me-2'
                                                            href='/staff/$s->id'><i class='fa fa-trash'></i>
                                                            Delete
                                                        </a>";
                        } else {
                            $html .= "<button class='btn btn-danger btn-xs send-otp disabled''>
                                                        Delete
                                                    </button>";
                        }
                    }
                    return $html;
                }
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Staff $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Staff $model): QueryBuilder
    {
        if ($this->staff_id) {
            return  $model->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffStaffRole', function ($q) {
                        $q->where('staff_id', $this->staff_id)
                            ->where('view', 1);
                    });
            })->orderBy('id', 'desc')->newQuery();
        }

        if ($this->cl_id) {
            return $model->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', $this->cl_id);
            })->orderBy('id', 'desc')->newQuery();
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
            ->setTableId('staff-table')
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
            Column::make(['data' => 'image', 'name' => 'image', 'title' => 'Image']),
            Column::make(['data' => 'unique_id', 'name' => 'unique_id',  'title' => 'Unique Id']),
            Column::make(['data' => 'fname', 'name' => 'lname', 'title' => 'First Name']),
            Column::make(['data' => 'lname', 'name' => 'lname', 'title' => 'Last Name']),
            Column::make(['data' => 'email', 'name' => 'email',  'title' => 'email']),
            Column::make(['data' => 'phone_number', 'name' => 'phone_number',  'title' => 'Phone']),
            Column::make(['data' => 'pro_subscription_id', 'name' => 'pro_subscription_id',  'title' => 'Main Working Station']),
            Column::make(['data' => 'role', 'name' => 'role',  'title' => 'Main Role']),
            Column::make(['data' => 'status', 'name' => 'status',  'title' => 'Status']),
            Column::make(['data' => 'staff_id', 'name' => 'staff_id',  'title' => 'Added/Last Edited By']),
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
        return 'Staff_' . date('YmdHis');
    }
}

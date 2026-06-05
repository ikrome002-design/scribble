<?php

namespace App\DataTables;

use App\Models\ProSubscription;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Log;

class ProSubscriptionsDataTable extends DataTable
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
            ->rawColumns(['transaction sms', 'check out', 'action', 'view', 'add', 'edit', 'delete', 'last 24 hours', 'last one month', 'all', 'daily summary', 'monthly summary', 'all summary', 're assign roles'])
            ->setRowId('id')
            ->editColumn('cl_id', function ($p) {
                return   $p->client->fname . ' ' . $p->client->lname;
            })
            ->editColumn('email', function ($p) {
                return   $p->client->email;
            })
            ->addColumn('services', function ($p) {
                $html = '';
                if ($p->staff) {
                    $html .= 'staff, ';
                }
                if ($p->vistors) {
                    $html .= 'visitors, ';
                }

                if ($p->transactions) {
                    $html .= 'transactions';
                }
                return $html;
            })
            ->addColumn('action', function ($p) {
                $html = '';

                if ($this->transactions_per_business) {

                    return  "<a class='btn btn-complete btn-xs' href='/transactions/per/business/$p->id'><i
                                                        class='fa fa-eye'></i> View Transactions</a>";
                }
                if ($this->visitors_per_business) {
                    return  "<a class='btn btn-complete btn-xs' href='/visitors/per/business/$p->id'><i
                                                        class='fa fa-eye'></i> View Visitors</a>";
                }
                if (auth('client')->check()) {
                    if ($p->sub_status != 'Active') {
                        return  "<a class='btn btn-complete btn-xs' href='/prosubscriptions/generate/invoice/$p->id'><i
                                                                                          class='fa fa-eye'></i>Generate Invoice</a>";
                    }
                }
                if (auth('admin')->check()) {
                    if (auth('admin')->check()) {
                        $html .= "<a class='btn  btn-sm btn-success me-2' href='/prosubscriptions/$p->id/edit'>Edit</a>";
                        $html .= "<a class='btn btn-sm btn-danger delete' href='/prosubscriptions/$p->id'>delete</a>";
                    }
                    return $html;
                }
            })
            ->addColumn('check out', function ($s) {
                if ($this->assign_visitor_roles) {
                    $visitor_roles = 1;
                    $checked_visitor = $s->staffVisitorRole()->where('staff_id', $this->staff->id)->first()->check_out ?? 0;

                    $super = auth('client')->check() ?? auth('admin')->check();

                    if (auth('staff')->check()) {
                        $visitor_roles = $s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->check_out ?? 0;
                    }
                    $checked_visitor = $checked_visitor  ? 'checked' : '';
                    $visitor_roles = $visitor_roles || $super ?  '' : 'disabled';

                    if ($this->assign_visitor_roles) {
                        return  "<input type='checkbox' value='$s->id' class='check-view$s->id' $checked_visitor $visitor_roles
                    name='check_out$s->id'><label>Check out</label>";
                    }
                }
            })
            ->addColumn('view', function ($s) {
                if ($this->assign_staff_roles || $this->assign_visitor_roles) {
                    $staff_roles = 1;
                    $visitor_roles = 1;
                    $checked_staff = $s->staffStaffRole()->where('staff_id', $this->staff->id)->first()->view ?? 0;
                    $checked_visitor = $s->staffVisitorRole()->where('staff_id', $this->staff->id)->first()->view ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();

                    if (auth('staff')->check()) {
                        $staff_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->view ?? 0;
                        $visitor_roles = $s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->view ?? 0;
                    }

                    $checked_staff = $checked_staff ? 'checked' : '';
                    $staff_roles = $staff_roles || $super ?  '' : 'disabled';
                    $checked_visitor = $checked_visitor  ? 'checked' : '';
                    $visitor_roles = $visitor_roles || $super ?  '' : 'disabled';

                    if ($this->assign_staff_roles) {
                        return "<input type='checkbox' value='$s->id' class='check-view$s->id' $checked_staff $staff_roles
                    name='view$s->id'><Label>View staff</Label>";
                    }

                    if ($this->assign_visitor_roles) {
                        return  "<input type='checkbox' value='$s->id' class='check-view$s->id' $checked_visitor $visitor_roles
                    name='view$s->id'><Label>View Visitors</Label>";
                    }
                }
            })
            ->addColumn('add', function ($s) {
                if ($this->assign_staff_roles || $this->assign_visitor_roles) {
                    $staff_roles = 1;
                    $visitor_roles = 1;
                    $checked_staff = $s->staffStaffRole()->where('staff_id', $this->staff->id)->first()->add ?? 0;
                    $checked_visitor = $s->staffVisitorRole()->where('staff_id', $this->staff->id)->first()->add ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $staff_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->add ?? 0;
                        $visitor_roles = $s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->add ?? 0;
                    }

                    $checked_staff = $checked_staff ? 'checked' : '';
                    $staff_roles = $staff_roles || $super ?  '' : 'disabled';
                    $checked_visitor = $checked_visitor  ? 'checked' : '';
                    $visitor_roles = $visitor_roles || $super ?  '' : 'disabled';

                    Log::info("$s->id $staff_roles");
                    if ($this->assign_staff_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_staff $staff_roles
                    name='add$s->id'><Label>Add staff</Label>";
                    }

                    if ($this->assign_visitor_roles) {
                        return  "<input type='checkbox' value='$s->id'  $checked_visitor $visitor_roles
                    name='add$s->id'><Label>Add Visitors</Label>";
                    }
                }
            })
            ->addColumn('edit', function ($s) {
                if ($this->assign_staff_roles || $this->assign_visitor_roles) {
                    $staff_roles = 1;
                    $visitor_roles = 1;
                    $checked_staff = $s->staffStaffRole()->where('staff_id', $this->staff->id)->first()->edit ?? 0;
                    $checked_visitor = $s->staffVisitorRole()->where('staff_id', $this->staff->id)->first()->edit ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $staff_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->edit ?? 0;
                        $visitor_roles = $s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->edit ?? 0;
                    }

                    $checked_staff = $checked_staff ? 'checked' : '';
                    $staff_roles = $staff_roles || $super ?  '' : 'disabled';
                    $checked_visitor = $checked_visitor  ? 'checked' : '';
                    $visitor_roles = $visitor_roles || $super ?  '' : 'disabled';
                    if ($this->assign_staff_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_staff $staff_roles
                    name='edit$s->id'><Label>Edit staff</Label>";
                    }

                    if ($this->assign_visitor_roles) {
                        return  "<input type='checkbox' value='$s->id' $checked_visitor $visitor_roles
                    name='edit$s->id'><Label>Edit Visitors</Label>";
                    }
                }
            })
            ->addColumn('delete', function ($s) {
                if ($this->assign_staff_roles || $this->assign_visitor_roles) {
                    $staff_roles = 1;
                    $visitor_roles = 1;
                    $checked_staff = $s->staffStaffRole()->where('staff_id', $this->staff->id)->first()->delete ?? 0;
                    $checked_visitor = $s->staffVisitorRole()->where('staff_id', $this->staff->id)->first()->delete ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $staff_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->delete ?? 0;
                        $visitor_roles = $s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->delete ?? 0;
                    }

                    $checked_staff = $checked_staff ? 'checked' : '';
                    $staff_roles = $staff_roles || $super ?  '' : 'disabled';
                    $checked_visitor = $checked_visitor  ? 'checked' : '';
                    $visitor_roles = $visitor_roles || $super ?  '' : 'disabled';

                    if ($this->assign_staff_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_staff $staff_roles
                    name='delete$s->id'><Label>Delete staff</Label>";
                    }

                    if ($this->assign_visitor_roles) {
                        return  "<input type='checkbox' value='$s->id'  $checked_visitor $visitor_roles
                    name='delete$s->id'><Label>Delete staff</abel>";
                    }
                }
            })
            ->addColumn('last 24 hours', function ($s) {
                if ($this->assign_transaction_roles) {
                    $trans_roles = 1;
                    $checked_trans = $s->staffTransactionRole()->where('staff_id', $this->staff->id)->first()->last_24_hours ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $trans_roles = $s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->last_24_hours ?? 0;
                    }

                    $checked_trans = $checked_trans ? 'checked' : '';
                    $trans_roles = $trans_roles || $super ? '' : 'disabled';
                    if ($this->assign_transaction_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_trans $trans_roles
                    name='last_24_hours$s->id'><Label>Last 24 hours</Label>";
                    }
                }
            })
            ->addColumn('last one month', function ($s) {
                if ($this->assign_transaction_roles) {
                    $trans_roles = 1;
                    $checked_trans = $s->staffTransactionRole()->where('staff_id', $this->staff->id)->first()->last_one_month ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $trans_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->last_one_month ?? 0;
                    }

                    $checked_trans = $checked_trans ? 'checked' : '';
                    $trans_roles = $trans_roles || $super ?  '' : 'disabled';
                    if ($this->assign_transaction_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_trans $trans_roles
                    name='last_one_month$s->id'><Label>Last one month</Label>";
                    }
                }
            })
            ->addColumn('all', function ($s) {
                if ($this->assign_transaction_roles) {
                    $trans_roles = 1;
                    $checked_trans = $s->staffTransactionRole()->where('staff_id', $this->staff->id)->first()->all ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $trans_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->all ?? 0;
                    }

                    $checked_trans = $checked_trans ? 'checked' : '';
                    $trans_roles = $trans_roles || $super ?  '' : 'disabled';
                    if ($this->assign_transaction_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_trans $trans_roles
                    name='all$s->id'><Label>All</Label>";
                    }
                }
            })
            ->addColumn('daily summary', function ($s) {
                if ($this->assign_transaction_roles) {
                    $trans_roles = 1;
                    $checked_trans = $s->staffTransactionRole()->where('staff_id', $this->staff->id)->first()->daily_summary ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $trans_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->daily_summary ?? 0;
                    }

                    $checked_trans = $checked_trans ? 'checked' : '';
                    $trans_roles = $trans_roles || $super ?  '' : 'disabled';
                    if ($this->assign_transaction_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_trans $trans_roles
                    name='daily_summary$s->id'><Label>Daily summary</Label>";
                    }
                }
            })
            ->addColumn('monthly summary', function ($s) {
                if ($this->assign_transaction_roles) {
                    $trans_roles = 1;
                    $checked_trans = $s->staffTransactionRole()->where('staff_id', $this->staff->id)->first()->monthly_summary ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $trans_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->monthly_summary ?? 0;
                    }

                    $checked_trans = $checked_trans ? 'checked' : '';
                    $trans_roles = $trans_roles || $super ?  '' : 'disabled';
                    if ($this->assign_transaction_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_trans $trans_roles
                    name='monthly_summary$s->id'><Label>Monthly summary</Label>";
                    }
                }
            })
            ->addColumn('all summary', function ($s) {
                if ($this->assign_transaction_roles) {
                    $trans_roles = 1;
                    $checked_trans = $s->staffTransactionRole()->where('staff_id', $this->staff->id)->first()->all_summary ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $trans_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->all_summary ?? 0;
                    }

                    $checked_trans = $checked_trans ? 'checked' : '';
                    $trans_roles = $trans_roles || $super ?  '' : 'disabled';
                    if ($this->assign_transaction_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_trans $trans_roles
                    name='all_summary$s->id'><Label>All summary</Label>";
                    }
                }
            })
            ->addColumn('transaction sms', function ($s) {
                if ($this->assign_transaction_roles) {
                    $trans_roles = 1;
                    $checked_trans = $s->staffTransactionRole()->where('staff_id', $this->staff->id)->first()->transaction_sms ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();
                    if (auth('staff')->check()) {
                        $trans_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->transaction_sms ?? 0;
                    }

                    $checked_trans = $checked_trans ? 'checked' : '';
                    $trans_roles = $trans_roles || $super ?  '' : 'disabled';
                    if ($this->assign_transaction_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_trans $trans_roles
                    name='transaction_sms$s->id'><Label>Transaction SMS</Label>";
                    }
                }
            })
            ->addColumn('re assign roles', function ($s) {
                if ($this->assign_transaction_roles || $this->assign_staff_roles || $this->assign_visitor_roles) {
                    $trans_roles = 1;
                    $staff_roles = 1;
                    $visitor_roles = 1;
                    $checked_trans = $s->staffTransactionRole()->where('staff_id', $this->staff->id)->first()->assign_roles ?? 0;
                    $checked_staff = $s->staffStaffRole()->where('staff_id', $this->staff->id)->first()->assign_roles ?? 0;
                    $checked_visitor = $s->staffVisitorRole()->where('staff_id', $this->staff->id)->first()->assign_roles ?? 0;
                    $super = auth('client')->check() ?? auth('admin')->check();

                    if (auth('staff')->check()) {
                        $trans_roles = $s->staffTransactionRole()->where('staff_id', auth('staff')->user()->id)->first()->assign_roles ?? 0;
                        $staff_roles = $s->staffStaffRole()->where('staff_id', auth('staff')->user()->id)->first()->assign_roles ?? 0;
                        $visitor_roles = $s->staffVisitorRole()->where('staff_id', auth('staff')->user()->id)->first()->assign_roles ?? 0;
                    }

                    $checked_trans = $checked_trans ? 'checked' : '';
                    $trans_roles = $trans_roles || $super ?  '' : 'disabled';

                    $checked_staff = $checked_staff ? 'checked' : '';
                    $staff_roles = $staff_roles || $super ?  '' : 'disabled';
                    $checked_visitor = $checked_visitor  ? 'checked' : '';
                    $visitor_roles = $visitor_roles || $super ?  '' : 'disabled';

                    if ($this->assign_staff_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_staff $staff_roles
                    name='assign_roles$s->id'><label>Re assign roles</label>";
                    }

                    if ($this->assign_visitor_roles) {
                        return  "<input type='checkbox' value='$s->id'  $checked_visitor $visitor_roles
                    name='assign_roles$s->id'><label>Re assign roles</label>";
                    }
                    if ($this->assign_transaction_roles) {
                        return "<input type='checkbox' value='$s->id'  $checked_trans $trans_roles
                    name='assign_roles$s->id'><label>Re Assign Roles</label>";
                    }
                }
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\ProSubscription $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProSubscription $model): QueryBuilder
    {


        if ($this->staff_id) {
            if ($this->assign_staff_roles) {
                return $model->where('sub_status', 'Active')
                    ->whereHas('staffStaffRole', function ($q) {
                        $q->where('staff_id', $this->staff_id)
                            ->where('assign_roles', 1);
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }

            if ($this->assign_visitor_roles) {
                return $model->where('sub_status', 'Active')
                    ->whereHas('staffVisitorRole', function ($q) {
                        $q->where('staff_id', $this->staff_id)
                            ->where('assign_roles', 1);
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }
            if ($this->assign_transaction_roles) {
                return $model->where('sub_status', 'Active')
                    ->whereHas('staffTransactionRole', function ($q) {
                        $q->where('staff_id', $this->staff_id)
                            ->where('assign_roles', 1);
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }
            if ($this->transactions_per_business) {
                return $model->where('sub_status', 'Active')
                    ->where('shortcode_status', 'Complete')
                    ->where('shortcode', '!=', null)
                    ->whereHas('staffTransactionRole', function ($q) {
                        $q->where('staff_id', $this->staff_id)
                            ->where('last_24_hours', 1)
                            ->orWhere('last_one_month', 1)
                            ->orWhere('all', 1)
                            ->where('daily_summary', 1)
                            ->orWhere('monthly_summary', 1)
                            ->orWhere('all_summary', 1);
                    })->orderBy('id', 'desc')->newQuery();
            }

            if ($this->visitors_per_business) {
                return $model->where('sub_status', 'Active')
                    ->whereHas('staffVisitorRole', function ($q) {
                        $q->where('staff_id', $this->staff_id)
                            ->where('view', 1);
                    })->orderBy('id', 'desc')->newQuery();
            }
        }
        if ($this->cl_id) {
            if ($this->any_status) {
                return $model->where('cl_id', $this->cl_id)
                    ->orderBy('id', 'desc')->newQuery();
            }
            return $model->where('cl_id', $this->cl_id)
                ->where('sub_status', 'Active')
                ->orderBy('id', 'desc')->newQuery();
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
        $responsive = true;
        $scrollx = false;

        if (
            $this->assign_staff_roles
            || $this->assign_visitor_roles ||
            $this->assign_transaction_roles
        ) {
            $responsive = false;
            $scrollx = true;
        }

        return $this->builder()
            ->setTableId('prosubscriptions-table')
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
                'responsive' => $responsive,
                'scrollX' => $scrollx,

            ]);
    }

    /**
     * Get the dataTable columns definition.
     *
     * @return array
     */
    public function getColumns(): array
    {
        if ($this->assign_staff_roles) {
            return [
                Column::make('id'),
                Column::make(['data' => 'business_name', 'name' => 'business_name', 'title' => 'Business Name']),
                Column::computed('view'),
                Column::computed('add'),
                Column::computed('edit'),
                Column::computed('delete'),
                Column::computed('re assign roles'),
            ];
        }

        if ($this->assign_visitor_roles) {
            return [
                Column::make('id'),
                Column::make(['data' => 'business_name', 'name' => 'business_name', 'title' => 'Business Name']),
                Column::computed('view'),
                Column::computed('add'),
                Column::computed('edit'),
                Column::computed('check out'),
                Column::computed('delete'),
                Column::computed('re assign roles'),
            ];
        }

        if ($this->assign_transaction_roles) {
            return [
                Column::make('id'),
                Column::make(['data' => 'business_name', 'name' => 'business_name', 'title' => 'Business Name']),
                Column::computed('last 24 hours'),
                Column::computed('last one month'),
                Column::computed('all'),
                Column::computed('daily summary'),
                Column::computed('monthly summary'),
                Column::computed('all summary'),
                Column::computed('transaction sms'),
                Column::computed('re assign roles'),
            ];
        }

        if ($this->visitors_per_business || $this->transactions_per_business) {
            return [
                Column::make('id'),
                Column::make(['data' => 'business_name', 'name' => 'business_name', 'title' => 'Business Name']),
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
            ];
        }
        return [
            Column::make('id'),
            Column::make(['data' => 'cl_id', 'name' => 'cl_id', 'title' => 'name']),
            Column::make(['data' => 'email', 'name' => 'email', 'title' => 'email']),
            Column::make(['data' => 'business_name', 'name' => 'business_name', 'title' => 'Business Name']),
            Column::make(['data' => 'sub_status', 'name' => 'sub_status',  'title' => 'Status']),
            Column::make(['data' => 'shortcode', 'name' => 'shortcode',  'title' => 'Shortcode']),
            Column::make(['data' => 'phone_number', 'name' => 'phone_number',  'title' => 'Phone Number']),
            Column::make(['data' => 'shortcode_status', 'name' => 'shortcode_status',  'title' => 'Mpesa Integration']),
            Column::make(['data' => 'developer_integrate', 'name' => 'developer_integrate',  'title' => 'Developer Integration']),
            Column::make(['data' => 'plan_recurring_date', 'name' => 'plan_recurring_date', 'title' => 'Recurring Date']),
            Column::make(['data' => 'summary_time', 'name' => 'summary_time', 'title' => 'Summary Time']),
            Column::computed('services'),
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
        return 'ProSubscriptions_' . date('YmdHis');
    }
}

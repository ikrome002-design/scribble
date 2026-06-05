<?php

namespace App\DataTables;

use App\Models\Visitor;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class VisitorsDataTable extends DataTable
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
            ->rawColumns(['image', 'action', 'check_out_time', 'check_in_time'])
            ->editColumn('image', function ($i) {
                if ($i->image) {
                    return "<img style='width:50px;height:auto' src='/private/visitor/$i->image'/>";
                }
                return;
            })
            ->editColumn('check_out_time', function ($v) {
                if ($v->check_out_time) {

                    return   $v->check_out_time;
                } else {
                    if (
                        auth('client')->check() || auth('admin')->check() ||
                        auth('staff')->user()->staffVisitorsRole()->where('pro_subscription_id', $v->pro_subscription_id)->first()->check_out ?? 0
                    ) {
                        return   "<a href='/visitors/checkout/$v->id'
                                                    class='checkout-visitor btn btn-success btn-xs'>Check out the
                                                    visitor  </a>";
                    } else {
                        return   '<a  class="checkout-visitor btn btn-success btn-xs disabled">Check out the
                                                    visitor</a>';
                    }
                }
            })
            ->editColumn('checked_in_by', function ($v) {
                if ($v->checked_in_by) {

                    return   $v->checkedInBy()->first()->fname . ' ' . $v->checkedInBy()->first()->lname . '(' . $v->checkedInBy()->first()->email . ')';
                } else {

                    return   'Business Owner';
                }
            })
            ->editColumn('checked_out_by', function ($v) {
                if ($v->checked_out_by) {

                    return   $v->checkedOutBy()->first()->fname . ' ' . $v->checkedOutBy()->first()->lname . '(' . $v->checkedOutBy()->first()->email . ')';
                } else {

                    return   'Business Owner';
                }
            })
            ->editColumn('edited_by', function ($v) {
                if ($v->edited_by) {

                    return   $v->editedBy()->first()->fname . ' ' . $v->editedBy()->first()->lname . '(' . $v->editedBy()->first()->email . ')';
                } else {

                    return   '';
                }
            })
            ->editColumn('pro_subscription_id', function ($v) {
                return $v->proSubscription->business_name;
            })
            ->editColumn('visitor_business_id', function ($v) {
                return $v->visitorBusiness->business_name;
            })
            ->addColumn('action', function ($p) {
                $html = '';
                if (
                    auth('client')->check() || auth('admin')->check() ||
                    auth('staff')->user()->staffVisitorsRole()->where('pro_subscription_id', $p->pro_subscription_id)->first()->edit ?? 0
                ) {
                    $html .= "<a class='btn  btn-sm btn-success me-2' href='visitors/$p->id/edit'>Edit";
                } else {
                    $html .= "<a class='btn  btn-sm btn-success me-2 disabled' >Edit</a>";
                }

                if (
                    auth('client')->check() || auth('admin')->check() ||
                    auth('staff')->user()->staffVisitorsRole()->where('pro_subscription_id', $p->pro_subscription_id)->first()->delete ?? 0
                ) {
                    $html .= "<a class='btn btn-sm btn-danger delete' href='/visitors/$p->id'>delete</a>";
                } else {
                    $html .= "<a class='btn btn-sm btn-danger delete disabled' >delete</a>";
                }
                return $html;
            })
            ->setRowId('id');
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Visitor $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Visitor $model): QueryBuilder
    {
        $check_in_start_date = $this->check_in_start_date;
        $check_in_end_date = $this->check_in_end_date;
        $check_out_start_date = $this->check_out_start_date;
        $check_out_end_date = $this->check_out_end_date;
        $now = Carbon::now()->format('Y-m-d H:i:s');
        $start = Carbon::now()->subYears('10')->format('Y-m-d H:i:s');

        if ($check_in_start_date) {
            $check_in_start_date = Carbon::parse($check_in_start_date)->format('Y-m-d H:i:s');
        } else {
            $check_in_start_date = $start;
        }

        if ($check_in_end_date) {
            $check_in_end_date = Carbon::parse($check_in_end_date)->format('Y-m-d H:i:s');
        } else {
            $check_in_end_date = $now;
        }


        if ($check_out_start_date) {
            $check_out_start_date = Carbon::parse($check_out_start_date)->format('Y-m-d H:i:s');
        } else {
            $check_out_start_date = $start;
        }


        if ($check_out_end_date) {
            $check_out_end_date = Carbon::parse($check_out_end_date)->format('Y-m-d H:i:s');
        } else {
            $check_out_end_date = $now;
        }
        // given sub and client id
        if ($this->sub_id && $this->cl_id) {
            if ($this->check_out_start_date || $this->check_out_end_date) {
                return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                    ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                    ->where('check_out_time', '!=', null)
                    ->whereHas('visitorBusiness', function ($q) {
                        $q->whereHas('proSubscription', function ($q) {
                            $q->where('id', $this->sub_id)
                                ->where('sub_status', "Active");
                        });
                    })->orderBy('id', 'desc')->newQuery();
            }

            return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                ->whereHas(
                    'visitorBusiness',
                    function ($q) {
                        $q->whereHas('proSubscription', function ($q) {
                            $q->where('id', $this->sub_id)
                                ->where('sub_status', "Active");
                        });
                    }
                )->orderBy('id', 'desc')->newQuery();
        }

        // given business id and client id
        if ($this->business_id && $this->cl_id) {
            if ($this->check_out_start_date || $this->check_out_end_date) {
                return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                    ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                    ->where('check_out_time', '!=', null)
                    ->whereHas('visitorBusiness', function ($q) {
                        $q->where('id', $this->business_id)
                            ->whereHas('proSubscription', function ($q) {
                                $q->where('sub_status', "Active")
                                    ->where('sub_status', "Active");
                            });
                    })->orderBy('id', 'desc')->newQuery();
            }

            return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                ->whereHas(
                    'visitorBusiness',
                    function ($q) {
                        $q->where('id', $this->business_id)
                            ->whereHas('proSubscription', function ($q) {
                                $q->where('sub_status', "Active")
                                    ->where('cl_id', $this->cl_id)
                                    ->where('sub_status', "Active");
                            });
                    }
                )->orderBy('id', 'desc')->newQuery();
        }

        // given word staff  and client id
        if ($this->work_staff_id && $this->cl_id) {
            if ($this->check_out_start_date || $this->check_out_end_date) {
                return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                    ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                    ->where('check_out_time', '!=', null)
                    ->where('checked_in_by', $this->work_staff_id)
                    ->orWhere('checked_out_by', $this->work_staff_id)
                    ->orWhere('edited_by', $this->work_staff_id)
                    ->whereHas('visitorBusiness', function ($q) {
                        $q->whereHas('proSubscription', function ($q) {
                            $q->where('id', $this->sub_id)
                                ->where('sub_status', "Active");
                        });
                    })->orderBy('id', 'desc')->newQuery();
            }

            return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                ->where('checked_in_by', $this->work_staff_id)
                ->orWhere('checked_out_by', $this->work_staff_id)
                ->orWhere('edited_by', $this->work_staff_id)
                ->whereHas('visitorBusiness', function ($q) {
                    $q->whereHas('proSubscription', function ($q) {
                        $q->where('cl_id', $this->cl_id)
                            ->where('sub_status', "Active");
                    });
                })->orderBy('id', 'desc')->newQuery();
        }



        //given client id
        if ($this->cl_id) {
            if ($this->check_out_start_date || $this->check_out_end_date) {
                return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                    ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                    ->where('check_out_time', '!=', null)
                    ->whereHas('visitorBusiness', function ($q) {
                        $q->whereHas('proSubscription', function ($q) {
                            $q->where('cl_id', $this->cl_id)
                                ->where('sub_status', "Active");
                        });
                    })->orderBy('id', 'desc')->newQuery();
            }
            return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                ->whereHas('visitorBusiness', function ($q) {
                    $q->whereHas('proSubscription', function ($q) {
                        $q->where('cl_id', $this->cl_id)
                            ->where('sub_status', "Active");
                    });
                })->orderBy('id', 'desc')->newQuery();
        }

        // given sub and staff id
        if ($this->sub_id && $this->staff_id) {
            if ($this->check_out_start_date || $this->check_out_end_date) {
                return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                    ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                    ->where('check_out_time', '!=', null)
                    ->whereHas('visitorBusiness', function ($q) {
                        $q->whereHas('proSubscription', function ($q) {
                            $q->where('id', $this->sub_id)
                                ->where('sub_status', "Active")
                                ->whereHas('staffVisitorRole', function ($q) {
                                    $q->where('view', 1)
                                        ->where('staff_id', $this->staff_id);
                                });
                        });
                    })->orderBy('id', 'desc')->newQuery();
            }
            return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                ->whereHas('visitorBusiness', function ($q) {
                    $q->whereHas('proSubscription', function ($q) {
                        $q->where('id', $this->sub_id)
                            ->where('sub_status', "Active")
                            ->whereHas('staffVisitorRole', function ($q) {
                                $q->where('view', 1)
                                    ->where('staff_id', $this->staff_id);
                            });
                    });
                })->orderBy('id', 'desc')->newQuery();
        }

        // given business id and staff id
        if ($this->business_id && $this->staff_id) {
            if ($this->check_out_start_date || $this->check_out_end_date) {
                return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                    ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                    ->where('check_out_time', '!=', null)
                    ->whereHas('visitorBusiness', function ($q) {
                        $q->where('id', $this->business_id)
                            ->whereHas('proSubscription', function ($q) {
                                $q->where('sub_status', "Active")
                                    ->whereHas('staffVisitorRole', function ($q) {
                                        $q->where('view', 1)
                                            ->where('staff_id', $this->staff_id);
                                    });
                            });
                    })->orderBy('id', 'desc')->newQuery();
            }
            return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                ->whereHas('visitorBusiness', function ($q) {
                    $q->where('id', $this->business_id)
                        ->whereHas('proSubscription', function ($q) {
                            $q->where('sub_status', "Active")
                                ->whereHas('staffVisitorRole', function ($q) {
                                    $q->where('view', 1)
                                        ->where('staff_id', $this->staff_id);
                                });
                        });
                })->orderBy('id', 'desc')->newQuery();
        }

        //given staff id
        if ($this->staff_id) {

            if ($this->work_staff_id) {
                if ($this->check_out_start_date || $this->check_out_end_date) {
                    return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                        ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                        ->where('check_out_time', '!=', null)
                        ->where('checked_in_by', $this->work_staff_id)
                        ->orWhere('checked_out_by', $this->work_staff_id)
                        ->orWhere('edited_by', $this->work_staff_id)
                        ->whereHas('visitorBusiness', function ($q) {
                            $q->whereHas('proSubscription', function ($q) {
                                $q->where('sub_status', "Active")
                                    ->whereHas('staffVisitorRole', function ($q) {
                                        $q->where('view', 1)
                                            ->where('staff_id', $this->staff_id);
                                    });
                            });
                        })->orderBy('id', 'desc')->newQuery();
                }
                return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                    ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                    ->where('check_out_time', '!=', null)
                    ->where('checked_in_by', $this->work_staff_id)
                    ->orWhere('checked_out_by', $this->work_staff_id)
                    ->orWhere('edited_by', $this->work_staff_id)
                    ->whereHas('visitorBusiness', function ($q) {
                        $q->whereHas('proSubscription', function ($q) {
                            $q->where('sub_status', "Active")
                                ->whereHas('staffVisitorRole', function ($q) {
                                    $q->where('view', 1)
                                        ->where('staff_id', $this->staff_id);
                                });
                        });
                    })->orderBy('id', 'desc')->newQuery();
            }
            if ($this->check_out_start_date || $this->check_out_end_date) {
                return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                    ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                    ->where('check_out_time', '!=', null)
                    ->whereHas('visitorBusiness', function ($q) {
                        $q->whereHas('proSubscription', function ($q) {
                            $q->where('sub_status', "Active")
                                ->whereHas('staffVisitorRole', function ($q) {
                                    $q->where('view', 1)
                                        ->where('staff_id', $this->staff_id);
                                });
                        });
                    })->orderBy('id', 'desc')->newQuery();
            }

            return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                ->whereHas('visitorBusiness', function ($q) {
                    $q->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', "Active")
                            ->whereHas('staffVisitorRole', function ($q) {
                                $q->where('view', 1)
                                    ->where('staff_id', $this->staff_id);
                            });
                    });
                })->orderBy('id', 'desc')->newQuery();
        }

        //for admin only 
        if (auth('admin')->check()) {
            if ($this->check_out_start_date || $this->check_out_end_date) {
                return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                    ->whereBetween('check_out_time',  [$check_out_start_date, $check_out_end_date])
                    ->orWhere('check_out_time', null)
                    ->orderBy('id', 'desc')->newQuery();
            }
            return $model->whereBetween('check_in_time', [$check_in_start_date,  $check_in_end_date])
                ->orderBy('id', 'desc')->newQuery();
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
            ->setTableId('visitor-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => "function(d) {
                     d.check_in_start_date =$('#check_in_start_date').val()
                     d.check_in_end_date =$('#check_in_end_date').val()
                     d.check_out_start_date =$('#check_out_start_date').val()
                     d.check_out_end_date =$('#check_out_end_date').val()
                    }",
            ])
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

    public function getColumns(): array
    {


        return [
            Column::make('id'),
            Column::make(['data' => 'image', 'name' => 'image', 'title' => 'Image']),
            Column::make(['data' => 'fname', 'name' => 'lname', 'title' => 'First Name']),
            Column::make(['data' => 'lname', 'name' => 'lname', 'title' => 'Last Name']),
            Column::make(['data' => 'id_number', 'name' => 'id_number',  'title' => 'ID Number']),
            Column::make(['data' => 'phone_number', 'name' => 'phone_number',  'title' => 'Phone']),
            Column::make(['data' => 'pro_subscription_id', 'name' => 'pro_subscription_id',  'title' => 'Subscription Business']),
            Column::make(['data' => 'visitor_business_id', 'name' => 'visitor_business_id',  'title' => 'Business/Premise/Office Visited']),
            Column::make(['data' => 'check_in_time', 'name' => 'check_in_time', 'title' => 'Check In Time']),
            Column::make(['data' => 'check_out_time', 'name' => 'check_out_time', 'title' => 'Check Out Time']),
            Column::make(['data' => 'notes', 'name' => 'notes', 'title' => 'notes'])
                ->addClass('white-space-normal'),
            Column::make(['data' => 'checked_in_by', 'name' => 'checked_in_by',  'title' => 'Checked In By']),
            Column::make(['data' => 'checked_out_by', 'name' => 'checked_out_by',  'title' => 'Checked out By']),
            Column::make(['data' => 'edited_by', 'name' => 'edited_by',  'title' => 'Last Staff to Edit ']),
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
        return 'Visitors_' . date('YmdHis');
    }
}

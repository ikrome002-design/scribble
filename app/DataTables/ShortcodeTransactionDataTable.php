<?php

namespace App\DataTables;

use App\Models\ShortCodeTransaction;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Transactions;

class ShortCodeTransactionDataTable extends DataTable
{
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))->setRowId('id');
    }

    public function query(ShortCodeTransaction $model): QueryBuilder
    {

        $start_date = $this->start_date;
        $end_date = $this->end_date;



        // given shortcode and client id
        if ($this->shortcode && $this->cl_id) {
            if ($start_date) {
                return $model->where('transaction_date', '>=', $start_date)
                    ->where('shortcode', $this->shortcode)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->where('cl_id', $this->cl_id);
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }

            if ($end_date) {
                return $model->where('transaction_date', '<=', $end_date)
                    ->where('shortcode', $this->shortcode)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->where('cl_id', $this->cl_id);
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }
            if ($end_date && $start_date) {
                return $model->whereBetween('transaction_date', [$start_date, $end_date])
                    ->where('shortcode', $this->shortcode)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->where('cl_id', $this->cl_id);
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }

            return $model->where('shortcode', $this->shortcode)
                ->whereHas('proSubscription', function ($q) {
                    $q->where('sub_status', 'Active')
                        ->where('cl_id', $this->cl_id);
                })->orderBy('id', 'desc')->newQuery();
        }

        // given work staff id
        if ($this->work_staff_id && $this->cl_id) {

            if ($end_date && $start_date) {
                return $model->whereBetween('transaction_date', [$start_date, $end_date])
                    ->where('bill_ref_number', $this->work_staff_id)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->where('cl_id', $this->cl_id);
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }

            if ($start_date) {
                return $model->where('transaction_date', '>=', $start_date)
                    ->where('bill_ref_number', $this->work_staff_id)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->where('cl_id', $this->cl_id);
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }

            if ($end_date) {
                return $model->where('transaction_date', '<=', $end_date)
                    ->where('bill_ref_number', $this->work_staff_id)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->where('cl_id', $this->cl_id);
                    })->orderBy('id', 'desc')->newQuery();
            }

            return $model->where('bill_ref_number', $this->work_staff_id)
                ->whereHas('proSubscription', function ($q) {
                    $q->where('sub_status', 'Active')
                        ->where('cl_id', $this->cl_id);
                })
                ->orderBy('id', 'desc')->newQuery();
        }
        //given client id
        if ($this->cl_id) {

            if ($end_date && $start_date) {
                return $model->whereBetween('transaction_date', [$start_date, $end_date])
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->where('cl_id', $this->cl_id);
                    })->orderBy('id', 'desc')->newQuery();
            }
            if ($start_date) {
                return $model->where('transaction_date', '>=', $start_date)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->where('cl_id', $this->cl_id);
                    })->orderBy('id', 'desc')->newQuery();
            }

            if ($end_date) {
                return $model->where('transaction_date', '<=', $end_date)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->where('cl_id', $this->cl_id);
                    })->orderBy('id', 'desc')->newQuery();
            }


            return $model->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->where('cl_id', $this->cl_id);
            })->orderBy('id', 'desc')->newQuery();
        }


        //given staff  and work staff id
        if ($this->staff_id && $this->work_staff_id) {
            if ($end_date && $start_date) {
                return $model->whereBetween('transaction_date', [$start_date, $end_date])
                    ->where('bill_ref_number', $this->work_staff_id)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->whereHas('staffTransactionRole', function ($q) {
                                $q->where('staff_id', $this->staff_id)
                                    ->where('last_24_hours', 1)
                                    ->orWhere('last_one_month', 1)
                                    ->orWhere('all', 1);
                            });
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }
            if ($start_date) {

                return $model->where('transaction_date', '>=', $start_date)
                    ->where('bill_ref_number', $this->work_staff_id)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->whereHas('staffTransactionRole', function ($q) {
                                $q->where('staff_id', $this->staff_id)
                                    ->where('last_24_hours', 1)
                                    ->orWhere('last_one_month', 1)
                                    ->orWhere('all', 1);
                            });
                    })->orderBy('id', 'desc')->newQuery();
            }

            if ($end_date) {
                return $model->where('transaction_date', '<=', $end_date)
                    ->where('bill_ref_number', $this->work_staff_id)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->whereHas('staffTransactionRole', function ($q) {
                                $q->where('staff_id', $this->staff_id)
                                    ->where('last_24_hours', 1)
                                    ->orWhere('last_one_month', 1)
                                    ->orWhere('all', 1);
                            });
                    })->orderBy('id', 'desc')->newQuery();
            }


            return $model->where('bill_ref_number', $this->work_staff_id)
                ->whereHas('proSubscription', function ($q) {
                    $q->where('sub_status', 'Active')
                        ->whereHas('staffTransactionRole', function ($q) {
                            $q->where('staff_id', $this->staff_id)
                                ->where('last_24_hours', 1)
                                ->orWhere('last_one_month', 1)
                                ->orWhere('all', 1);
                        });
                })->orderBy('id', 'desc')->newQuery();
        }

        //given staff  and shortcode
        if ($this->staff_id && $this->shortcode) {
            if ($end_date && $start_date) {

                return $model->whereBetween('transaction_date', [$start_date, $end_date])
                    ->where('shortcode', $this->shortcode)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->whereHas('staffTransactionRole', function ($q) {
                                $q->where('staff_id', $this->staff_id)
                                    ->where('last_24_hours', 1)
                                    ->orWhere('last_one_month', 1)
                                    ->orWhere('all', 1);
                            });
                    })
                    ->orderBy('id', 'desc')->newQuery();
            }
            if ($start_date) {

                return $model->where('transaction_date', '>=', $start_date)
                    ->where('shortcode', $this->shortcode)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->whereHas('staffTransactionRole', function ($q) {
                                $q->where('staff_id', $this->staff_id)
                                    ->where('last_24_hours', 1)
                                    ->orWhere('last_one_month', 1)
                                    ->orWhere('all', 1);
                            });
                    })->orderBy('id', 'desc')->newQuery();
            }

            if ($end_date) {
                return $model->where('transaction_date', '<=', $end_date)
                    ->where('shortcode', $this->shortcode)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->whereHas('staffTransactionRole', function ($q) {
                                $q->where('staff_id', $this->staff_id)
                                    ->where('last_24_hours', 1)
                                    ->orWhere('last_one_month', 1)
                                    ->orWhere('all', 1);
                            });
                    })->orderBy('id', 'desc')->newQuery();
            }


            return $model->where('shortcode', $this->shortcode)
                ->whereHas('proSubscription', function ($q) {
                    $q->where('sub_status', 'Active')
                        ->whereHas('staffTransactionRole', function ($q) {
                            $q->where('staff_id', $this->staff_id)
                                ->where('last_24_hours', 1)
                                ->orWhere('last_one_month', 1)
                                ->orWhere('all', 1);
                        });
                })->orderBy('id', 'desc')->newQuery();
        }
        //staff id only
        if ($this->staff_id) {
            if ($end_date && $start_date) {
                return $model->whereBetween('transaction_date', [$start_date, $end_date])
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->whereHas('staffTransactionRole', function ($q) {
                                $q->where('staff_id', $this->staff_id)
                                    ->where('all', 1);
                            });
                    })->orderBy('id', 'desc')->newQuery();
            }
            if ($start_date) {
                return $model->where('transaction_date', '>=', $start_date)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->whereHas('staffTransactionRole', function ($q) {
                                $q->where('staff_id', $this->staff_id)
                                    ->where('all', 1);
                            });
                    })->orderBy('id', 'desc')->newQuery();
            }

            if ($end_date) {
                return $model->where('transaction_date', '<=', $end_date)
                    ->whereHas('proSubscription', function ($q) {
                        $q->where('sub_status', 'Active')
                            ->whereHas('staffTransactionRole', function ($q) {
                                $q->where('staff_id', $this->staff_id)
                                    ->where('all', 1);
                            });
                    })->orderBy('id', 'desc')->newQuery();
            }


            return $model->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffTransactionRole', function ($q) {
                        $q->where('staff_id', $this->staff_id)
                            ->where('all', 1);
                    });
            })->orderBy('id', 'desc')->newQuery();
        }

        //for admin only 
        if (auth('admin')->check()) {
            if ($start_date) {
                return $model->where('transaction_date', '>=', $start_date)
                    ->orderBy('id', 'desc')->newQuery();
            }

            if ($end_date) {
                return $model->where('transaction_date', '<=', $end_date)
                    ->orderBy('id', 'desc')->newQuery();
            }
            if ($end_date && $start_date) {
                return $model->where('transaction_date', '>=', $start_date)
                    ->where('transaction_date', '<=', $end_date)
                    ->orderBy('id', 'desc')->newQuery();
            }

            return $model->orderBy('id', 'desc')->newQuery();
        }
    }

    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('ShortCodeTransaction-table')
            ->columns($this->getColumns())
            ->ajax([
                'data' => "function(d) {
                     d.start_date =$('#start_date').val()
                     d.end_date =$('#end_date').val()
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
            Column::make(['data' => 'trans_id', 'name' => 'trans_id', 'title' => 'Transaction Id']),
            Column::make(['data' => 'shortcode', 'name' => 'shortcode', 'title' => 'Shortcode']),
            Column::make(['data' => 'bill_ref_number', 'name' => 'bill_ref_number', 'title' => 'Account Number']),
            Column::make(['data' => 'amount', 'name' => 'amount',  'title' => 'Amount']),
            Column::make(['data' => 'transaction_date', 'name' => 'transaction_date',  'title' => 'Transaction Date']),
            Column::make(['data' => 'phone_number', 'name' => 'phone_number', 'title' => 'Phone Number']),
            Column::make(['data' => 'name', 'name' => 'name', 'title' => 'Name']),
            Column::make(['data' => 'transaction_type', 'name' => 'transaction_type', 'title' => 'Transaction Type']),

        ];
    }

    protected function filename(): string
    {
        return 'transactions_' . date('YmdHis');
    }
}

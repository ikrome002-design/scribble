<?php

namespace App\DataTables;

use App\Models\Staff;
use App\Models\TeamMember;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TeamMembersDataTable extends DataTable
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
            ->rawColumns(['image', 'action', 'opted_out'])
            ->editColumn('image', function ($i) {
                if ($i->image) {
                    return "<img style='width:50px;height:auto' src='/private/staff/$i->image'/>";
                }
                return '';
            })
            ->editColumn('team_opted_out', function ($s) {
                if ($s->team_opted_out == 'No') {
                    return 'No';
                } else {
                    return  'Yes, ' . $s->team_opted_out_date;
                }
            })
            ->editColumn('cl_id', function ($s) {
                return "$s->client->fname $s->client->lname ($s->client->email)";
            })
            ->addColumn('action', function ($s) {
                $html = '';
                if (auth('admin')->check() || auth('client')->check()) {
                    $html .= "<a class='btn btn-complete btn-xs me-2' href='/team/members/$s->id/edit'>
                        <i class='fa fa-eye'></i>Edit</a>";
                    $html .= "<a class='btn btn-danger btn-xs me-2 delete' href='/team/members/$s->id'>
                        <i class='fa fa-trash'></i>Delete</a>";
                } else if (auth('team')->user()->team_role == 'Manager' && $s->team_role != 'Manager') {
                    $html .= "<a class='btn btn-complete btn-xs me-2' href='/team/members/$s->id/edit'>
                        <i class='fa fa-eye'></i>Edit</a>";
                    $html .= "<a class='btn btn-danger btn-xs me-2 delete' href='/team/members/$s->id'>
                        <i class='fa fa-trash'></i>Delete</a>";
                }
                return $html;
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
        if ($this->team_id) {
            return  $model->whereHas('client', function ($q) {
                $q->where('cl_id', auth('team')->user()->cl_id);
            })->orderBy('id', 'desc')->newQuery();
        }

        if ($this->cl_id) {
            return $model->whereHas('client', function ($q) {
                $q->where('cl_id', $this->cl_id);
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
        if (auth('admin')->check()) {
            return [
                Column::make('id'),
                Column::make(['data' => 'image', 'name' => 'image', 'title' => 'Image']),
                Column::make(['data' => 'unique_id', 'name' => 'unique_id',  'title' => 'Unique Id']),
                Column::make(['data' => 'fname', 'name' => 'lname', 'title' => 'First Name']),
                Column::make(['data' => 'lname', 'name' => 'lname', 'title' => 'Last Name']),
                Column::make(['data' => 'email', 'name' => 'email',  'title' => 'email']),
                Column::make(['data' => 'phone_number', 'name' => 'phone_number',  'title' => 'Phone']),
                Column::make(['data' => 'is_team', 'name' => 'is_team',  'title' => 'Is Team']),
                Column::make(['data' => 'team_role', 'name' => 'team_role',  'title' => 'Team Role']),
                Column::make(['data' => 'team_opted_out', 'name' => 'team_opted_out',  'title' => 'Team Opted Out']),
                Column::make(['data' => 'status', 'name' => 'status',  'title' => 'Status']),
                Column::make(['data' => 'cl_id', 'name' => 'cl_id',  'title' => 'Client']),
                Column::computed('action')
                    ->exportable(false)
                    ->printable(false)
            ];
        }

        return [
            Column::make('id'),
            Column::make(['data' => 'image', 'name' => 'image', 'title' => 'Image']),
            Column::make(['data' => 'unique_id', 'name' => 'unique_id',  'title' => 'Unique Id']),
            Column::make(['data' => 'fname', 'name' => 'lname', 'title' => 'First Name']),
            Column::make(['data' => 'lname', 'name' => 'lname', 'title' => 'Last Name']),
            Column::make(['data' => 'email', 'name' => 'email',  'title' => 'email']),
            Column::make(['data' => 'phone_number', 'name' => 'phone_number',  'title' => 'Phone']),
            Column::make(['data' => 'is_team', 'name' => 'is_team',  'title' => 'Is Team']),
            Column::make(['data' => 'team_role', 'name' => 'team_role',  'title' => 'Team Role']),
            Column::make(['data' => 'team_opted_out', 'name' => 'team_opted_out',  'title' => 'Team Opted Out']),
            Column::make(['data' => 'status', 'name' => 'status',  'title' => 'Status']),
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

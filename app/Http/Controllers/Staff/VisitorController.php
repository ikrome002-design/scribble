<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use Illuminate\Http\Request;
use App\DataTables\VisitorsDataTable;
use App\Models\ProSubscription;
use Illuminate\Support\Facades\Storage;
use App\DataTables\ProSubscriptionsDataTable;
use App\Client;
use App\Helpers\SmsHelper;
use App\Models\Staff;
use App\Models\VisitorBusiness;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VisitorsDataTable $dataTable, Request $request)
    {

        return $dataTable->with(
            [
                'staff_id' => auth('staff')->user()->id,
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
            ]
        )->render('staff.visitor.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


        $sub = ProSubscription::where('id', $request->sub_id)
            ->where('sub_status', 'Active')
            ->whereHas('staffVisitorRole', function ($q) {
                $q->where('add', 1)
                    ->where('staff_id', auth('staff')->user()->id);
            })->first();
        $subs = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffVisitorRole', function ($q) {
                $q->where('add', 1)
                    ->where('staff_id', auth('staff')->user()->id);
            })->get();

        if ($subs->count() == 0) {
            return back()->withErrors('You are not allowed to add staff');
        }
        return view('staff.visitor.create', compact('subs', 'sub'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'phone_number' => 'required',
            'last_name' => 'required',
            'business_name' => 'required',
            'check_in_time' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000'
        ]);


        $bus = VisitorBusiness::where('id', $request->business_name)
            ->whereHas('proSubscription', function ($q) {
                $q->where('sub_status', 'Active')
                    ->whereHas('staffVisitorRole', function ($q) {
                        $q->where('add', 1)
                            ->where('staff_id', auth('staff')->user()->id);
                    });
            })->first();

        if (!$bus) {
            return back()->withErrors('The subscription for business name is not active or you are not allowed to edit');
        }
        $filename = null;

        if ($request->image) {
            $filename = basename(Storage::disk('private')->put('visitor', $request->image));
        }
        $client = Client::find(auth('staff')->user()->cl_id);
        $v = new Visitor();
        $v->fname = $request->first_name;
        $v->lname = $request->last_name;
        $v->pro_subscription_id = $bus->pro_subscription_id;
        $v->visitor_business_id = $bus->id;
        $v->image = $filename;
        $v->id_number = $request->id_number;
        $v->phone_number = $request->phone_number;
        $v->check_in_time = $request->check_in_time;
        $v->checked_in_by = auth('staff')->user()->id;
        $v->notes = $request->notes;
        $v->save();
        $main = $bus->proSubscription->business_name;
        $message = "Welcome to $main ($bus->business_name), $request->first_name! We are thrilled to have you visit us today. Please let us know if there is anything, we can do to make your stay more enjoyable.";
        $sms = new SmsHelper();
        $sms->clientSendQuickSms($bus->proSubscription->sender_id, $client, $request->phone_number, $message, 0, $bus->proSubscription->id);
        return redirect('/visitors')->with('message', 'Visitor added successfully');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function show(Visitor $visitor)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function edit(Visitor $visitor)
    {
        $subs = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffVisitorRole', function ($q) {
                $q->where('edit', 1);
            })->get();
        $visitor = $visitor->whereHas('proSubscription', function ($q) {
            $q->whereHas('staffVisitorRole', function ($q) {
                $q->where('edit', 1)
                    ->where('staff_id', auth('staff')->user()->id);
            });
        })->first();
        if (!$visitor) {
            return back()->withError('You are not allowed to edit the visitor');
        }
        $buses = VisitorBusiness::where('pro_subscription_id', $visitor->pro_subscription_id)->get();
        return view('staff.visitor.edit', compact('subs', 'visitor', 'buses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Visitor $visitor)
    {
        $request->validate([
            'first_name' => 'required',
            'phone_number' => 'required',
            'last_name' => 'required',
            'business_name' => 'required',
            'check_in_time' => 'required',
            'image' => 'mimes:jpg,png,jpeg,gif|max:5000'
        ]);


        $bus = VisitorBusiness::where('id', $request->business_name)
            ->whereHas('proSubscription', function ($q) {
                $q->whereHas('staffVisitorRole', function ($q) {
                    $q->where('edit', 1)
                        ->where('staff_id', auth('staff')->user()->id);
                });
            })->first();

        if (!$bus) {
            return back()->withErrors('The subscription for business name is not active or you are not allowed to edit');
        }

        if ($request->image) {
            Storage::disk('private')->delete('visitor/' . $visitor->image);
            $filename = basename(Storage::disk('private')->put('visitor', $request->image));
            $visitor->image = $filename;
        }

        $visitor->fname = $request->first_name;
        $visitor->lname = $request->last_name;
        $visitor->pro_subscription_id = $bus->pro_subscription_id;
        $visitor->visitor_business_id = $bus->id;
        $visitor->id_number = $request->id_number;
        $visitor->check_in_time = $request->check_in_time;
        $visitor->check_out_time = $request->check_out_time;
        $visitor->phone_number = $request->phone_number;
        $visitor->edited_by = auth('staff')->user()->id;
        $visitor->notes = $request->notes;
        $visitor->save();
        return back()->with('message', 'Visitor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Visitor  $visitor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Visitor $visitor)
    {

        $visitor = $visitor->whereHas('proSubscription', function ($q) {
            $q->whereHas('staffVisitorRole', function ($q) {
                $q->where('delete', 1)
                    ->where('staff_id', auth('staff')->user()->id);
            });
        })->first();
        if (!$visitor) {
            return back()->withError('No allowed to delete this visitor');
        }

        $sub = ProSubscription::where('sub_status', 'Active')
            ->whereHas('staffVisitorRole', function ($q) {
                $q->where('edit', 1)
                    ->where('staff_id', auth('staff')->user()->id);
            })->first();

        if (!$sub) {
            return back()->withErrors('The subscription for business is not active or does not exist');
        }
        Storage::disk('private')->delete('visitor/' . $visitor->image);
        $visitor->delete();
        return back()->with('message', 'Visitor deleted successfully');
    }

    public function checkOutVisitor(Request $request, $id)
    {
        $request->validate([
            'check_out_time' => 'required',
        ]);
        $visitor = Visitor::find($id);

        if (!$visitor) {
            return back()->withErrors('The visitor does not exist');
        }



        $sub = ProSubscription::where('sub_status', 'Active')
            ->where('id', $visitor->pro_subscription_id)
            ->whereHas('staffVisitorRole', function ($q) {
                $q->where('check_out', 1)
                    ->where('staff_id', auth('staff')->user()->id);
            })->first();

        if (!$sub) {
            return back()->withErrors('The subscription for business is not active or does not exist or not allowed to check out visitors');
        }

        $bus = VisitorBusiness::where('id', $visitor->visitor_business_id)
            ->whereHas('proSubscription', function ($q) {
                $q->whereHas('staffVisitorRole', function ($q) {
                    $q->where('edit', 1)
                        ->where('staff_id', auth('staff')->user()->id);
                });
            })->first();
        $visitor->check_out_time = $request->check_out_time;
        $visitor->checked_out_by = auth('staff')->user()->id;
        $visitor->save();
        $main = $bus->proSubscription->business_name;
        $client = Client::find(auth('staff')->user()->cl_id);
        $message = "Thank you for visiting $main ($bus->business_name) , $visitor->fname! We hope you had a pleasant experience and we look forward to serving you again soon.";
        $sms = new SmsHelper();
        $sms->clientSendQuickSms($sub->sender_id, $client, $visitor->phone_number, $message, 10);


        return back()->with('message', 'Checked out successfully.');
    }



    public function businesses(ProSubscriptionsDataTable $dataTable)
    {


        $subs = ProSubscription::whereHas('staffVisitorRole', function ($q) {
            $q->where('view', 1)
                ->where('staff_id', auth('staff')->user()->id);
        })->get();

        if ($subs->count() == 0) {
            return redirect('/dashboard')->withErrors('You are not allowed to view any visitors.');
        }

        return $dataTable->with(
            [
                'staff_id' => auth('staff')->user()->id,
                'visitors_per_business' => true,

            ]
        )->render('staff.per-business');
    }

    public function visitorsPerBusiness(VisitorsDataTable $dataTable, Request $request, $id)
    {


        $sub = ProSubscription::where('id', $id)
            ->whereHas('staffVisitorRole', function ($q) {
                $q->where('view', 1)
                    ->where('staff_id', auth('staff')->user()->id);
            })->first();

        if (!$sub) {
            return back()->withErrors('Either no allowed or subscription is inactive');
        }

        return $dataTable->with(
            [
                'staff_id' => auth('staff')->user()->id,
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
                'sub_id' => $sub->id,
            ]
        )->render('staff.visitor.index', compact('sub'));
    }

    public function workHistory(VisitorsDataTable $dataTable, Request $request, $work_staff_id)
    {
        $staff = Staff::where('id', $work_staff_id)
            ->where('cl_id', auth('staff')->user()->cl_id)
            ->whereHas('proSubscription', function ($q) {
                $q->whereHas('staffVisitorRole', function ($q) {
                    $q->where('view', 1)
                        ->where('staff_id', auth('staff')->user()->id);
                });
            })->first();

        if (!$staff) {
            return back()->withErrors('Staff Not found or not allowed to view');
        }

        return $dataTable->with(
            [
                'staff_id' => auth('staff')->user()->id,
                'work_staff_id' => $work_staff_id,
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
            ]
        )->render('staff.visitor.index', compact('staff'));
    }
    public function vistorsPerBusinessMinor(VisitorsDataTable $dataTable, Request $request, $business_id)
    {


        $bus = VisitorBusiness::where('id', $business_id)
            ->whereHas('proSubscription', function ($q) {
                $q->whereHas('staffVisitorRole', function ($q) {
                    $q->where('view', 1)
                        ->where('staff_id', auth('staff')->user()->id);
                });
            })->first();
        if (!$bus) {
            return back()->withErrors('Not allowed to view');
        }
        return $dataTable->with(
            [
                'staff_id' => auth('staff')->user()->id,
                'business_id' => $business_id,
                'check_in_start_date' => $request->check_in_start_date,
                'check_in_end_date' => $request->check_in_end_date,
                'check_out_start_date' => $request->check_out_start_date,
                'check_out_end_date' => $request->check_out_end_date,
            ]
        )->render('staff.visitor.index', compact('bus'));
    }
}

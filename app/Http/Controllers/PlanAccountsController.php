<?php

namespace App\Http\Controllers;

use App\Plan;
use App\PlanFeatures;
use Illuminate\Http\Request;
use \Carbon\Carbon;
use App\Account;
use App\SMSPricePlan;


class PlanAccountsController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    //plan control
    public  function viewPlans()
    {
        $plans = Plan::all();

        return view('admin.view-plans', compact('plans'));
    }


    public function  addPlan(Request $request)
    {
        if ($request->isMethod('post')) {
            $v = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'price' => 'required',
                    'features.*' => 'required',
                    'popular' => 'required',
                ]
            );
            if ($v->fails()) {
                return back()->withInput($request->all())->withErrors($v->errors());
            } else {
                $plan = new Plan();

                $plan->name = $request->name;
                $plan->price = $request->price;
                $plan->popular = $request->popular;
                $plan->save();
                $plan_id = $plan->id;
                $features = [];
                foreach ($request->features as $f) {
                    $features[] =
                        [
                            'plan_id' => $plan_id,
                            'feature' => $f,
                            "created_at" =>  Carbon::now(),
                            "updated_at" => Carbon::now(),
                        ];
                }

                PlanFeatures::insert($features);

                return redirect('/admin/plans')->with([
                    'message' => 'The plan was successfully added.',

                ]);
            }
        }

        return view('admin.add-plan');
    }

    public function  editPlan(Request $request, $id)
    {
        $plan = SMSPricePlan::find($id);
        if ($request->isMethod('post')) {
            $v = \Validator::make(
                $request->all(),
                [
                    'features.*' => 'required',

                ]
            );
            if ($v->fails()) {
                return back()->withInput($request->all())->withErrors($v->errors());
            } else {
                $plan->planFeatures()->delete();
                $features = [];
                foreach ($request->features as $f) {
                    $features[] =
                        [
                            'plan_id' => $id,
                            'feature' => $f,
                            "created_at" =>  Carbon::now(),
                            "updated_at" => Carbon::now(),
                        ];
                }

                PlanFeatures::insert($features);

                return back()->with([
                    'message' => 'The plan was successfully  updated ',

                ]);
            }
        }

        return view('admin.edit-plan', compact('plan'));
    }

    public function deletePlan($id)
    {
        $plan = Plan::find($id);
        $plan->delete();
        return redirect('admin/plans')->with([
            'message' => 'The plan was successfully deleted.',

        ]);
    }

    // accounts control
    public  function viewAccounts()
    {
        $accounts = Account::all();

        return view('admin.view-accounts', compact('accounts'));
    }

    public function  addAccount(Request $request)
    {
        if ($request->isMethod('post')) {
            $v = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                ]
            );
            if ($v->fails()) {
                return back()->withInput($request->all())->withErrors($v->errors());
            } else {
                $c = new Account();

                $c->name = $request->name;
                $c->save();
                return redirect('/admin/accounts')->with([
                    'message' => 'The acccount was successfully added.',

                ]);
            }
        }

        return view('admin.add-account');
    }

    public function  editAccount(Request $request, $id)
    {
        $account = Account::find($id);
        if ($request->isMethod('post')) {
            $v = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',

                ]
            );
            if ($v->fails()) {
                return back()->withInput($request->all())->withErrors($v->errors());
            } else {
                $account->name = $request->name;
                $account->save();
                return redirect('admin/accounts')->with([
                    'message' => 'The account was successfully  updated ',

                ]);
            }
        }

        return view('admin.edit-account', compact('account'));
    }

    public function deleteAccount($id)
    {
        $c = Account::find($id);
        $c->delete();
        return redirect('admin/accounts')->with([
            'message' => 'The account was successfully  deleted.',

        ]);
    }
}
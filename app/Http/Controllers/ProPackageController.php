<?php

namespace App\Http\Controllers;


use App\PlanFeatures;
use Illuminate\Http\Request;
use App\Helpers\PriceCalculation;
use \Carbon\Carbon;
use App\Account;
use App\Plan;



class ProPackageController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    //plan control
    public  function viewPackages()
    {
        $packages = Plan::all();

        return view('admin.packages', compact('packages'));
    }


    public function  addPackage(Request $request)
    {
        if ($request->isMethod('post')) {
            $v = \Validator::make($request->all(), [
                'amount' => 'required|numeric',
                'transaction_fee' => 'required|numeric',
                'discount_amount' => 'required|numeric',
                'govt_charges_amount' => 'required|numeric',
                'name' => 'required',
                'popular' => 'required',
                'status' => 'required'
            ]);

            if ($v->fails()) {
                return back()->withInput($request->all())->withErrors($v->errors());
            } else {

                $calc = new PriceCalculation();
                $calc = $calc->calculatePrice(
                    $request->amount,
                    $request->government_Charges_Type,
                    $request->govt_charges_amount,
                    $request->discount_amount,
                    $request->disc_amount_charge,
                    $request->transaction_fee
                );
                $package = new Plan();
                $package->name = $request->name;
                $package->price =  $request->amount;
                $package->transaction_fee = $request->transaction_fee;
                $package->discount_type    = $request->disc_amount_charge;
                $package->apply_discount = $request->discounts;
                $package->discount_amount = $request->discount_amount;
                $package->govt_charges_type    = $request->government_Charges_Type;
                $package->govt_charges_amt = $request->govt_charges_amount;
                $package->apply_govt_charges = $request->apply_govt_charge;
                $package->discount = $calc['discount'];
                $package->tax = $calc['tax'];
                $package->trans_amount = $calc['trans_amount'];
                $package->total = $calc['price'];
                $package->popular = $request->popular;
                $package->status = $request->status;
                $package->save();


                return redirect('/admin/packages')->with([
                    'message' => 'The packages was successfully added.',

                ]);
            }
        }

        return view('admin.add-package');
    }

    public function  editPackage(Request $request, $id)
    {
        $package = Plan::find($id);
        if ($request->isMethod('post')) {
            $v = \Validator::make($request->all(), [
                'amount' => 'required|numeric',
                'transaction_fee' => 'required|numeric',
                'discount_amount' => 'required|numeric',
                'govt_charges_amount' => 'required|numeric',
                'name' => 'required',
                'popular' => 'required',
                'status' => 'required'
            ]);

            if ($v->fails()) {
                return back()->withInput($request->all())->withErrors($v->errors());
            } else {

                $calc = new PriceCalculation();
                $calc = $calc->calculatePrice(
                    $request->amount,
                    $request->government_Charges_Type,
                    $request->govt_charges_amount,
                    $request->discount_amount,
                    $request->disc_amount_charge,
                    $request->transaction_fee
                );
                $package->name = $request->name;
                $package->price =  $request->amount;
                $package->transaction_fee = $request->transaction_fee;
                $package->discount_type    = $request->disc_amount_charge;
                $package->apply_discount = $request->discounts;
                $package->discount_amount = $request->discount_amount;
                $package->govt_charges_type    = $request->government_Charges_Type;
                $package->govt_charges_amt = $request->govt_charges_amount;
                $package->apply_govt_charges = $request->apply_govt_charge;
                $package->discount = $calc['discount'];
                $package->tax = $calc['tax'];
                $package->trans_amount = $calc['trans_amount'];
                $package->total = $calc['price'];
                $package->popular = $request->popular;
                $package->status = $request->status;
                $package->save();

                return redirect('/admin/packages')->with([
                    'message' => 'The packages was successfully updated.',

                ]);
            }
        }

        return view('admin.edit-package', compact('package'));
    }
    public function editFeatures(Request $request, $id)
    {
        $package = Plan::find($id);
        $features = PlanFeatures::where('package_id', $id)->get();
        if ($request->isMethod('post')) {
            $v = \Validator::make(
                $request->all(),
                [

                    'features.*' => 'required',
                ]
            );
            if ($v->fails()) {
                return back()->withInput($request->all())->withErrors($v->errors());
            }
            $features = [];
            foreach ($request->features as $f) {
                $features[] =
                    [
                        'package_id' => $id,
                        'feature' => $f,
                        "created_at" =>  Carbon::now(),
                        "updated_at" => Carbon::now(),
                    ];
            }

            PlanFeatures::insert($features);
            return back()->with([
                'message' => 'The packages was successfully updated.',

            ]);
        }
        return view('admin.edit-package-features', compact('package', 'features'));
    }

    public function deletePackage($id)
    {
        $plan = Plan::find($id);
        $plan->delete();
        return redirect('admin/packages')->with([
            'message' => 'The package was successfully deleted.',

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

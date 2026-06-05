@extends('client')

@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">Airtime Bundle Details</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">Airtime Bundle Details</h3>
                        </div>
                        <div class="panel-body p-none">
                            {{-- <table class="table">
                          
                               
                                @if(isset($airtime_bundle) && !empty($airtime_bundle))
                                <tr>
                                    <th>Credit Amount</th>
                                    <td>{{us_money_format($airtime_bundle->credit_amt)}}</td>
                                </tr>
                                <tr>
                                    <th >Price</th>
                                    <td>{{us_money_format($airtime_bundle->price)}}</td>
                                </tr>
                                <tr>
                                    <th >Transaction Fee</th>
                                     <td>{{us_money_format($airtime_bundle->transaction_fee)}}</td>
                                </tr>
                                <tr>

                                    <th>Discount type</th>
                                    @if( $airtime_bundle->discount_type == 2)
                                         <td>fixed</td>
                                     @elseif($airtime_bundle->discount_type == 3)
                                         <td>No Discount</td>
                                     @else
                                         <td>percent</td>
                                     @endif

                                </tr>

                                <tr>
                                    <th>Apply Discount</th>
                                      @if( $airtime_bundle->apply_discount == 1)
                                        <td>{{language_data('one time')}}</td>

                                      @elseif($airtime_bundle->apply_discount == 2)
                                         <td>{{language_data('recurring')}}</td>
                                      @else
                                        <td>{{language_data('first purchase only')}}</td>
                                      @endif
                                </tr>

                                <tr>
                                    <th>Discount Amount</th>
                                    <td>{{us_money_format($airtime_bundle->discount_amt)}}</td>
                                </tr>
                                       
                                      
                                 <tr>
                                    <th>Goverment Charges Type</th>
                                    @if( $airtime_bundle->govt_charges_type == 2)
                                    <td >{{language_data('fixed')}}</td>
                                    @else
                                    <td >{{language_data('percent')}}</td>
                                    @endif

                                 </tr>
                                       

                                <tr>
                                <th >Apply Goverment Charges</th>
                                @if( $airtime_bundle->apply_govt_charges == 1)
                                <td >{{language_data('Tax')}}</td>
                                @else
                                <td >{{language_data('other charges')}}</td>
                                @endif
                                </tr>

                                <tr>
                                    <th>Goverment Charges Amount</th>
                                    <td>{{us_money_format($airtime_bundle->govt_charges_amt)}}</td>
                                </tr>

                                
                                @php
                                $full_name = $airtime_bundle->fname. " ".$airtime_bundle->lname;
                                @endphp

                                <tr>
                                    <th>Client</th>
                                    <td>{{$full_name }}</td>
                                </tr>

                                <tr>
                                    <th>Client Group</th>
                                    <td>{{$airtime_bundle->group_name }}</td>
                                </tr>


                                <tr>
                                    <th>Plan Type</th>
                                    <td>{{$airtime_bundle->plan_name}}</td>
                                </tr>

                                <tr>
                                    <th>Status</th>
                                    <td>{{$airtime_bundle->status}}</td>
                                </tr>

                                <tr>
                                    <th>Mark Popular</th>
                                    <td>{{$airtime_bundle->popular}}</td>
                                </tr>

                                <tr>

                                    <th>Created_at</th>
                                    <td>{{ date('Y-M-d',strtotime($airtime_bundle->created_at))  }}</td>
                                </tr>

                                @endif
                               
                            </table> --}}

                            @if(isset($airtime_bundle) && !empty($airtime_bundle))

                            <div class="card text-center" style="width: 25rem; margin: 0 auto; margin-bottom: 15px; border: 1px solid black; padding: 12px; background: aliceblue;">
                                <h4 class="card-title">{{$airtime_bundle->bundle_name}}</h4>
                                <div class="card-body">
                                  <h5>{{$airtime_bundle->credit_amt}} Units</h5>
                                  <p class="card-text">Unit Price: KES {{$airtime_bundle->price}}</p>
                                  @php
                                      $pay_amount = $airtime_bundle->credit_amt * $airtime_bundle->price;
                                  @endphp
                                  <p class="card-text">Amount to Pay: KES {{$pay_amount}}</p>

                                  <p class="card-text">Transaction Fee: {{$airtime_bundle->transaction_fee}}%</p>

                                  @if($airtime_bundle->discount_type == 2)
                                  <p class="card-text">Discount Amount : KES {{$airtime_bundle->discount_amt}}</p>

                                  @elseif($airtime_bundle->discount_type == 3)
                                  <p class="card-text">Discount Amount : KES {{$airtime_bundle->discount_amt}}</p>
                                  @else
                                  <p class="card-text">Discount Amount : {{$airtime_bundle->discount_amt}}%</p>
                                  @endif

                                  @if($airtime_bundle->govt_charges_type == 2)
                                    <p class="card-text">Goverment Tax : KES {{$airtime_bundle->govt_charges_amt}}</p>
                                  @else
                                    <p class="card-text">Goverment Tax :  {{$airtime_bundle->govt_charges_amt}}%</p>
                                  @endif

                                  @php

                                    // applying transaction fee on pay amount

                                    $amt_aft_trans =  $pay_amount * $airtime_bundle->transaction_fee/100;

                                    $add_trn_amt = $pay_amount + $amt_aft_trans;

                                    if($airtime_bundle->discount_type == 2)
                                    {
                                      $add_dis_amt =   $add_trn_amt - $airtime_bundle->discount_amt;
                                    }
                                    elseif($airtime_bundle->discount_type == 3)
                                    {
                                        $add_dis_amt =   $add_trn_amt - $airtime_bundle->discount_amt;
                                    }
                                    else 
                                    {
                                        $per_dis_amount = $add_trn_amt * $airtime_bundle->discount_amt/100;
                                        $add_dis_amt =   $add_trn_amt - $per_dis_amount;
                                    }

                                    if($airtime_bundle->govt_charges_type == 2)
                                    {
                                        $add_govt_amt =  $add_dis_amt + $airtime_bundle->govt_charges_amt;
                                    }
                                    else 
                                    {
                                        $per_add_amt = $add_dis_amt * $airtime_bundle->govt_charges_amt/100;
                                        $add_govt_amt = $add_dis_amt + $per_add_amt;
                                    }

                                  @endphp

                                  <p class="card-text">Amount Payable : KES {{$add_govt_amt}}</p>

                                  <a href="#" class="btn btn-primary">Buy Now</a>
                                </div>
                              </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
@extends('client')

@section('content')

    <section class="wrapper-bottom-sec">
        <div class="p-30">
            <h2 class="page-title">SmS Price Plan Details</h2>
        </div>
        <div class="p-30 p-t-none p-b-none">
            @include('notification.notify')
            <div class="row">

                <div class="col-lg-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <h3 class="panel-title">SmS Price Plan Details</h3>
                        </div>
                        <div class="panel-body p-none">
                            <table class="table">
                          
                               
                                @if(isset($price_plan) && !empty($price_plan))
                                <tr>
                                    <th>Unit From</th>
                                    <td>{{$price_plan->unit_from}}</td>
                                </tr>
                                <tr>
                                    <th>Unit To</th>
                                    <td>{{$price_plan->unit_to}}</td>
                                </tr>
                                <tr>
                                    <th >Price</th>
                                    <td>{{us_money_format($price_plan->price)}}</td>
                                </tr>
                                <tr>
                                    <th >Transaction Fee</th>
                                     <td>{{us_money_format($price_plan->transaction_fee)}}</td>
                                </tr>
                                <tr>

                                    <th>Discount type</th>
                                    @if( $price_plan->discount_type == 2)
                                         <td>fixed</td>
                                     @elseif($price_plan->discount_type == 3)
                                         <td>No Discount</td>
                                     @else
                                         <td>percent</td>
                                     @endif

                                </tr>

                                <tr>
                                    <th>Apply Discount</th>
                                      @if( $price_plan->apply_discount == 1)
                                        <td>{{language_data('one time')}}</td>

                                      @elseif($price_plan->apply_discount == 2)
                                         <td>{{language_data('recurring')}}</td>
                                      @else
                                        <td>{{language_data('first purchase only')}}</td>
                                      @endif
                                </tr>

                                <tr>
                                    <th>Discount Amount</th>
                                    <td>{{us_money_format($price_plan->discount_amount)}}</td>
                                </tr>
                                       
                                      
                                 <tr>
                                    <th>Goverment Charges Type</th>
                                    @if( $price_plan->govt_charges_type == 2)
                                    <td >{{language_data('fixed')}}</td>
                                    @else
                                    <td >{{language_data('percent')}}</td>
                                    @endif

                                 </tr>
                                       

                                <tr>
                                <th >Apply Goverment Charges</th>
                                @if( $price_plan->apply_govt_charges == 1)
                                <td >{{language_data('Tax')}}</td>
                                @else
                                <td >{{language_data('other charges')}}</td>
                                @endif
                                </tr>

                                <tr>
                                    <th>Goverment Charges Amount</th>
                                    <td>{{us_money_format($price_plan->govt_charges_amt)}}</td>
                                </tr>

                                
                                @php
                                $full_name = $price_plan->fname. " ".$price_plan->lname;
                                @endphp

                                <tr>
                                    <th>Client</th>
                                    <td>{{$full_name }}</td>
                                </tr>

                                <tr>
                                    <th>Client Group</th>
                                    <td>{{$price_plan->group_name }}</td>
                                </tr>

                                {{-- <tr>
                                    <th>Show in client</th>
                                    <td>{{$price_plan->show_client}}</td>
                                </tr> --}}

                                <tr>
                                    <th>Plan Type</th>
                                    <td>{{$price_plan->plan_name}}</td>
                                </tr>

                                <tr>
                                    <th>Status</th>
                                    <td>{{$price_plan->status}}</td>
                                </tr>

                                <tr>
                                    <th>Mark Popular</th>
                                    <td>{{$price_plan->popular}}</td>
                                </tr>

                                <tr>

                                    <th>Created_at</th>
                                    <td>{{ date('Y-M-d',strtotime($price_plan->created_at))  }}</td>
                                </tr>

                                @endif
                               
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
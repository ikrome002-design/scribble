@extends('admin')

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
                            <table class="table">
                          
                               
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
                                    <th>Show in client</th>
                                    <td>{{$airtime_bundle->show_client}}</td>
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
                               
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
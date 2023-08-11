@extends('layouts.app')
@section('title', 'Dth Recharge')
@section('pagetitle', 'Dth Recharge')


@section('content')


<div class="row ">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle')</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <form id="rechargeForm" action="{{route('dth')}}" autocomplete="off">
                    {{ csrf_field() }}
                    <input type="hidden" name="type" value="">
                    <input type="hidden" name="circle" value="">
                    <input type="hidden" name="providername" value="">
                    <div class="row">
                        <div class="form-group col-3">
                            <label>DTH Number <span class="text-danger fw-bold">*</span></label>
                            <input type="text" name="number" class="form-control" placeholder="Enter DTH number" onchange="getoperator()" required="">
                            <!--onchange="getoperator()"-->
                        </div>
                        <div class="form-group col-3">
                            <label>DTH Operator <span class="text-danger fw-bold">*</span></label>
                            <select name="provider_id" class="form-control" required="" onchange="getdthinfo()">
                                <option value="">Select Operator</option>

                                <option value="1">Airtel DTH</option>
                                <option value="1">Dish TV</option>
                                <option value="1">Sun Direct</option>
                                <option value="1">Tata Sky</option>

                            </select>
                        </div>
                        <div class="dthinf" style="display:none">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Name</th>
                                    <td class="name"></td>
                                </tr>
                                <tr>
                                    <th>Plan Name</th>
                                    <td class="planname"></td>
                                </tr>
                                <tr>
                                    <th>Balance</th>
                                    <td class="balance"></td>
                                </tr>
                                <tr>
                                    <th>Monthly Plan</th>
                                    <td class="mplan"></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td class="status"></td>
                                </tr>
                                <tr>
                                    <th>Recharge Date</th>
                                    <td class="date"></td>
                                </tr>
                            </table>
                        </div>


                        <div class="form-group col-3">
                            <label>Recharge Amount <span class="text-danger fw-bold">*</span></label>
                            <input type="text" name="amount" class="form-control" placeholder="Enter  amount" required="">
                        </div>
                        <div class="form-group col-3">
                            <label>T-Pin <span class="text-danger fw-bold">*</span></label>
                            <input type="password" name="pin" class="form-control" placeholder="Enter transaction pin" required="">
                            <a href="{{route('dth')}}" target="_blank" class="text-primary pull-right">Generate Or Forgot Pin??</a>
                        </div>

                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Paying"><b><i class=" icon-paperplane"></i></b> Pay Now</button>
                            <button type="button" class="btn submit-button btn-success" onclick="getplan()">GET Plan</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>

    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mt-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>Recent @yield('pagetitle')</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th>Order ID</th>
                                <th>Recharge Details</th>
                                <th>Amount/Commission</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
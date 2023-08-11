@extends('layouts.app')
@section('title', 'UTI Pancard')
@section('pagetitle', 'UTI Pancard')


@section('content')

<div class="row">
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle')</span>
                    </h5>
                </div>
                <div class="col-sm-12 col-md-7 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <a href="http://www.psaonline.utiitsl.com/psaonline/" class="btn btn-primary ms-2 text-white ">
                            Login UTI Portal</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-bordered my-2">
                    <tr>
                        <td>1 Token</td>
                        <td>1 PAN Application</td>
                    </tr>
                    <tr>
                        <td>Username</td>
                        <td>MAHA26-AMTW9875876</td>
                    </tr>
                    <tr>
                        <td>Password</td>
                        <td></td>
                    </tr>
                </table>


                <form id="pancardForm" action="{{route('panuti')}}">
                    {{ csrf_field() }}
                    <input type="hidden" name="actiontype" value="purchase">
                    <div class="panel-body">
                        <div class="form-group my-1">
                            <label>No Of Tokens</label>
                            <input type="number" class="form-control my-1" name="tokens" placeholder="Enter No. of tokens" required="">
                        </div>
                        <div class="form-group my-1">
                            <label>Total Price in Rs</label>
                            <input type="number" class="form-control my-1" id="price" value="" readonly>
                        </div>
                        <div class="form-group my-1">
                            <label>Vle Id</label>
                            <input type="text" class="form-control my-1" name="vleid" value="MAHA26-AMTW9875876" required="">
                        </div>
                        <div class="form-group my-1">
                            <label>T-Pin</label>
                            <input type="password" name="pin" class="form-control my-1" placeholder="Enter transaction pin" required="">
                            <a href="{{url('panuti')}}" target="_blank" class="text-primary pull-right">Generate Or Forgot Pin??</a>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Pay Now</button>
                    </div>
                </form>
            </div>

        </div>

    </div>
    <div class="col-8 col-xl-8 col-sm-8 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>Recent Coupon Purchase </span>
                    </h5>
                </div>
            </div>
            <div class="card-body mb-5">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>Order ID</th>
                            <th>User Details</th>
                            <th>Transaction Details</th>
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

@endsection
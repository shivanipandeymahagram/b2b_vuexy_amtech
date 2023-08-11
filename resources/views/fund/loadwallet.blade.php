@extends('layouts.app')
@section('title', 'Wallet Load Request')
@section('pagetitle', 'Wallet Load Request')


@section('content')

@include('layouts.pageheader')
<div class="row">
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>STATE BANK OF INDIA</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div>IFSC : SBIN00065</div>
                        <div>AC / NO. : 234343443345345</div>
                        <div>Branch : Vikas Marge New Delhi</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>ICICI BANK</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div>IFSC : ICICI000998</div>
                        <div>AC / NO. : 1212332434534534</div>
                        <div>Branch : SURAJ MAL VIHAR DELHI</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>HDFC BANK</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div>IFSC : HDFC00093</div>
                        <div>AC / NO. : 5654545454545454</div>
                        <div>Branch : PREET BIHAR DELHI</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>PNB BANK</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div>IFSC : PUNB99786767</div>
                        <div>AC / NO. : 34673246734673</div>
                        <div>Branch : KAILASH NAGAR DELHI</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
</div>


@endsection
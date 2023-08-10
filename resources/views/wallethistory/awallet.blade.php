@extends('layouts.app')
@section('title', 'Aeps Wallet Statement')
@section('pagetitle', 'Aeps Wallet Statement')


@section('content')

@include('layouts.pageheader')
<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle') - Table</span>
                    </h5>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Refrences Details</th>
                            <th>Order ID</th>
                            <th>Transaction Details</th>
                            <th>TXN Type</th>
                            <th>ST Type</th>
                            <th>Status</th>
                            <th>Opening Bal. </th>
                            <th>Amount </th>
                            <th>Closing Bal. </th>
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
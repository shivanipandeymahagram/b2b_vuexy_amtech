@extends('layouts.app')
@section('title', 'Investment Fund Request')
@section('pagetitle', 'Investment Fund Request')


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
                            <th>Requested By</th>
                            <th>Deposit Bank Details</th>
                            <th>Refrence Details</th>
                            <th>Amount</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="col">
                                <label class="switch">
                                    <input type="checkbox" class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                        <span class="switch-off"></span>

                                    </span>

                                </label>
                                <p>
                                    9 Aug 23 - 12:28 PM
                                </p>
                            </td>
                            <td scope="col">
                                <div><small>Anil Kumar (4) (Retailer)</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Name - HDFC BANK</small></div>
                                <div><small>Account No. - 50200065891772</small></div>
                                <div><small> Branch - PREET VIHAR DELHI</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Ref No. - ssssssss</small></div>
                                <div><small>Paydate - 2023-08-09</small></div>
                                <div><small>Paymode - NEFT ( )</small></div>
                            </td>
                            <td scope="col">
                                <div><small>100</small></div>
                            </td>
                            <td scope="col">
                                <div><small>aasassssaas</small></div>
                            </td>
                            <td scope="col">
                                <div class="btn-group ">
                                    <button type="button" class="btn btn-warning dropdown-toggle btn-xs me-1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Pending
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item disabled" href="javascript:void(0);">Action</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Update Request</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

@endsection
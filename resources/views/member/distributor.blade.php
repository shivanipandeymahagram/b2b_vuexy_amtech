@extends('layouts.app')
@section('title', 'Distributor List')
@section('pagetitle', 'Distributor List')


@section('content')

@include('layouts.pageheader')  
<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle') - Table</span>
                    </h5>
                </div>
                <div class="col-sm-12 col-md-2 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <a href="{{route('dcreate')}}" class="btn btn-success text-white ms-4" onclick="addSetup()">
                            <i class="ti ti-plus ti-xs"></i> Add New</a>
                    </div>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent Details</th>
                            <th>Company Profile</th>
                            <th>Wallet Details</th>
                            <th>Id Stock</th>
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
                                    24 Jul 23 - 12:28 PM
                                </p>
                            </td>
                            <td scope="col">
                                <div><small>Shivani</small></div>
                                <div><small>6393784131</small></div>
                                <div><small>Distributor</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Admin (1)</small></div>
                                <div><small>1234567890</small></div>
                                <div><small> Admin</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Main</small></div>
                                <div><small>login.amtechpe.in</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Main : 0 /-</small></div>
                                <div><small>AEPS : 0 /-</small></div>
                            </td>
                            <td scope="col">
                                <div><small>MD - 0</small></div>
                                <div><small>Distributor - 0</small></div>
                                <div><small> Retailer - 0</small></div>
                            </td>
                            <td scope="col">
                                <div class="btn-group ">
                                    <button type="button" class="btn btn-primary dropdown-toggle me-1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item disabled" href="javascript:void(0);">Action</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Fund Transfer/Return</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Scheme</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Add Id Stock</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Permission</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">View Profile</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">KYC Manager</a></li>
                                    </ul>

                                    <button type="button" class="btn btn-primary dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                                        Reports
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item " href="javascript:void(0);">Billpayment</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Recharge</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">AEPS</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Money Transfer</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">UTI Pan Card</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">UTI Id</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Account Statement</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">AEPS Wallet</a></li>
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

@push('style')
<style>
    .md-checkbox {
        margin: 5px 0px;
    }
</style>
@endpush
@extends('layouts.app')
@section('title', 'Fund Transfer & Return')
@section('pagetitle', 'Fund Transfer & Return')


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
                    <thead class=" text-center">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent Details</th>
                            <th>Company Profile</th>
                            <th>Wallet Details</th>
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
                                    1 Aug 23 - 12:28 PM
                                </p>
                            </td>
                            <td scope="col">
                                <div><small>Shivani</small></div>
                                <div><small>6393784131</small></div>
                                <div><small>Retailer</small></div>
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
                                <div><small>Main : 268 ₹/-</small></div>
                                <div><small>AEPS : 0 ₹/-</small></div>
                            </td>
                            <td scope="col">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#trModal">
                                    Transfer/Return
                                </button>
                            </td>
                        </tr>
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
                                <div><small>Shivam</small></div>
                                <div><small>6393784131</small></div>
                                <div><small>Retailer</small></div>
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
                                <div><small>Main : 139 ₹/-</small></div>
                                <div><small>AEPS : 0 ₹/-</small></div>
                            </td>
                            <td scope="col">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#trModal">
                                    Transfer/Return
                                </button>
                            </td>
                        </tr>
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
                                    8 Aug 23 - 12:28 PM
                                </p>
                            </td>
                            <td scope="col">
                                <div><small>Amit</small></div>
                                <div><small>6393784131</small></div>
                                <div><small>Retailer</small></div>
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
                                <div><small>Main : 150 ₹/-</small></div>
                                <div><small>AEPS : 0 ₹/-</small></div>
                            </td>
                            <td scope="col">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#trModal">
                                    Transfer/Return
                                </button>
                            </td>
                        </tr>
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
                                    19 Jul 23 - 12:28 PM
                                </p>
                            </td>
                            <td scope="col">
                                <div><small>WhiteLable</small></div>
                                <div><small>6393784131</small></div>
                                <div><small>WhiteLable</small></div>
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#trModal">
                                    Transfer/Return
                                </button>
                            </td>
                        </tr>
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#trModal">
                                    Transfer/Return
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="trModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">@yield('pagetitle')</h3>
                </div>
                <form id="transferForm" action="{{route('tr')}}">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Fund Action</label>
                                <select name="type" class="form-control my-1" id="select" required>
                                    <option value="">Select Action</option>
                                    <option value="transfer">Transfer</option>

                                    <option value="return">Return</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Amount</label>
                                <input type="number" name="amount" step="any" class="form-control my-1" placeholder="Enter Amount" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 my-1">
                                <label>Remark</label>
                                <input type="text" name="remark" class="form-control my-1" placeholder="Enter Remark">

                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
                </form>
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
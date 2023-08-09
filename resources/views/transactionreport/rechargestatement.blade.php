@extends('layouts.app')
@section('title', 'Recharge Statement')
@section('pagetitle', 'Recharge Statement')


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
                            <th>Order ID</th>
                            <th>User Details</th>
                            <th>Transaction Details</th>
                            <th>Refrence Details</th>
                            <th>Amount/Commission</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="col">
                                <div> Mahagram Recharge </div>
                                <div> 75 </div>
                                <div> 09 Aug 23 - 12:51 PM </div>
                            </td>
                            <td scope="col">
                                <div><small>Shivani (4)</small></div>
                                <div><small>Retailer</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Number - 7208822571</small></div>
                                <div><small>Operator - Airtel</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Ref No. - 1234</small></div>
                                <div><small>Txnid - 45</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Amount - 10 ₹/-</small></div>
                                <div><small>Profit - 0 ₹/-</small></div>
                            </td>
                            <td scope="col">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Refunded
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#complaintModal">Comlaint</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td scope="col">
                                <div> ambikamultiservices </div>
                                <div> 75 </div>
                                <div> 09 Aug 23 - 12:51 PM </div>
                            </td>
                            <td scope="col">
                                <div><small>Shivam (4)</small></div>
                                <div><small>Retailer</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Number - 7208822571</small></div>
                                <div><small>Operator - Airtel</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Ref No. - 530809162019036</small></div>
                                <div><small>Txnid - RP5546571135</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Amount - 10 ₹/-</small></div>
                                <div><small>Profit - 0 ₹/-</small></div>
                            </td>
                            <td scope="col">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Success
                                    </button>
                                    <ul class="dropdown-menu">

                                        <li><a class="dropdown-item" href="javascript:void(0);">Check Status</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal">Edit</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#complaintModal">Comlaint</a></li>

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

<div class="modal fade" id="complaintModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">@yield('pagetitle')</h3>
                </div>
                <form id="transferForm" action="{{route('rechargestatement')}}">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                            <div class="form-group col-md-12 my-1">
                                <label>Subject</label>
                                <select name="type" class="form-control my-1" id="select" required>
                                    <option value="">Select Subject</option>
                                    <option value="transfer">Recharge</option>

                                </select>
                            </div>
                            <div class="form-group col-md-12 my-1">
                                <label>Amount</label>
                                <textarea type="text" class="form-control my-1"></textarea>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">@yield('pagetitle')</h3>
                </div>
                <form id="transferForm" action="{{route('rechargestatement')}}">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Status</label>
                                <select name="type" class="form-control my-1" id="select" required>
                                    <option value="">Select Status</option>
                                    <option value="transfer">Success</option>
                                    <option value="return">Pending</option>
                                    <option value="return">Failed</option>
                                    <option value="return">Reversed</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Ref No</label>
                                <input type="text" name="ref" step="any" class="form-control my-1" placeholder="Enter Ref No" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Txn Id</label>
                                <input type="text" name="txn_id" step="any" class="form-control my-1" placeholder="Enter Txn Id" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Pay Id</label>
                                <input type="text" name="pay_id" step="any" class="form-control my-1" placeholder="Enter Pay Id" required="">
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
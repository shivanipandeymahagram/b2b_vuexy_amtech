@extends('layouts.app')
@section('title', 'Aeps Fund Request')
@section('pagetitle', 'Aeps Fund Request')


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
                <div class="col-sm-12 col-md-2 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <a href="" class="btn btn-success text-white " data-bs-toggle="modal" data-bs-target="#runpaisaModal">
                            <i class="ti ti-plus ti-xs"></i> Runpaisa Payout</a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th>Bank Details</th>
                            <th>Refrence Details</th>
                            <th>Amount</th>
                            <th>Remark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="runpaisaModal" tabindex="-1" role="modal" aria-labelledby="exampleModalLabels" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Runpaisa Payout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <!-- <span aria-hidden="true">&times;</span> -->
                </button>
            </div>

            <form id="fundRequestFormsRunpaisa" action="{{route('req')}}" method="post">
                <div class="modal-body">
                    <table class="table table-bordered p-b-15" cellspacing="0" style="margin-bottom: 30px">
                        <thead>
                            <tr>
                                <th>Select bank</th>
                                <th>Bank</th>
                                <th>Account</th>
                                <th>Ifsc</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <td><input type="radio" id="banktype" name="bankacccount" value="ICICI Bank"></td>
                                <td>ICICI</td>
                                <td>ZZXXXYYYY</td>
                                <td>ABCD1234</td>

                            </tr>
                        </tbody>
                    </table>
                    <input type="hidden" name="user_id">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-6 my-1">
                            <label>Account Number <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="account" placeholder="Enter Value" required="" value="ICICI">
                        </div>
                        <div class="form-group col-md-6 my-1">
                            <label>IFSC Code <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="ifsc" placeholder="Enter Value" required="" value="ABCD12345">
                        </div>
                        <div class="form-group col-md-6 my-1">
                            <label>Bank Name <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="bank" placeholder="Enter Value" required="" value="ZZZZZZZXXXXXXYYYYYY">
                        </div>

                        <div class="form-group col-md-6 my-1">
                            <label>Wallet Type <span class="text-danger fw-bold">*</span></label>
                            <select name="type" class="form-control my-1" required>
                                <option value="">Select Wallet</option>
                                <option value="bank">Move To Bank</option>
                                <option value="wallet">Move To Wallet</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 my-1">
                            <label>Amount <span class="text-danger fw-bold">*</span></label>
                            <input type="number" class="form-control my-1" name="amount" placeholder="Enter Value" required="">
                        </div>
                        <div class="form-group col-md-6 my-1">
                            <label>Comments <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="comments" placeholder="Enter Value" required="">
                        </div>
                        <div class="form-group col-md-6 my-1">
                            <label>T- PIN <span class="text-danger fw-bold">*</span></label>
                            <input type="password" name="pin" class="form-control my-1" placeholder="Enter transaction pin" required="">
                            <a href="{{route('req')}}" target="_blank" class="text-primary pull-right">Generate or Forgot PIN?</a>
                        </div>
                    </div>
                    <p class="text-danger">Note - If you want to change bank details, please send mail with account details to update your bank details.</p>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
            </form>

        </div>
    </div>
</div>
@endsection
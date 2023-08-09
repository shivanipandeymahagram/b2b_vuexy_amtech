@extends('layouts.app')
@section('title', 'MicroAtm Request')
@section('pagetitle', 'MicroAtm Request')


@section('content')

@include('layouts.pageheader')
<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle')</span>
                    </h5>
                </div>
                <div class="col-sm-12 col-md-2 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <button class="btn btn-success text-white ms-2" data-bs-toggle="modal" data-bs-target="#requestModal">
                            New Request</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th> User Details</th>
                            <th> Bank Details</th>
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


<div class="modal fade" id="requestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Micro ATM Fund Request</h3>
                </div>
                <form id="transferForm" action="{{route('mrequest')}}">
                    <div class="modal-body">
                        <div class="row">
                            <table class="table table-bordered p-b-15" cellspacing="0" style="margin-bottom: 30px">
                                <thead>
                                    <tr>
                                        <th>Account</th>
                                        <th>Bank</th>
                                        <th>Ifsc</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>23</td>
                                        <td>54</td>
                                        <td>9</td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table table-bordered p-b-15" cellspacing="0" style="margin-bottom: 30px">
                                <tbody>
                                    <tr>
                                        <th>Settlement Charge</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Settlement Timing</th>
                                        <td>Bank</td>
                                    </tr>
                                </tbody>
                            </table>

                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                        </div>
                        <!-- <div class="row">
                            <div class="form-group col-md-6">
                                <label>Account Number</label>
                                <input type="text" class="form-control" name="account" placeholder="Enter Value" required="" value="23">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Ifsc Code</label>
                                <input type="text" class="form-control" name="ifsc" placeholder="Enter Value" required="" value="54">
                            </div>
                            <div class="form-group col-md-6">
                                <label>Bank Name</label>
                                <input type="text" class="form-control" name="bank" placeholder="Enter Value" required="" value="9">
                            </div>
                        </div> -->
                        <div class="row my-2">
                            <div class="form-group col-md-6">
                                <label>Wallet Type</label>
                                <select name="type" class="form-control" required>
                                    <option value="">Select Wallet</option>
                                    <option value="bank">Move To Bank</option>
                                    <option value="wallet">Move To Wallet</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Amount</label>
                                <input type="number" class="form-control" name="amount" placeholder="Enter Value" required="">
                            </div>
                        </div>
                        <p class="text-danger">Note - If you want to change bank details, please send mail with account details to update your bank details.</p>

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
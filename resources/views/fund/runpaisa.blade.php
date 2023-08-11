@extends('layouts.app')
@section('title', 'Runpaisa PG Request')
@section('pagetitle', 'Runpaisa PG Request')


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
                <div class="col-sm-12 col-md-3 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <a href="{{route('runpaisa')}}" class="btn btn-success me-1 text-white" data-bs-toggle="modal" data-bs-target="#reqModal">
                            New Request</a>
                        <a href="{{route('runpaisa')}}" class="btn btn-success ms-1 text-white" data-bs-toggle="modal" data-bs-target="#getlinkModal">
                            Get Link</a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th>Reference Details</th>
                            <th>Amount</th>
                            <th>Remark</th>
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
<div class="modal fade" id="reqModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Wallet Fund Request</h3>
                </div>

                <form id="pgRequestForm" action="{{route('runpaisa')}}>
                    <div class=" modal-body">
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="type" value="pgdirect">
                    {{ csrf_field() }}
                    <div class="row">

                        <div class="form-group my-1 col-md-6">
                            <label>Amount</label>
                            <input type="number" name="amount" step="any" class="form-control my-1"  placeholder="Enter Amount" required="">
                        </div>
                        <div class="form-group my-1 col-md-6">
                            <label>Mobile</label>
                            <input type="number" name="mobile" step="any" class="form-control my-1"  placeholder="Enter Mobile" required="">
                        </div>
                        <div class="form-group my-1 col-md-12">
                            <label>Email</label>
                            <input type="text" name="email" step="any" class="form-control my-1"  placeholder="Enter Email" required="">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group my-1 col-md-12">
                            <label>Remark</label>
                            <textarea name="remark" class="form-control my-1"  rows="2" placeholder="Enter Remark" required=""></textarea>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Close</button>
                <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="getlinkModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Get Payment Links</h3>
                </div>

                <form id="linkRequestForm" action="{{route('runpaisa')}}">
                    <div class="modal-body">
                        <input type="hidden" name="user_id">
                        <input type="hidden" name="type" value="pgdirect">
                        {{ csrf_field() }}
                        <div class="row">

                            <div class="form-group my-1 col-md-6">
                                <label>Amount</label>
                                <input type="number" name="amount" step="any" class="form-control my-1"  placeholder="Enter Amount" required="">
                            </div>
                            <div class="form-group my-1 col-md-6">
                                <label>Mobile</label>
                                <input type="number" name="mobile" step="any" class="form-control my-1"  placeholder="Enter Mobile" required="">
                            </div>
                            <div class="form-group my-1 col-md-12">
                                <label>Email</label>
                                <input type="text" name="email" step="any" class="form-control my-1"  placeholder="Enter Email" required="">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group my-1 col-md-12">
                                <label>Remark</label>
                                <textarea name="remark" class="form-control my-1"  rows="2" placeholder="Enter Remark" required=""></textarea>
                            </div>
                        </div>

                        <div class="mydiv">

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
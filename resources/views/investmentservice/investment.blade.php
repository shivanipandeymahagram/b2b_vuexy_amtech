@extends('layouts.app')
@section('title', 'Investment List')
@section('pagetitle', 'Investment List')


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
                <div class="col-sm-12 col-md-3 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <button class="btn btn-success text-white ms-5" data-bs-toggle="modal" data-bs-target="#investmentModal">
                            <i class="ti ti-plus ti-xs"></i>  Add Investment</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Banner</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Mature Amount</th>
                            <th>Maturity Amount</th>
                            <th>Amount</th>
                            <th>Satus</th>
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


<div class="modal fade" id="investmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Investment</h3>
                </div>
                <form id="transferForm" action="{{route('investment')}}">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Banner</label>
                                <select name="type" class="form-control my-1" id="select" required>
                                    <option value="">Title</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>From </label>
                                <input type="date" name="f_date" class="form-control my-1" required="">
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label>To </label>
                                <input type="date" name="t_date" class="form-control my-1" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Mature Amount </label>
                                <input type="number" name="m_amount" class="form-control my-1" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Maturity At </label>
                                <input type="date" name="m_date" class="form-control my-1" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Amount </label>
                                <input type="number" name="amount" class="form-control my-1" required="">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
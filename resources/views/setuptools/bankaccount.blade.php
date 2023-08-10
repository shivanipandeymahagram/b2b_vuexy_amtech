@extends('layouts.app')
@section('title', 'Bank Account List')
@section('pagetitle', 'Bank Account List')


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
                        <a href="{{route('bankaccount')}}" class="btn btn-success text-white ms-4" data-bs-toggle="modal" data-bs-target="#addModal">
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
                            <th>Account</th>
                            <th>IFSC</th>
                            <th>Branch</th>
                            <th>Charge</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>5</td>
                            <td>UNION BANK OF INDIA</td>
                            <td>053221010000034</td>
                            <td>UBIN0905321</td>
                            <td>PREET VIHAR DELHI</td>
                            <td>50</td>
                            <td>
                                <label class="switch">
                                    <input type="checkbox" class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                        <span class="switch-off"></span>
                                    </span>
                                </label>
                            </td>
                            <td>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editModal" type="button">Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">@yield('pagetitle')</h3>
                </div>

                <form id="setupManager" action="{{route('bankaccount')}}">
                <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="bank">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control my-1" placeholder="Enter Bank Name" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Account Number</label>
                                <input type="text" name="account" class="form-control my-1" placeholder="Enter Account Number" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 my-1">
                                <label>IFSC</label>
                                <input type="text" name="ifsc" class="form-control my-1" placeholder="Enter Ifsc Code" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Branch</label>
                                <input type="text" name="branch" class="form-control my-1" placeholder="Enter Branch" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 my-1">
                                <label>Charges/Lakh</label>
                                <input type="number" name="charge" class="form-control my-1" placeholder="Enter charge amount" value="0" required="">
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


<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">@yield('pagetitle')</h3>
                </div>

                <form id="setupManager" action="{{route('bankaccount')}}">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="bank">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control my-1" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Account Number</label>
                                <input type="text" name="account" class="form-control my-1" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 my-1">
                                <label>IFSC</label>
                                <input type="text" name="ifsc" class="form-control my-1" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Branch</label>
                                <input type="text" name="branch" class="form-control my-1"  required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 my-1">
                                <label>Charges/Lakh</label>
                                <input type="number" name="charge" class="form-control my-1" value="0" required="">
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
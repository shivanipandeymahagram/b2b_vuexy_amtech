@extends('layouts.app')
@section('title', 'Company Manager')
@section('pagetitle', 'Company Manager')

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
                        <button type="button" class="btn btn-success text-white ms-4" onclick="addSetup()" data-bs-toggle="modal" data-bs-target="#setupModal">
                            <i class="ti ti-plus ti-xs"></i> Add New</button>
                    </div>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Domain</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="col">1</td>
                            <td scope="col">Vuexy</td>
                            <td scope="col">https://vuexy.com</td>
                            <td scope="col">
                                <label class="switch">
                                    <input type="checkbox" class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                        <span class="switch-off"></span>
                                    </span>
                                </label>
                            </td>
                            <td scope="col">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Action
                                    </button>
                                    <ul class="dropdown-menu">

                                        <li><a class="dropdown-item disabled" href="javascript:void(0);">Setting</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Edit</a></li>
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


<div class="modal fade" id="setupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Add Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="setupManager" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="company">
                        {{ csrf_field() }}
                        <div class="form-group col-md-12">
                            <label>Name</label>
                            <input type="text" name="companyname" class="form-control mb-3" placeholder="Enter Bank Name" required="">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Website</label>
                            <input type="text" name="website" class="form-control mb-3" placeholder="Enter Bank Name" required="">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Senderid</label>
                            <input type="text" name="senderid" class="form-control mb-3" placeholder="Enter Sms Senderid">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Smsuser</label>
                            <input type="text" name="smsuser" class="form-control mb-3" placeholder="Enter Sms Username">
                        </div>
                        <div class="form-group col-md-12">
                            <label>Smspwd</label>
                            <input type="text" name="smspwd" class="form-control mb-3" placeholder="Enter Sms Password">
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Close
                    </button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection
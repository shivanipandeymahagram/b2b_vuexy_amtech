@extends('layouts.app')
@section('title', 'Roles')
@section('pagetitle', 'Roles')


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
                        <a href="" class="btn btn-success text-white ms-4" data-bs-toggle="modal" data-bs-target="#addModal">
                            <i class="ti ti-plus ti-xs"></i> Add New</a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th> Name</th>
                            <th>Display Name</th>
                            <th>Last Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>50</td>
                            <td>investment_fund_request</td>
                            <td>fund</td>
                            <td>27 May 19 - 03:36 PM</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        More
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editModal" type="button">Edit</button>
                                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#schemeModal">Scheme</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#permissionModal">Permission</a></li>
                                        </li>
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


<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Add Role</h3>
                </div>

                <form id="setupManager" action="{{route('roles')}}">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="bank">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Role Name</label>
                                <input type="text" name="name" class="form-control my-1" placeholder="Enter Role Name" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Display Name</label>
                                <input type="text" name="account" class="form-control my-1" placeholder="Enter Display Name" required="">
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
                    <h3 class="mb-2">Edit Role</h3>
                </div>

                <form id="setupManager" action="{{route('apimanager')}}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-6 my-1">
                                <label>Role Name</label>
                                <input type="text" name="name" class="form-control my-1" placeholder="Enter Role Name" required="" value="psm">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Display Name</label>
                                <input type="text" name="account" class="form-control my-1" placeholder="Enter Display Name" required="" value="PSM">
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

<div class="modal fade" id="schemeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Scheme Manager</h3>
                </div>

                <form id="setupManager" action="{{route('apimanager')}}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12 my-1">
                                <label>Scheme</label>
                                <select class="form-control my-1">
                                    <option value="">Select Scheme</option>
                                    <option value="">1136</option>
                                    <option value="">Special</option>
                                </select>
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

<div class="modal fade" id="permissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content">
            <form action="{{route('roles')}}" id="roles">
                <div class="modal-body">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="text-center mb-3">
                        <h3 class="mb-2">Member Permission</h3>
                    </div>

                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Section Category</th>
                                    <th>Permissions</th>
                                    <th>
                                        <div class="md-checkbox">
                                            <input type="checkbox" id="selectall">
                                            <label for="selectall">Select All</label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="md-checkbox mymd">
                                            <input type="checkbox" class="selectall" id="resource">
                                            <label for="resource"> Resource</label>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="md-checkbox col-md-4 p-0">
                                            <input type="checkbox" class="case" id="1" name="permissions[]" value="1">
                                            <label for="1">Change Company Profile</label>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="md-checkbox col-md-4 p-0">
                                            <input type="checkbox" class="case" id="1" name="permissions[]" value="2">
                                            <label for="2">View Commission</label>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
@extends('layouts.app')
@section('title', 'Api Manager')
@section('pagetitle', 'Api Manager')


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
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Display Name</th>
                            <th>Api Code</th>
                            <th>Credentials</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>50</td>
                            <td>ambikamultiservices Recharge</td>
                            <td>ambikamultiservices Recharge</td>
                            <td>recharge5</td>
                            <td>Api Credentials</td>
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


<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">@yield('pagetitle')</h3>
                </div>

                <form id="setupManager" action="{{route('apimanager')}}">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="api">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Product Name</label>
                                <input type="text" name="product" class="form-control my-1" placeholder="Enter value" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Display Name</label>
                                <input type="text" name="name" class="form-control my-1" placeholder="Enter value" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Url</label>
                                <input type="text" name="url" class="form-control my-1" placeholder="Enter url">
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control my-1" placeholder="Enter Value">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Password</label>
                                <input type="text" name="password" class="form-control my-1" placeholder="Enter url">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Optional1</label>
                                <input type="text" name="optional1" class="form-control my-1" placeholder="Enter Value">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Api Code</label>
                                <input type="text" name="code" class="form-control my-1" placeholder="Enter url" required="">
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label>Product Type</label>
                                <select name="type" class="form-control my-1" required>
                                    <option value="">Select Type</option>
                                    <option value="recharge">Recharge</option>
                                    <option value="bill">Bill Payment</option>
                                    <option value="money">Money transfer</option>
                                    <option value="pancard">Pancard</option>
                                    <option value="fund">Fund</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Commission Type</label>
                                <select name="commissiontype" class="form-control my-1" required>
                                    <option value="">Select Type</option>
                                    <option value="percent">Percent</option>
                                    <option value="flat">Flat</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label>Commission/Charge</label>
                                <input type="text" name="commissionCharge" class="form-control my-1" placeholder="Commission or Charge" required="">
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
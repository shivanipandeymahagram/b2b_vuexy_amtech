@extends('layouts.app')
@section('title', 'Money Transfer')
@section('pagetitle', 'Money Transfer')


@section('content')

<div class="row">
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle')</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 my-1">
                        <label>Mobile Number</label>
                        <input type="number" placeholder="Enter Mobile Number" name="mobile" class="form-control my-2" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Search</button>
            </div>
        </div>

    </div>
    <div class="col-8 col-xl-8 col-sm-8 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>Beneficiary List </span>
                    </h5>
                </div>
            </div>
            <div class="card-body mb-5">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>Name</th>
                            <th>Account Details</th>
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


@endsection
@extends('layouts.app')
@section('title', 'Portal Setting')
@section('pagetitle', 'Portal Setting')


@section('content')

<div class="row">
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>Wallet Settlement Type</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Settlement Type</label>
                        <select name="commissiontype" class="form-control my-1" required>
                            <option value="">Select Type</option>
                            <option value="auto">Auto</option>
                            <option value="mannual">Mannual</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

            </div>
        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>Bank Settlement Type</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Commission Type</label>
                        <select name="commissiontype" class="form-control my-1" required>
                            <option value="">Select Type</option>
                            <option value="auto">Auto</option>
                            <option value="mannual">Mannual</option>
                            <option value="down">Down</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

            </div>
        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>login with OTP
                        </span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Login Type</label>
                        <select name="commissiontype" class="form-control my-1" required>
                            <option value="">Select Type</option>
                            <option value="withoutotp">Without OTP</option>
                            <option value="withotp">With OTP</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

            </div>
        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>login with OTP
                        </span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Login Type</label>
                        <select name="commissiontype" class="form-control my-1" required>
                            <option value="">Select Type</option>
                            <option value="withoutotp">Without OTP</option>
                            <option value="withotp">With OTP</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

            </div>
        </div>

    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>login with OTP
                        </span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <label>Login Type</label>
                        <select name="commissiontype" class="form-control my-1" required>
                            <option value="">Select Type</option>
                            <option value="withoutotp">Without OTP</option>
                            <option value="withotp">With OTP</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

            </div>
        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>Bank Settlement Charge Upto 25000
                        </span>
                    </h5>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 ">
                        <label>Charge</label>
                        <input class="form-control" name="charge" placeholder="Charge" />
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

            </div>
        </div>

    </div>
</div>


@endsection
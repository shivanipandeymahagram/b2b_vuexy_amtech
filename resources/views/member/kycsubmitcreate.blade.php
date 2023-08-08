@extends('layouts.app')
@section('title', ' Create Kycsubmit')
@section('pagetitle', ' Create Kycsubmit')
@section('content')

<div class="row">
    <div class="col-lg-12 ">
        <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                    <h5 class="mb-0">
                        <h4>Personal Information</h4>
                    </h5>
                </div>
            </div>

            <div class="card-body">
                <div class=" rounded mt-5">
                    <form class="memberForm" action="{{ route('create') }}" method="post">
                        {{ csrf_field() }}
                        <div class="row">

                            <div class="col-md-12 my-1">
                                <h5>Member Type Information</h5>
                                <div class="row">
                                    <div class="form-group col-md-6 my-1">
                                        <label>Member Type</label>
                                        <select name="role_id" class="form-control select my-1" required="">
                                            <option value="">Select Role</option>
                                            <option value="1">Admin</option>
                                            <option value="2">Retailer</option>
                                            <option value="3">User</option>

                                        </select>
                                    </div>
                                    <div class="form-group col-md-6 my-1">
                                        <label>Scheme</label>
                                        <select name="scheme_id" class="form-control my-1" required="">
                                            <option value="">Select Scheme</option>

                                            <option value="1136">1136</option>
                                            <option value="Special">Special</option>

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="role_id" value="1">

                            <div class="form-group col-md-6 my-1">
                                <label for="fname">Name : <span class="text-danger fw-bold h6">*</span></label>
                                <input type="text" name="name" class="form-control my-1" id="fname" placeholder="First Name">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label for="lname">Mobile : <span class="text-danger fw-bold h6">*</span></label>
                                <input type="number" name="mobile" class="form-control my-1" id="lname" placeholder="Mobile Number">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label for="add1">Email : <span class="text-danger fw-bold h6">*</span></label>
                                <input type="email" name="email" class="form-control my-1" id="add1" placeholder="Email Address">
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label for="cname">State : <span class="text-danger fw-bold h6">*</span></label>
                                <select name="state" class="form-control my-1" required="">
                                    <option value="">Select State</option>

                                    <option value="Uttar Pradesh">Uttar Pradesh</option>
                                    <option value="MP">MP</option>
                                    <option value="Uttrakhand">Uttrakhand</option>
                                    <option value="Assam">Assam</option>
                                    <option value="Bihar">Bihar</option>

                                </select>
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label for="add2">Address : <span class="text-danger fw-bold h6">*</span></label>
                                <input type="text" name="address" class="form-control my-1" id="add2" placeholder="Address">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>City : <span class="text-danger fw-bold h6">*</span></label>
                                <input type="text" name="city" class="form-control my-1" value="" required="" placeholder="City">
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label>Pin Code : <span class="text-danger fw-bold h6">*</span></label>
                                <input type="number" name="pincode" class="form-control my-1" value="" required="" maxlength="6" minlength="6" placeholder="PinCode">

                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Shop Name : <span class="text-danger fw-bold h6">*</span></label>
                                <input type="text" name="shopname" class="form-control my-1" value="" required="" placeholder="Shop Name">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>PAN Card Number : <span class="text-danger fw-bold h6">*</span></label>
                                <input type="text" id="pancard" name="pancard" class="form-control my-1" value="" required="" placeholder="Pan card">

                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Aadhaar Card Number : <span class="text-danger fw-bold h6">*</span></label>
                                <input type="text" name="aadharcard" id="aadharcard" class="form-control my-1" value="" required="" placeholder="Aadhar Card Number" maxlength="12" minlength="12">

                            </div>

                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Add New User</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


</div>

@endsection
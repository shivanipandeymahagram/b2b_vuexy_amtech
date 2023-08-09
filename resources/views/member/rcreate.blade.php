@extends('layouts.app')
@section('title', 'Create Member')
@section('pagetitle', 'Create Member')
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
                    <form class="memberForm" action="{{ route('rcreate') }}">
                        {{ csrf_field() }}
                        <div class="row">

                            <!-- <div class="col-md-12">
                                <h5>Member Type Information</h5>
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label>Mamber Type</label>
                                        <select name="role_id" class="form-control select" required="">
                                            <option value="">Select Role</option>
                                            <option value="1">Admin</option>
                                            <option value="2">Retailer</option>
                                            <option value="3">User</option>

                                        </select>
                                    </div>
                                </div>
                            </div> -->

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
                                    <option value="Assam">Assam</option>
                                    <option value="Bihar">Bihar</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="Canada">Canada</option>
                                    <option value="China">China</option>
                                    <option value="France">France</option>
                                    <option value="Germany">Germany</option>
                                    <option value="India">India</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Korea">Korea, Republic of</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Philippines">Philippines</option>
                                    <option value="Russia">Russian Federation</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Emirates">United Arab Emirates</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="United States">United States</option>

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
                            <div class="form-group col-md-6 my-1">
                                <label>Scheme</label>
                                <select name="scheme_id" class="form-control my-1" required="">
                                    <option value="">Select Scheme</option>

                                    <option value="1136">1136</option>
                                    <option value="Special">Special</option>

                                </select>
                            </div>

                        </div>
                        <hr>


                        <h5 class="mb-3">Upload Your Documents</h5>
                        <div class="row">
                            <div class="form-group col-md-4 my-1">
                                <label>Passport size photo <span class="text-danger fw-bold">*</span></label>

                                <input type="file" class="form-control my-1" autocomplete="off" name="profiles" placeholder="Enter Demat account" required onchange="readURL(this);">

                            </div>

                            <div class="form-group col-md-4 my-1">
                                <label>Pancard Photo <span class="text-danger fw-bold">*</span></label>
                                <input type="file" class="form-control my-1" autocomplete="off" name="pancardpics" placeholder="Enter Business saving account" required>
                            </div>
                            <div class="form-group col-md-4 my-1">
                                <label>Aadharcard Front Photo <span class="text-danger fw-bold">*</span></label>
                                <input type="file" class="form-control my-1" autocomplete="off" name="aadharcardpics" placeholder="Enter Digital saving account" value="" required>
                            </div>

                            <div class="form-group col-md-4 my-1">
                                <label>Aadharcard Back Photo <span class="text-danger fw-bold">*</span></label>
                                <input type="file" class="form-control my-1" autocomplete="off" name="aadharcardpicsback" placeholder="Enter Digital saving account" value="" required>
                            </div>

                            <div class="form-group col-md-4 my-1">
                                <label>Shop photo <span class="text-danger fw-bold">*</span></label>
                                <input type="file" class="form-control my-1" autocomplete="off" name="shop_photos" placeholder="Enter Demat account" required>
                            </div>

                            <div class="form-group col-md-4 my-1">
                                <label>Live Photo <span class="text-danger fw-bold">*</span></label>
                                <input type="file" class="form-control my-1" autocomplete="off" name="livepics" placeholder="Enter Digital commodity account" required>
                            </div>
                            <div class="form-group col-md-4 my-1">
                                <label>Signature <span class="text-danger fw-bold">*</span></label>
                                <input type="file" class="form-control my-1" autocomplete="off" name="signatures" placeholder="Enter Business saving account" required>
                            </div>
                            <div class="form-group col-md-4 my-1">
                                <label>Declaration <span class="text-danger fw-bold">*</span></label>
                                <input type="file" class="form-control my-1" autocomplete="off" name="declaration" placeholder="Enter Demat account" required>
                            </div>
                            <div class="form-group col-md-4 my-1">
                                <label>Employee photo <span class="text-danger fw-bold">*</span></label>
                                <input type="file" class="form-control my-1" autocomplete="off" name="agntpics" placeholder="Enter Demat account" required>
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
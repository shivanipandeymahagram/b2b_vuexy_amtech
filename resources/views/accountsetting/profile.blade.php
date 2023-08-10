@extends('layouts.app')
@section('title', "Profile")
@section('pagetitle', "Profile")
@section('bodyClass', "has-detached-left")

@section('content')

<div class="row">
    <div class="col-lg-12 ">
        <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                    <h5 class="mb-0">
                        <h4>My Profile</h4>
                    </h5>
                </div>
            </div>

            <div class="card-body">
                <div class=" rounded mt-5">
                    <div class="row gap-4 gap-sm-0">
                        <div class="">
                            <ul class="nav nav-tabs nav-pills" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="true">
                                        Profile Details
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-kyc" aria-controls="navs-justified-kyc" aria-selected="false">
                                        KYC Details
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-password" aria-controls="navs-justified-password" aria-selected="false">
                                        Password Manager
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-pin" aria-controls="navs-justified-pin" aria-selected="false">
                                        Pin Manager
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-bank" aria-controls="navs-justified-bank" aria-selected="false">
                                        Bank Details
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-role" aria-controls="navs-justified-role" aria-selected="false">
                                        Role Manager
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-mapping" aria-controls="navs-justified-mapping" aria-selected="false">
                                        Mapping Manager
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="navs-justified-profile" role="tabpanel">
                                    <form id="profileForm" action="{{route('profile')}}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="1">
                                        <input type="hidden" name="actiontype" value="profile">

                                        <div class="row">
                                            <div class="form-group col-md-4 my-1">
                                                <label>Name</label>
                                                <input type="text" name="name" class="form-control my-1" value="Shivi" required="" placeholder="Enter Value">
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>Mobile</label>
                                                <input type="number" required="" value="9876543212" class="form-control my-1" placeholder="Enter Value">
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>Email</label>
                                                <input type="email" name="email" class="form-control my-1" value="abc@gmail.com" required="" placeholder="Enter Value">
                                            </div>

                                            <div class="form-group col-md-4 my-1">
                                                <label>State</label>
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
                                            <div class="form-group col-md-4 my-1">
                                                <label>City</label>
                                                <input type="text" name="city" class="form-control my-1" value="Lucknow" required="" placeholder="Enter Value">
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>PIN Code</label>
                                                <input type="number" name="pincode" class="form-control my-1" value="1234566" required="" maxlength="6" minlength="6" placeholder="Enter Value">
                                            </div>

                                            <div class="form-group col-md-4 my-1">
                                                <label>Gender</label>
                                                <select name="gender" class="form-control my-1" required="">
                                                    <option value="">Select Gender</option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>Address</label>
                                                <input type="text" name="address" class="form-control my-1" rows="3" required="" placeholder="Enter Value" value="Lucknow"></input>
                                            </div>

                                            <div class="form-group col-md-4 my-1">
                                                <label>Security PIN</label>
                                                <input type="password" name="mpin" autocomplete="off" class="form-control my-1" required="">
                                            </div>


                                            <div class="col-sm-12">
                                                <button class="btn btn-primary pull-right  mt-2" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Profile</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade " id="navs-justified-kyc" role="tabpanel">
                                    <form id="kycForm" action="{{route('profile')}}" method="post" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="1">
                                        <input type="hidden" name="actiontype" value="profile">
                                        <div class="row">
                                            <div class="form-group col-md-4 my-1">
                                                <label>Shop Name</label>
                                                <input type="text" name="shopname" class="form-control my-1" value="xxxxxxx" required="" placeholder="Enter Value">
                                            </div>

                                            <div class="form-group col-md-4 my-1">
                                                <label>GST Number</label>
                                                <input type="text" name="gstin" class="form-control my-1" value="xxxxx" placeholder="Enter Value">
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>Aadhaar Card Number</label>
                                                <input type="text" name="aadharcard" class="form-control my-1" value="xxxxxxxxxxxxxxxxxx" required="" placeholder="Enter Value" maxlength="12" minlength="12">
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>PAN Card Number</label>
                                                <input type="text" name="pancard" class="form-control my-1" value="xxxxxxxxxxx" required="" placeholder="Enter Value">
                                            </div>

                                            <div class="form-group col-md-4 my-1">
                                                <label>PAN Card Picture</label>
                                                <input type="file" name="pancardpics" class="form-control my-1" value="" placeholder="Enter Value">
                                            </div>

                                            <div class="form-group col-md-4 my-1">
                                                <label>Aadhaar Card Picture</label>
                                                <input type="file" name="aadharcardpics" class="form-control my-1" value="" placeholder="Enter Value">
                                            </div>

                                            <div class="form-group col-md-4 my-1">
                                                <label>Security PIN</label>
                                                <input type="password" name="mpin" autocomplete="off" class="form-control my-1" required="">
                                            </div>

                                            <div class="col-sm-12">
                                                <button class="btn btn-primary mt-2 pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Profile</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade " id="navs-justified-password" role="tabpanel">
                                    <form id="passwordForm" action="{{route('profile')}}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="1">
                                        <input type="hidden" name="actiontype" value="password">
                                        <div class="panel panel-default">

                                            <div class="panel-body p-b-0">
                                                <div class="row">
                                                    <div class="form-group col-md-4 my-1">
                                                        <label>Old Password</label>
                                                        <input type="password" name="oldpassword" class="form-control my-1" required="" placeholder="Enter Value">
                                                    </div>

                                                    <div class="form-group col-md-4 my-1">
                                                        <label>New Password</label>
                                                        <input type="password" name="password" id="password" class="form-control my-1" required="" placeholder="Enter Value">
                                                    </div>
                                                    <div class="form-group col-md-4 my-1">
                                                        <label>Confirmed Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control my-1" required="" placeholder="Enter Value">
                                                    </div>

                                                    <div class="form-group col-md-4 my-1">
                                                        <label>Security PIN</label>
                                                        <input type="password" name="mpin" autocomplete="off" class="form-control my-1" required="">
                                                    </div>

                                                    <div class="col-sm-12">
                                                        <button class="btn btn-primary mt-2 pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Resetting...">Password Reset</button>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="navs-justified-pin" role="tabpanel">
                                    <form id="pinForm" action="{{route('profile')}}" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="1">
                                        <input type="hidden" name="mobile" value="1234567890">
                                        <div class="row">
                                            <div class="form-group col-md-4 my-1">
                                                <label>New PIN</label>
                                                <input type="password" name="pin" id="pin" class="form-control my-1" required="" placeholder="Enter Value">
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>Confirmed PIN</label>
                                                <input type="password" name="pin_confirmation" class="form-control my-1" required="" placeholder="Enter Value">
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>OTP</label>
                                                <input type="password" name="otp" class="form-control my-1" Placeholder="Otp" required>
                                                <a href="javascript:void(0)" onclick="OTPRESEND()" class="text-primary pull-right fw-bloder">Send OTP</a>
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button class="btn btn-primary mt-2 float-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Resetting...">Password Reset</button>

                                            </div>
                                        </div>
                                    </form>

                                </div>

                                <div class="tab-pane fade " id="navs-justified-bank" role="tabpanel">
                                    <form id="bankForm" action="{{route('profile')}}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="1">
                                        <input type="hidden" name="actiontype" value="bankdata">
                                        <div class="row">
                                            <div class="form-group col-md-4 my-1">
                                                <label>Account Number</label>
                                                <input type="text" name="account" class="form-control my-1" value="xxxxxxxxxxxxxxxxxxx" required="" placeholder="Enter Value">
                                            </div>

                                            <div class="form-group col-md-4 my-1">
                                                <label>Bank Name</label>
                                                <input type="text" name="bank" class="form-control my-1" value="xxxxxxxxx" placeholder="Enter Value">
                                            </div>

                                            <div class="form-group col-md-4 my-1">
                                                <label>IFSC Code</label>
                                                <input type="text" name="ifsc" class="form-control my-1" value="xxxxxxxxxxx" required="" placeholder="Enter Value">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-4 my-1">
                                                <label>Security PIN</label>
                                                <input type="password" name="mpin" autocomplete="off" class="form-control my-1" required="">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button class="btn btn-primary mt-2 pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Changing...">Change</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade my-1" id="navs-justified-role" role="tabpanel">
                                    <form id="roleForm" action="{{route('profile')}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="1">
                                        <input type="hidden" name="actiontype" value="rolemanager">
                                        <div class="row">
                                            <div class="form-group col-md-4 my-1">
                                                <label>Member Role</label>
                                                <select name="role_id" class="form-control my-1" required="">
                                                    <option value="">Select Role</option>
                                                    <option value="1">Whitelabel</option>
                                                    <option value="1">Retailer</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>Security PIN</label>
                                                <input type="password" name="mpin" autocomplete="off" class="form-control my-1" required="">
                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button class="btn btn-primary mt-2 pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Changing...">Change</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade my-1" id="navs-justified-mapping" role="tabpanel">
                                    <form id="memberForm" action="{{route('profile')}}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="id" value="1">
                                        <input type="hidden" name="actiontype" value="mapping">
                                        <div class="row">
                                            <div class="form-group col-md-4 my-1">
                                                <label>Parent Member</label>
                                                <select name="parent_id" class="form-control my-1" required="">
                                                    <option value="">Select Member</option>
                                                    <option value="1">Whitelabel (9876543210) (Whitelabel)</option>
                                                    <option value="1">Retailer (9876543210) (Retailer)</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4 my-1">
                                                <label>Security PIN</label>
                                                <input type="password" name="mpin" autocomplete="off" class="form-control my-1" required="">
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <button class="btn btn-primary mt-2 pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Changing...">Change</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>

@endsection

@push('style')
<style>
    .dropzone {
        min-height: 127px;
    }

    .dropzone .dz-default.dz-message:before {
        font-size: 50px;
        top: 60px;
    }

    .dropzone .dz-default.dz-message span {
        font-size: 18px;
        margin-top: 100px;
    }
</style>
@endpush
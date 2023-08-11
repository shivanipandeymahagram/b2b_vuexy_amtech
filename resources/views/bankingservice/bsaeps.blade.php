@extends('layouts.app')
@section('title', 'Aeps Service')
@section('pagetitle', 'Aeps Service')


@section('content')

@include('layouts.pageheader')
<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle') Registration</span>
                    </h5>
                </div>
            </div>
            <div class="card-body">

                <form action="{{route('bsaeps')}}" method="post" id="transactionForm">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-4 my-1">
                            <label>Firstname <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" autocomplete="off" name="bc_f_name" placeholder="Enter Your Firstame" value="Shivani" required>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>Lastname <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="bc_l_name" autocomplete="off" placeholder="Enter Your Lastname" value="Pandey" required>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>Email <span class="text-danger fw-bold">*</span></label>
                            <input type="email" class="form-control my-1" autocomplete="off" name="emailid" placeholder="Enter Your Email" value="abc@gmail.com" required>
                        </div>
                    </div>

                    <div class="row">


                        <div class="form-group col-md-4 my-1">
                            <label>Mobile <span class="text-danger fw-bold">*</span></label>
                            <input type="text" pattern="[0-9]*" maxlength="10" minlength="10" class="form-control my-1" name="phone1" autocomplete="off" placeholder="Enter Your Mobile" value="9876665454" required>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>Alternate Mobile <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" autocomplete="off" name="phone2" pattern="[0-9]*" maxlength="10" minlength="10" placeholder="Enter Your Alternate Mobile">
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>DOB <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control mydatepic" autocomplete="off" name="bc_dob" placeholder="Enter Your DOB (DD-MM-YYYY)" required>
                        </div>
                    </div>

                    <div class="row">


                        <div class="form-group col-md-4 my-1">
                            <label>State <span class="text-danger fw-bold">*</span></label>
                            <select name="bc_state" class="form-control my-1" required>
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
                            <label>District <span class="text-danger fw-bold">*</span></label>
                            <select name="bc_district" class="form-control my-1" required>

                            </select>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>Address <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" autocomplete="off" name="bc_address" placeholder="Enter Your Address" value="Lucknow" required>
                        </div>
                    </div>

                    <div class="row">


                        <div class="form-group col-md-4 my-1">
                            <label>Block <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="bc_block" autocomplete="off" placeholder="Enter Your Block" required>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>City <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" autocomplete="off" name="bc_city" value="Lucknow" placeholder="Enter Your City" required>
                        </div>
                        <div class="form-group col-md-4 my-1">
                            <label>Landmark <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" autocomplete="off" name="bc_landmark" placeholder="Enter Your Landmark" required>
                        </div>
                    </div>

                    <div class="row">


                        <div class="form-group col-md-4 my-1">
                            <label>Mohalla <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="bc_mohhalla" autocomplete="off" placeholder="Enter Your Mohhalla" required>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>Location <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" autocomplete="off" name="bc_loc" placeholder="Enter Your Location" required>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>PIN Code <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" autocomplete="off" name="bc_pincode" placeholder="Enter Your Pincode" pattern="[0-9]*" value="123456" maxlength="6" minlength="6" required>
                        </div>
                    </div>

                    <div class="row">


                        <div class="form-group col-md-4 my-1">
                            <label>PAN Card <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="bc_pan" autocomplete="off" placeholder="Enter Your Pancard" value="ABCDF2324" required>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>Shop Name <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" autocomplete="off" name="shopname" value="Fund" placeholder="Enter Your Shopname" required>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>Shop Type <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" autocomplete="off" name="shopType" placeholder="Enter Your Shop type" required>
                        </div>
                    </div>

                    <div class="row">


                        <div class="form-group col-md-4 my-1">
                            <label>Qualification <span class="text-danger fw-bold">*</span></label>
                            <select name="qualification" class="form-control my-1">
                                <option value="SSC">SSC</option>
                                <option value="HSC">HSC</option>
                                <option value="Graduate">Graduate</option>
                                <option value="Post Graduate">Post Graduate</option>
                                <option value="Diploma">Diploma</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>Population <span class="text-danger fw-bold">*</span></label>
                            <select name="population" class="form-control my-1">
                                <option value="0 to 2000">0 to 2000</option>
                                <option value="2000 to 5000">2000 to 5000</option>
                                <option value="5000 to 10000">5000 to 10000</option>
                                <option value="10000 to 50000">10000 to 50000</option>
                                <option value="50000 to 100000">50000 to 100000</option>
                            </select>
                        </div>

                        <div class="form-group col-md-4 my-1">
                            <label>Location Type <span class="text-danger fw-bold">*</span></label>
                            <select name="locationType" class="form-control my-1">
                                <option value="Rural">Rural</option>
                                <option value="Urban">Urban</option>
                                <option value="Metro Semi Urban">Metro Semi Urban</option>
                            </select>
                        </div>
                    </div>


                    <div class="card-footer">
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-primary" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting"><b><i class=" icon-paperplane"></i></b> Submit</button>
                    </div>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>

@endsection
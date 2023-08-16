@extends('layouts.app')
@section('title', "Loan Enquiry Form")
@section('pagetitle', "Loan Enquiry Form")


@section('content')
<div class="content">


    <div class="row">
        <div class="col-sm-12 iq-card p-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">Loan Enquiry Form</h4>
                </div>
                <div class="panel-body">
                    <form action="{{route('loanformstore')}}" method="post" id="loanform">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Amount <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" autocomplete="off" name="loanamount" placeholder="Enter Your Amount" value="" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Mobile <span class="text-danger">*</span></label>
                                <input type="text" pattern="[0-9]*" maxlength="10" minlength="10" class="form-control" name="mobile" autocomplete="off" placeholder="Enter Your Mobile" value="" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" autocomplete="off" name="email" placeholder="Enter Your Email" value="" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Customer Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" autocomplete="off" name="c_name" placeholder="Enter Your Name" value="" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>PAN Card <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pan" autocomplete="off" placeholder="Enter Your Pancard" value="" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Adhar Card <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="adhar" autocomplete="off" placeholder="Enter Your Adhar number" value="" pattern="[0-9]*" maxlength="12" minlength="12">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Address <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" autocomplete="off" name="address" placeholder="Enter Your Address" value="" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Pincode <span class="text-danger">*</span></label>
                                <input type="text" name="pincode" class="form-control" value="" required="" maxlength="6" minlength="6" placeholder="Enter Your 6 digit pincode" pattern="[0-9]*">

                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>City <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" autocomplete="off" name="city" value="" placeholder="Enter Your City" required>
                            </div>
                            <!--<div class="form-group col-md-6">-->
                            <!--    <label>State </label>-->
                            <!--    <input type="text" class="form-control" autocomplete="off" name="state" placeholder="Enter Your Landmark" required>-->
                            <!--</div>-->

                            <div class="form-group col-md-6 form-select-lg">
                                <label>State <span class="text-danger">*</span></label>
                                <select name="state" class="form-control select" required>
                                    <option value="">Select State</option>
                                    @foreach ($mahastate as $state)
                                    <option value="{{$state->state}}">{{$state-> state}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            
                            <div class="form-group col-md-6">
                                <label>Remarks <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" autocomplete="off" name="remark" value="" placeholder="Enter remark" required>
                            </div>

                            <div class="form-group col-md-6">
                                <label>Reference <span class="text-danger">*</span></label>
                                <select name="refby" class="form-control select" required>
                                    <option value="">Select Agent</option>
                                    @foreach ($agents as $agent)
                                    <option value="{{$agent->id}}"> {{$agent->name ."(" .$agent->mobile.")"}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>Loan Type <span class="text-danger">*</span></label>
                                <select name="loantype" class="form-control select" required>
                                    <option value="">Select load</option>

                                    <option value="Personal_loan">Personal Loan</option>
                                    <option value="Business_loan">Business Loan</option>
                                    <option value="Home_loan">Home Loan</option>
                                    <option value="Loan_Against_Property">Loan Against Property</option>
                                    <option value="Car_loan">Car Loan</option>
                                    <option value="Life_Insurance">Life Insurance</option>
                                    <option value="Motor_Insurance">Motor Insurance</option>
                                    <option value="Health_Insurance">Health Insurance</option>
                                    <option value="Health_Products">Health Products</option>
                                    <option value="Credit_card">Credit Card</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Earning Type <span class="text-danger">*</span></label>
                                <select name="earningtype" class="form-control select" required>
                                    <option value="">Select type</option>
                                    <option value="saleried">Saleried</option>
                                    <option value="SelfEmployed">Self Employed</option>
                                </select>
                            </div>
                            
                        </div>



                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-primary bg-teal-400 btn-labeled btn-rounded legitRipple btn-lg" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Submitting"><b><i class=" icon-paperplane"></i></b> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        $("#loanform").validate({
            rules: {
                adhar: {
                    required: true,
                    number: true,
                }
            },
            messages: {
                adhar: {
                    required: "Please Enter Adhar",
                    number: "Adhar number should be numeric",
                }
            },
            errorElement: "p",
            errorPlacement: function(error, element) {
                if (element.prop("tagName").toLowerCase() === "select") {
                    error.insertAfter(element.closest(".form-group").find(".select2"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                var form = $('#loanform');
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        swal({
                            title: 'Wait!',
                            text: 'We are working on your request',
                            onOpen: () => {
                                swal.showLoading()
                            },
                            allowOutsideClick: () => !swal.isLoading()
                        });
                    },
                    success: function(data) {
                        swal.close();
                        if (data.status == "TXN") {
                            swal({
                                type: 'success',
                                title: 'Welcome',
                                text: 'Your request has been submitted successfully',
                                showConfirmButton: true
                            });
                            $('#loanform').find('form')[0].reset();
                        } else {
                            notify(data.message, 'warning');
                        }
                    },
                    error: function(errors) {
                        swal.close();
                        if (errors.status == '422') {
                            // notify(errors.responseJSON.errors[0], 'warning');
                        } else {
                            swal("Oh No!", "Something went wrong, try again later!", "error");
                            //  notify('Something went wrong, try again later.', 'warning');
                        }
                    }
                });
            }
        });
    });
</script>
@endpush
@extends('layouts.app')
@section('title', "Money Transfer")
@section('pagetitle', "Money Transfer")
@php
$table = "yes";
@endphp

@section('content')
<div class="content">
    <div class="row">

        <div class="col-sm-4">
            <div class="card mb-3">

                <div class="card-body">
                    <h4 class="card-title">Money Transfer</h4>
                    <form id="serachForm" action="{{route('dmt1pay')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="verification">
                        <input type="hidden" id="rname">
                        <input type="hidden" id="rlimit">
                        <div class="panel-body">
                            <div class="form-group my-1 no-margin-bottom">
                                <label>Mobile Number</label>
                                <input type="number" step="any" name="mobile" class="form-control my-1" placeholder="Enter Mobile Number" required="">
                            </div>
                        </div>
                        <div class="panel-footer text-center mt-4">
                            <button type="submit" class="btn btn-primary" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i class="icon-search4"></i></b> Search</button>
                        </div>
                    </form>
                </div>
            </div>

        

            <div class="card userdetails" style="display:none">
                <div class="card-body">

                    <div class="row">
                        <div class="col-sm-6">
                            <a href="javascript:void(0)" class="text-default name"></a>
                            <h6 class="text-semibold no-margin-top mobile"></h6>
                            <ul class="list list-unstyled">
                                <li>Used Limit : <i class="fa fa-inr"></i> <span class="usedlimit"></span></li>
                            </ul>
                        </div>

                        <div class="col-sm-6">
                            <h6 class="text-semibold text-right no-margin-top">Total Limit : <i class="fa fa-inr"></i> <span class="totallimit"></span></h6>
                            <ul class="list list-unstyled text-right">
                                <li>Remain Limit: <i class="fa fa-inr"></i> <span class="text-semibold remainlimit"></span></li>
                            </ul>
                        </div>

                        <div class="col-sm-12 text-center">
                            <a href="#" data-toggle="modal" data-target="#beneficiaryModal" class="btn btn-primary">
                                <i class="icon-plus22 position-left"></i>
                                New Beneficiary
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>

        <div class="col-sm-8">
           

            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Beneficiary List</h4>
                    <div class="table-responsive">
                        <table class="table transaction">
                            <thead class="bg-light">
                                <th >Name</th>
                                <th >Account Details</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>

<div class="modal fade" id="beneficiaryModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Beneficiary</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('dmt1pay')}}" method="post" id="beneficiaryForm">
                <div class="modal-body">
                    {{ csrf_field() }}
                    <input type="hidden" name="rid">
                    <input type="hidden" name="type" value="addbeneficiary">
                    <input type="hidden" name="mobile">
                    <input type="hidden" name="name">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group my-1">
                                <label>Bank Name : </label>
                                <select id="bank" name="benebank" class="form-control my-1">
                                    <option value="">Select Bank</option>
                                    @foreach($banks as $bank)
                                    <option value="{{$bank->bankid}}" ifsc="{{$bank->masterifsc}}">{{$bank->bankname}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group my-1">
                                <label for="phone">IFSC Code:</label>
                                <input type="text" class="form-control my-1" name="beneifsc" placeholder="Bank ifsc code" required="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group my-1">
                                <label for="phone">Bank Account No.:</label>
                                <input type="text" class="form-control my-1" id="account" name="beneaccount" placeholder="Enter account no." required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group my-1">
                                <label for="phone">Beneficiary Mobile:</label>
                                <input type="text" class="form-control my-1" name="benemobile" placeholder="Enter name" required="">
                                <p></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group my-1">
                                <label for="phone">Beneficiary Name:</label>
                                <input type="text" class="form-control my-1" name="benename" placeholder="Enter name" required="">
                                <p></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-warning text-white" type="button" id="getBenename">Get Name</button>
                    <button class="btn btn-primary " type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="otpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">OTP Verification</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('dmt1pay')}}" method="post" id="otpForm">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="type" value="beneverify">
                    <input type="hidden" name="mobile">
                    <input type="hidden" name="beneaccount">
                    <input type="hidden" name="benemobile">
                    <div class="form-group my-1">
                        <label>OTP</label>
                        <input type="text" class="form-control my-1" name="otp" placeholder="enter otp" required>
                        <a href="javascript:void(0)" class="pull-right resendOtp" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Sending" type="resendOtpVerification"><i class='fa fa-paper-plane'></i> Resend Otp</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Money</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('dmt1pay')}}" method="post" id="transferForm">
                {{ csrf_field() }}
                <input type="hidden" name="type" value="transfer">
                <input type="hidden" name="mobile">
                <input type="hidden" name="name">
                <input type="hidden" name="benename">
                <input type="hidden" name="beneaccount">
                <input type="hidden" name="benebank">
                <input type="hidden" name="beneifsc">
                <input type="hidden" name="benemobile">
                <div class="modal-body">
                    <div class="panel border-left-lg border-left-success invoice-grid timeline-content">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h6 class="text-semibold no-margin-top ">Name - <span class="benename"></span></h6>
                                    <ul class="list list-unstyled">
                                        <li>Bank - <span class="benebank"></span></li>
                                    </ul>
                                </div>

                                <div class="col-sm-6">
                                    <h6 class="text-semibold text-right no-margin-top">Acc - <span class="beneaccount"></span></h6>
                                    <ul class="list list-unstyled text-right">
                                        <li>Ifsc - <span class="beneifsc"></span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group my-1">
                                <label>Amount</label>
                                <input type="number" class="form-control my-1" placeholder="Enter amount to be transfer" name="amount" step="any" required>
                            </div>
                        </div>
                        <div class="form-group my-1 col-md-12">
                            <label>T-Pin</label>
                            <input type="password" name="pin" class="form-control my-1" placeholder="Enter transaction pin" required="">
                            <a href="{{url('profile/view?tab=pinChange')}}" target="_blank" class="text-primary pull-right">Generate Or Forgot Pin??</a>
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


<div class="modal fade bd-example-modal-lg" id="receipt" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Receipt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="list-group transactionData p-0">
                </ul>
                <div id="receptTable">
                    <div class="clearfix">
                        <div class="pull-left">
                            <h4>Invoice</h4>
                        </div>
                    </div>
                    <hr class="m-t-10 m-b-10">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="pull-left m-t-10">
                                <address class="m-b-10">
                                    Agent : <strong class="username">{{Auth::user()->name}}</strong><br>
                                    Shop Name : <span class="company">{{Auth::user()->shopname}}</span><br>
                                    Phone : <span class="mobile">{{Auth::user()->mobile}}</span>
                                </address>
                            </div>
                            <div class="pull-right m-t-10">
                                <address class="m-b-10">
                                    <strong>Date : </strong> <span class="date">{{date('d M y - h:i A')}}</span><br>
                                    <strong>Name : </strong> <span class="benename"></span><br>
                                    <strong>Account : </strong> <span class="beneaccount"></span><br>
                                    <strong>Bank : </strong> <span class="benebank"></span>
                                </address>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h4>Transaction Details :</h4>
                                <table class="table m-t-10">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>Order Id</th>
                                            <th>Amount</th>
                                            <th>UTR No.</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="id"></td>
                                            <td class="amount"></td>
                                            <td class="refno"></td>
                                            <td class="status"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="border-radius: 0px;">
                        <div class="col-md-6 col-md-offset-6">
                            <h5 class="text-right">Transfer Amount : <span class="samount"></span></h5>
                        </div>
                    </div>
                    <p>* As per RBI guideline, maximum charges allowed is 2%.</p>
                    <hr>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button class="btn btn-secondary" type="button" id="print"><i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>
</div>

<div id="registrationModal" class="modal fade" role="dialog" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <h4 class="modal-title pull-left text-white">Member Registration</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form action="{{route('dmt1pay')}}" method="post" id="registrationForm">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="type" value="registration">
                    <input type="hidden" name="mobile">
                    <div class="row">
                        <div class="form-group my-1 col-md-6">
                            <label>First Name</label>
                            <input type="text" class="form-control my-1" name="fname" required="" placeholder="Enter last name">
                        </div>

                        <div class="form-group my-1 col-md-6">
                            <label>Last Name</label>
                            <input type="text" class="form-control my-1" name="lname" required="" placeholder="Enter first name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group my-1 col-md-6">
                            <label>Pincode</label>
                            <input type="text" class="form-control my-1" name="pincode" required="" placeholder="Enter Pincode">
                        </div>

                        <div class="form-group my-1 col-md-6">
                            <label>Otp</label>
                            <input type="text" class="form-control my-1" name="otp" required="" placeholder="Enter Pincode">
                            <a href="javascript:void(0)" class="pull-right resendOtp" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Sending" type="resendOtpVerification"><i class='fa fa-paper-plane'></i> Resend Otp</a>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-raised legitRipple" data-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn bg-slate btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div>

    </div>
</div>

<div class="modal fade" id="registrationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Remitter Registration</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('dmt1pay')}}" method="post" id="registrationForm">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="type" value="registration">
                    <input type="hidden" name="mobile">
                    <div class="row">
                        <div class="form-group my-1 col-md-6">
                            <label>First Name</label>
                            <input type="text" class="form-control my-1" name="fname" required="" placeholder="Enter last name">
                        </div>

                        <div class="form-group my-1 col-md-6">
                            <label>Last Name</label>
                            <input type="text" class="form-control my-1" name="lname" required="" placeholder="Enter first name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group my-1 col-md-6">
                            <label>Pincode</label>
                            <input type="text" class="form-control my-1" name="pincode" required="" placeholder="Enter Pincode">
                        </div>

                        <div class="form-group my-1 col-md-6">
                            <label>Otp</label>
                            <input type="text" class="form-control my-1" name="otp" required="" placeholder="Enter Pincode">
                            <a href="javascript:void(0)" class="pull-right resendOtp" data-loading-text="<i class='fa fa-spinner fa-spin'></i> Sending" type="resendOtpVerification"><i class='fa fa-paper-plane'></i> Resend Otp</a>
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

@endsection

@push('script')
<script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $("[name='mobile']").keyup(function() {
            $("#serachForm").submit();
        });

        $('#print').click(function() {
            $('#receptTable').print();
        });

        $('#bank').on('change', function(e) {
            $('input[name="beneifsc"]').val($(this).find('option:selected').attr('ifsc'));
        });

        $('a.resendOtp').click(function() {
            var mobile = $(this).closest('form').find('input[name="mobile"]').val();
            var button = $(this);
            var form = $(this).closest('form');
            $.ajax({
                url: "{{route('dmt1pay')}}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    'mobile': mobile,
                    'type': "otp"
                },
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        text: 'We are processing your request.',
                        allowOutsideClick: () => !swal.isLoading(),
                        onOpen: () => {
                            swal.showLoading()
                        }
                    });
                },
                success: function(data) {
                    swal.close();
                    if (result.statuscode == "TXN") {
                        notify(data.message, 'success', "inline", form);
                    } else {
                        notify(data.message, 'error', "inline", form);
                    }
                },
                error: function(error) {
                    swal.close();
                    notify("Something went wrong", 'error', "inline", form);
                }
            });
        });

        $("#serachForm").validate({
            rules: {
                mobile: {
                    required: true,
                    number: true,
                    minlength: 10,
                    maxlength: 10
                },
            },
            messages: {
                mobile: {
                    required: "Please enter mobile number",
                    number: "Mobile number should be numeric",
                    minlenght: "Mobile number length should be 10 digit",
                    maxlenght: "Mobile number length should be 10 digit",
                }
            },
            errorElement: "p",
            errorPlacement: function(error, element) {
                if (element.prop("tagName").toLowerCase() === "select") {
                    error.insertAfter(element.closest(".form-group my-1").find(".select2"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                var form = $('#serachForm');
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button[type="submit"]').button('loading');
                    },
                    success: function(data) {
                        form.find('button[type="submit"]').button('reset');
                        if (data.statuscode == "TXN") {
                            setVerifyData(data);
                            setBeneData(data);
                        } else if (data.statuscode == "RNF") {
                            var mobile = form.find('[name="mobile"]').val();
                            $('#registrationModal').find('[name="mobile"]').val(mobile);
                            $('#registrationModal').modal();
                        } else if (data.statuscode == "TXNOTP") {
                            var type = form.find('[name="type"]').val();
                            if (type == "registration" || type == "verification") {
                                $('#otpModal').find('[name="type"]').val("registrationValidate");
                            }
                            var mobile = form.find('[name="mobile"]').val();
                            $('#otpModal').find('[name="transid"]').val(data.transid);
                            $('#otpModal').find('[name="mobile"]').val(mobile);
                            $('#otpModal').modal();
                        } else {
                            notify(data.message, 'error', "inline", form);

                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $("#beneficiaryForm").validate({
            rules: {
                ifsc: {
                    required: true,
                },
                account: {
                    required: true,
                },
                account_confirmation: {
                    required: true,
                    equalTo: '#account'
                },
                name: {
                    required: true,
                }
            },
            messages: {
                ifsc: {
                    required: "Bank ifsc code is required",
                },
                account: {
                    required: "Beneficiary bank account number is required",
                },
                account_confirmation: {
                    required: "Account number confirmation is required",
                    equalTo: 'Account confirmation is same as account number'
                },
                name: {
                    required: "Beneficiary account name is required",
                }
            },
            errorElement: "p",
            errorPlacement: function(error, element) {
                if (element.prop("tagName").toLowerCase() === "select") {
                    error.insertAfter(element.closest(".form-group my-1").find(".select2"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                var form = $('#beneficiaryForm');
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button[type="submit"]').button('loading');
                    },
                    success: function(data) {
                        form.find('button[type="submit"]').button('reset');
                        if (data.statuscode == "TXN") {
                            form[0].reset();
                            form.find('select').select2().val(null).trigger('change');
                            form.closest('.modal').modal('hide');
                            notify('Beneficiary Successfully Added.', 'success');
                            $("#serachForm").submit();
                        } else {
                            notify(data.message, 'error', "inline", form);
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $("#otpForm").validate({
            rules: {
                otp: {
                    required: true,
                    number: true,
                },
            },
            messages: {
                otp: {
                    required: "Please enter otp number",
                    number: "Otp number should be numeric",
                }
            },
            errorElement: "p",
            errorPlacement: function(error, element) {
                if (element.prop("tagName").toLowerCase() === "select") {
                    error.insertAfter(element.closest(".form-group my-1").find(".select2"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                var form = $('#otpForm');
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button[type="submit"]').button('loading');
                    },
                    success: function(data) {
                        form.find('button[type="submit"]').button('reset');
                        if (data.statuscode == "TXN") {
                            var type = form.find('[name="type"]').val();
                            form[0].reset();
                            $('#otpModal').find('[name="mobile"]').val("");
                            $('#otpModal').find('[name="beneaccount"]').val("");
                            $('#otpModal').find('[name="benemobile"]').val("");
                            $('#otpModal').modal('hide');
                            if (type == "registrationValidate") {
                                notify('Member successfully registered.', 'success');
                            } else {
                                notify('Beneficiary Successfully verified.', 'success');
                            }
                            $("#serachForm").submit();
                        } else {
                            notify(data.message, 'error', "inline", form);
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $('#transferForm').submit(function(event) {
            var form = $('#transferForm');
            var amount = form.find('[name="amount"]').val();
            var benename = form.find('[name="benename"]').val();
            var beneaccount = form.find('[name="beneaccount"]').val();
            var benebank = form.find('[name="benebank"]').val();
            var bankname = form.find('.benebank').text();
            var beneifsc = form.find('[name="beneifsc"]').val();
            form.ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button[type="submit"]').button('loading');
                },
                success: function(data) {
                    form.find('button[type="submit"]').button('reset');
                    form[0].reset();
                    getbalance();
                    form.closest('.modal').modal('hide');
                    var samount = 0;
                    var out = "";
                    var tbody = '';
                    $.each(data.data, function(index, val) {
                        if (val.data.statuscode == "TXN" || val.data.statuscode == "TUP") {
                            samount += parseFloat(val.amount);
                            out += '<li class="list-group-item alert alert-success no-margin mb-10"><strong>Rs.  ' + val.amount + '</strong><span class="pull-right">' + val.data.status + '</span></li>';
                            tbody += `
                                <tr>
                                    <td>` + val.data.payid + `</td>
                                    <td>` + val.amount + `</td>
                                    <td>` + val.data.rrn + `</td>
                                    <td>` + val.data.status + `</td>
                                </tr>        
                            `;
                        } else {
                            out += '<li class="list-group-item alert alert-danger no-margin mb-10"><strong>Rs.  ' + val.amount + '</strong><span class="pull-right">' + val.data.rrn + '</span></li>';
                        }
                    });
                    $('.transactionData').html(out);
                    if (samount != 0) {
                        $('#receptTable').fadeIn('400');
                        $('.benename').text(benename);
                        $('.beneaccount').text(beneaccount);
                        $('.benebank').text(bankname);
                        $('#receptTable').find('tbody').html(tbody);
                        $('.samount').text(parseFloat(samount));
                    } else {
                        $('#receptTable').fadeOut('400');
                    }
                    $('#receipt').modal();
                },
                error: function(errors) {
                    showError(errors, form);
                }
            });
            return false;
        });

        // $( "#transferForm" ).validate({
        //     rules: {
        //         amount: {
        //             required: true,
        //             number : true,
        //             min:100
        //         }
        //     },
        //     messages: {
        //         amount: {
        //             required: "Please enter amount",
        //             number: "Amount should be numeric",
        //             min : "Amount value should be greater than 100"
        //         }
        //     },
        //     errorElement: "p",
        //     errorPlacement: function ( error, element ) {
        //         if ( element.prop("tagName").toLowerCase() === "select" ) {
        //             error.insertAfter( element.closest( ".form-group my-1" ).find(".select2") );
        //         } else {
        //             error.insertAfter( element );
        //         }
        //     },
        //     submitHandler: function () {
        //         var form = $('#transferForm');
        //         var amount = form.find('[name="amount"]').val();
        //         var benename = form.find('[name="benename"]').val();
        //         var beneaccount = form.find('[name="beneaccount"]').val();
        //         var benebank = form.find('[name="benebank"]').val();
        //         var bankname = form.find('.benebank').text();
        //         var beneifsc = form.find('[name="beneifsc"]').val();
        //         form.ajaxSubmit({
        //             dataType:'json',
        //             beforeSubmit:function(){
        //                 form.find('button[type="submit"]').button('loading');
        //             },
        //             success:function(data){
        //                 form.find('button[type="submit"]').button('reset');
        //                 form[0].reset();
        //                 getbalance();
        //                 form.closest('.modal').modal('hide');
        //                 var samount = 0;
        //                 var out ="";
        //                 var tbody = '';
        //                 $.each(data.data , function(index, val){
        //                     if(val.data.statuscode == "TXN" || val.data.statuscode == "TUP"){
        //                         samount += parseFloat(val.amount);
        //                         out += '<li class="list-group-item alert alert-success no-margin mb-10"><strong>Rs.  '+val.amount+'</strong><span class="pull-right">'+val.data.status+'</span></li>';
        //                         tbody += `
        //                             <tr>
        //                                 <td>`+val.data.payid+`</td>
        //                                 <td>`+val.amount+`</td>
        //                                 <td>`+val.data.rrn+`</td>
        //                                 <td>`+val.data.status+`</td>
        //                             </tr>        
        //                         `;
        //                     }else{
        //                         out += '<li class="list-group-item alert alert-danger no-margin mb-10"><strong>Rs.  '+val.amount+'</strong><span class="pull-right">'+val.data.rrn+'</span></li>';
        //                     }
        //                 });
        //                 $('.transactionData').html(out);
        //                 if(samount != 0){
        //                     $('#receptTable').fadeIn('400');                            
        //                     $('.benename').text(benename);
        //                     $('.beneaccount').text(beneaccount);
        //                     $('.benebank').text(bankname);
        //                     $('#receptTable').find('tbody').html(tbody);
        //                     $('.samount').text(parseFloat(samount));
        //                 }else{
        //                     $('#receptTable').fadeOut('400');
        //                 }
        //                 $('#receipt').modal();
        //             },
        //             error: function(errors) {
        //                 showError(errors, form);
        //             }
        //         });
        //     }
        // });

        $("#registrationForm").validate({
            rules: {
                name: {
                    required: true,
                },
                surname: {
                    required: true,
                },
                pincode: {
                    required: true,
                    number: true,
                    minlength: 6,
                    maxlength: 6
                },
            },
            messages: {
                name: {
                    required: "Please enter firstname",
                },
                surname: {
                    required: "Please enter surname",
                },
                pincode: {
                    required: "Please enter pincode",
                    number: "Pincode should be numeric",
                    minlenght: "Pincode length should be 6 digit",
                    maxlenght: "Pincode length should be 6 digit",
                }
            },
            errorElement: "p",
            errorPlacement: function(error, element) {
                if (element.prop("tagName").toLowerCase() === "select") {
                    error.insertAfter(element.closest(".form-group my-1").find(".select2"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                var form = $('#registrationForm');
                var type = form.find('input[name="type"]').val();
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button[type="submit"]').button('loading');
                    },
                    success: function(data) {
                        form.find('button[type="submit"]').button('reset');
                        if (data.statuscode == "TXN") {
                            form.closest('.modal').modal('hide');
                            $("#serachForm").submit();
                        } else {
                            notify(data.message, 'error', "inline", form);
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $('#getBenename').click(function() {
            var mobile = $(this).closest('form').find("[name='mobile']").val();
            var name = $(this).closest('form').find("[name='name']").val();
            var benebank = $(this).closest('form').find("[name='benebank']").val();
            var beneaccount = $(this).closest('form').find("[name='beneaccount']").val();
            var beneifsc = $(this).closest('form').find("[name='beneifsc']").val();
            var benename = $(this).closest('form').find("[name='benename']").val();
            var benemobile = $(this).closest('form').find("[name='benemobile']").val();

            if (mobile != '' || name != '' || benebank != '' || beneaccount != '' || beneifsc != '' || benename != '' || benemobile != '') {
                getName(mobile, name, benebank, beneaccount, beneifsc, benename, benemobile, 'add');
            }
        });
    });

    function setVerifyData(data) {
        $('.name').text(data.message.custfirstname);
        $('.mobile').text(data.message.custmobile);
        $('.totallimit').text(parseInt(data.message.total_limit));
        $('.usedlimit').text(parseInt(data.message.used_limit));
        $('.remainlimit').text(parseInt(data.message.total_limit) - parseInt(data.message.used_limit));
        $('[name="mobile"]').val(data.message.custmobile);
        $('[name="name"]').val(data.message.custfirstname);
        $('#rname').val(data.message.custfirstname);
        $('#rlimit').val(parseInt(data.message.total_limit) - parseInt(data.message.used_limit));
        $('.userdetails').fadeIn('400');
    }

    function setBeneData(data) {
        if (data.message.Data.length > 0) {
            out = ``;
            $.each(data.message.Data, function(index, beneficiary) {
                out += `<tr>
                        <td>` + beneficiary.benename + `</td>
                        <td>` + beneficiary.beneaccno + ` <br> (` + beneficiary.ifsc + `)<br> ( ` + beneficiary.bankname + ` )</td>
                        <td>`;
                if (beneficiary.status == "V") {
                    out += `<button class="btn btn-primary" onclick="sendMoney('` + data.message.custmobile + `','` + data.message.custfirstname + `','` + beneficiary.bankid + `', '` + beneficiary.beneaccno + `', '` + beneficiary.ifsc + `', '` + beneficiary.benename + `', '` + beneficiary.benemobile + `', '` + beneficiary.bankname + `')"><i class="fa fa-paper-plane"></i> Send</button>`;
                }

                if (beneficiary.status == "NV") {
                    out += `<button class="btn btn-success" onclick="otpVerify('` + data.message.custmobile + `', '` + beneficiary.beneaccno + `', '` + beneficiary.benemobile + `')"><i class="fa fa-check"></i> Verify</button>`;
                }
                out += `</td>
                    </tr>`;
            });
            $('.transaction').find('tbody').html(out);
        } else {
            $('.transaction').find('tbody').html('');
        }
    }

    function getName(mobile, name, benebank, beneaccount, beneifsc, benename, benemobile, type) {
        swal({
            title: 'Are you sure ?',
            text: "You want verify account details, it will charge.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: "Yes Verify",
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !swal.isLoading(),
            preConfirm: () => {
                return new Promise((resolve) => {
                    $.ajax({
                        url: "{{route('dmt1pay')}}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        data: {
                            'type': "accountverification",
                            'mobile': mobile,
                            "beneaccount": beneaccount,
                            "beneifsc": beneifsc,
                            "name": name,
                            "benebank": benebank,
                            "benename": benename,
                            "benemobile": benemobile
                        },
                        success: function(data) {
                            swal.close();
                            if (data.statuscode == "IWB") {
                                notify(data.message, 'error');
                            } else if (data.statuscode == "TXN") {
                                if (type == "add") {
                                    $("#beneficiaryForm").find('input[name="benename"]').val(data.message);
                                    $("#beneficiaryForm").find('input[name="benename"]').blur();
                                    notify("Success! Account details found", 'success', "inline", $("#beneficiaryForm"));
                                } else {
                                    swal(
                                        'Account Verified',
                                        "Account Name is - " + data.data.benename,
                                        'success'
                                    );
                                }
                            } else {
                                if (type == "add") {
                                    notify(data.message, 'error', "inline", $("#beneficiaryForm"));
                                } else {
                                    swal('Oops!', data.message, 'error');
                                }
                            }
                        },
                        error: function(errors) {
                            swal.close();
                            showError(errors, 'withoutform');
                        }
                    });
                });
            },
        });
    }

    function sendMoney(mobile, name, benebank, beneaccount, beneifsc, benename, benemobile, bankname) {
        $('#transferForm').find('input[name="mobile"]').val(mobile);
        $('#transferForm').find('input[name="name"]').val(name);
        $('#transferForm').find('input[name="benebank"]').val(benebank);
        $('#transferForm').find('input[name="beneaccount"]').val(beneaccount);
        $('#transferForm').find('input[name="beneifsc"]').val(beneifsc);
        $('#transferForm').find('input[name="benename"]').val(benename);
        $('#transferForm').find('input[name="benemobile"]').val(benemobile);

        $('#transferForm').find('.benename').text(benename);
        $('#transferForm').find('.beneaccount').text(beneaccount);
        $('#transferForm').find('.beneifsc').text(beneifsc);
        $('#transferForm').find('.benebank').text(bankname);
        $('#transferModal').modal();
    }

    function otpVerify(mobile, beneaccount, benemobile) {
        $.ajax({
            url: "{{route('dmt1pay')}}",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            data: {
                'mobile': mobile,
                'type': "otp"
            },
            beforeSend: function() {
                swal({
                    title: 'Wait!',
                    text: 'We are processing your request.',
                    allowOutsideClick: () => !swal.isLoading(),
                    onOpen: () => {
                        swal.showLoading()
                    }
                });
            },
            success: function(result) {
                swal.close();
                if (result.statuscode == "TXN") {
                    $('#otpModal').find("[name='mobile']").val(mobile);
                    $('#otpModal').find("[name='beneaccount']").val(beneaccount);
                    $('#otpModal').find("[name='benemobile']").val(benemobile);
                    $('#otpModal').modal();
                } else {
                    notify(data.message, 'error', "inline", form);
                }
            },
            error: function(error) {
                swal.close();
                notify("Something went wrong", 'error', "inline", form);
            }
        });
    }
</script>
@endpush
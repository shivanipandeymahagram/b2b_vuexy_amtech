@extends('layouts.app')
@section('title', "Aeps Fund Request")
@section('pagetitle', "Aeps Fund Request")

@php
$table = "yes";
$export = "aepsfundrequest";
$status['type'] = "Fund";
$status['data'] = [
"success" => "Success",
"pending" => "Pending",
"failed" => "Failed",
"approved" => "Approved",
"rejected" => "Rejected",
];
@endphp

@section('content')
<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle')</span>
                    </h5>
                </div>
                <div class="col-sm-12 col-md-2 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <a href="" class="btn btn-success text-white " data-bs-toggle="modal" data-bs-target="#fundRequestModalsRunpaisa">
                            <i class="ti ti-plus ti-xs"></i> Runpaisa Payout</a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th>Bank Details</th>
                            <th>Refrence Details</th>
                            <th>Amount</th>
                            <th>Remark</th>
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


<div class="modal fade" id="fundRequestModalsRunpaisa" tabindex="-1" role="modal" aria-labelledby="exampleModalLabels" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Runpaisa Payout</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>

            <form id="fundRequestFormsRunpaisa" action="{{route('runpaisatxn')}}" method="post">
                <div class="modal-body">
                    @if(Auth::user()->bank != '' && Auth::user()->ifsc != '' && Auth::user()->account != '')

                    <table class="table table-bordered p-b-15" cellspacing="0" style="margin-bottom: 30px">
                        <thead>
                            <tr>
                                <th>Select bank</th>
                                <th>Bank</th>
                                <th>Account</th>
                                <th>Ifsc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($userbanks as $payoutuser)
                            @if(!empty($payoutuser['account']))
                            <tr>
                                <td><input type="radio" id="banktype" name="bankacccount" value="{{$payoutuser['account']}}-{{$payoutuser['ifsc']}}-{{$payoutuser['bank']}}"></td>
                                <td>{{$payoutuser['bank']}}</td>
                                <td>{{$payoutuser['account']}}</td>
                                <td>{{$payoutuser['ifsc']}}</td>
                            </tr>
                            @endif
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                    <input type="hidden" name="user_id">
                    {{ csrf_field() }}
                    <div class="row">
                        @if(Auth::user()->bank == '' && Auth::user()->ifsc == '' && Auth::user()->account == '')
                        <div class="form-group col-md-6 my-1 my-1">
                            <label>Account Number <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="account" placeholder="Enter Value" required="" value="{{Auth::user()->account}}">
                        </div>
                        <div class="form-group col-md-6 my-1 my-1">
                            <label>IFSC Code <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="ifsc" placeholder="Enter Value" required="" value="{{Auth::user()->ifsc}}">
                        </div>
                        <div class="form-group col-md-6 my-1 my-1">
                            <label>Bank Name <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="bank" placeholder="Enter Value" required="" value="{{Auth::user()->bank}}">
                        </div>
                        @endif

                        <div class="form-group col-md-6 my-1 my-1">
                            <label>Wallet Type <span class="text-danger fw-bold">*</span></label>
                            <select name="type" class="form-control my-1" required>
                                <option value="">Select Wallet</option>
                                <option value="bank">Move To Bank</option>
                                <option value="wallet">Move To Wallet</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 my-1 my-1">
                            <label>Amount <span class="text-danger fw-bold">*</span></label>
                            <input type="number" class="form-control my-1" name="amount" placeholder="Enter Value" required="">
                        </div>
                        <div class="form-group col-md-6 my-1 my-1">
                            <label>Comments <span class="text-danger fw-bold">*</span></label>
                            <input type="text" class="form-control my-1" name="comments" placeholder="Enter Value" required="">
                        </div>
                        <div class="form-group col-md-6 my-1 my-1">
                            <label>T- PIN <span class="text-danger fw-bold">*</span></label>
                            <input type="password" name="pin" class="form-control my-1" placeholder="Enter transaction pin" required="">
                            <a href="{{url('profile/view?tab=pinChange')}}" target="_blank" class="text-primary pull-right">Generate or Forgot PIN?</a>
                        </div>
                    </div>
                    <p class="text-danger">Note - If you want to change bank details, please send mail with account details to update your bank details.</p>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
            </form>

        </div>
    </div>
</div>


<div class="modal fade" id="fundRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Aeps Fund Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="fundRequestForm" action="{{route('fundtransaction')}}" method="post">
                <div class="modal-body">
                    @if(Auth::user()->bank != '' && Auth::user()->ifsc != '' && Auth::user()->account != '')
                    <table class="table table-bordered p-b-15" cellspacing="0" style="margin-bottom: 30px">
                        <thead class="thead-light">
                            <tr>
                                <th>Select bank</th>
                                <th>Account</th>
                                <th>Name</th>
                                <th>Bank</th>
                                <th>Ifsc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payoutusers as $payoutuser)
                            <tr>
                                <td><input type="radio" id="banktype" name="beniid" value="{{$payoutuser->bene_id}}"></td>
                                <td>{{$payoutuser->account}}</td>
                                <td>{{$payoutuser->account}}</td>
                                <td>{{$payoutuser->bankname}}</td>
                                <td>{{$payoutuser->ifsc}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif

                    <input type="hidden" name="user_id">
                    {{ csrf_field() }}
                    <div class="row">
                        @if(Auth::user()->bank == '' && Auth::user()->ifsc == '' && Auth::user()->account == '')

                        <div class="form-group col-md-6 my-1">
                            <label>Account Number</label>
                            <input type="text" class="form-control my-1" name="account" placeholder="Enter Value" required="" value="{{Auth::user()->account}}">
                        </div>
                        <div class="form-group col-md-6 my-1">
                            <label>IFSC Code</label>
                            <input type="text" class="form-control my-1" name="ifsc" placeholder="Enter Value" required="" value="{{Auth::user()->ifsc}}">
                        </div>
                        <div class="form-group col-md-6 my-1">
                            <label>Bank Name</label>
                            <input type="text" class="form-control my-1" name="bank" placeholder="Enter Value" required="" value="{{Auth::user()->bank}}">
                        </div>

                        @endif

                        <div class="form-group col-md-6 my-1">
                            <label>Wallet Type</label>
                            <select name="type" class="form-control my-1" required>
                                <option value="">Select Wallet</option>
                                <option value="bank">Move To Bank</option>
                                <option value="wallet">Move To Wallet</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6 my-1">
                            <label>Amount</label>
                            <input type="number" class="form-control my-1" name="amount" placeholder="Enter Value" required="">
                        </div>
                        <div class="form-group col-md-6 my-1">
                            <label>T- PIN</label>
                            <input type="password" name="pin" class="form-control my-1" placeholder="Enter transaction pin" required="">
                            <a href="{{url('profile/view?tab=pinChange')}}" target="_blank" class="text-primary pull-right">Generate or Forgot PIN?</a>
                        </div>
                    </div>
                    <p class="text-danger">Note - If you want to change bank details, please send mail with account details to update your bank details.</p>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('style')

@endpush

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/aepsfundrequest/0";
        var onDraw = function() {};
        var options = [{
                "data": "name",
                render: function(data, type, full, meta) {
                    var out = '';
                    if (full.api) {
                        out += `<span class='myspan'>` + full.api.api_name + `</span><br>`;
                    }
                    out += `<span class='text-inverse'>` + full.id + `</span><br><span style='font-size:12px'>` + full.created_at + `</span>`;
                    return out;
                }
            },
            {
                "data": "username"
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.type == "wallet") {
                        return "Wallet"
                    } else {
                        return full.account + " ( " + full.bank + " )<br>" + full.ifsc;
                    }
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.type == "wallet") {
                        return "Wallet"
                    } else {
                        if (full.pay_type == "payout") {
                            return "Ref - " + full.payoutref + "<br>Txnid - " + full.payoutid;
                        } else {
                            return "Manual";
                        }
                    }
                }
            },
            {
                "data": "description",
                render: function(data, type, full, meta) {
                    return `<span class='text-inverse'><i class="fa fa-rupee"></i> ` + full.amount + `</span> / ` + full.type;
                }
            },
            {
                "data": "remark"
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    if (full.status == "approved") {
                        var btn = '<span class="label label-success text-uppercase"><b>' + full.status + '</b></span>';
                    } else if (full.status == 'pending') {
                        var btn = '<span class="label label-warning text-uppercase"><b>' + full.status + '</b></span>';
                    } else {
                        var btn = '<span class="label label-danger text-uppercase"><b>' + full.status + '</b></span>';
                    }
                    return btn;
                }
            }
        ];

        datatableSetup(url, options, onDraw);

        $("#fundRequestForm").validate({
            rules: {
                amount: {
                    required: true
                },
                type: {
                    required: true
                },
            },
            messages: {
                amount: {
                    required: "Please enter request amount",
                },
                type: {
                    required: "Please select request type",
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
                var form = $('#fundRequestForm');
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button:submit').button('loading');
                    },
                    complete: function() {
                        form.find('button:submit').button('reset');
                    },
                    success: function(data) {
                        if (data.status == "success") {
                            form.closest('.modal').modal('hide');
                            notify("Fund Request submitted Successfull", 'success');
                            $('#datatable').dataTable().api().ajax.reload();
                        } else {
                            notify(data.status, 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

        $("#fundRequestForms").validate({
            rules: {
                amount: {
                    required: true
                },
                type: {
                    required: true
                },
            },
            messages: {
                amount: {
                    required: "Please enter request amount",
                },
                type: {
                    required: "Please select request type",
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
                var form = $('#fundRequestForms');
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button:submit').button('loading');
                    },
                    complete: function() {
                        form.find('button:submit').button('reset');
                    },
                    success: function(data) {
                        if (data.status == "success") {
                            form.closest('.modal').modal('hide');
                            notify("Fund Request submitted Successfull", 'success');
                            $('#datatable').dataTable().api().ajax.reload();
                        } else {
                            notify(data.status, 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });
        $("#fundRequestFormsRunpaisa").validate({
            rules: {
                amount: {
                    required: true
                },
                type: {
                    required: true
                },
            },
            messages: {
                amount: {
                    required: "Please enter request amount",
                },
                type: {
                    required: "Please select request type",
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
                var form = $('#fundRequestFormsRunpaisa');
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button:submit').button('loading');
                    },
                    complete: function() {
                        form.find('button:submit').button('reset');
                    },
                    success: function(data) {
                        if (data.status == "success") {
                            form.closest('.modal').modal('hide');
                            notify("Fund Request submitted Successfull", 'success');
                            $('#datatable').dataTable().api().ajax.reload();
                        } else {
                            notify(data.status, 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

    });
</script>
@endpush
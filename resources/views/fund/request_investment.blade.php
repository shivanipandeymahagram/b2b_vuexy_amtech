@extends('layouts.app')
@section('title', "Wallet Load Request")
@section('pagetitle', "Wallet Load Request")

@php
$table = "yes";

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
<div class="content">
  

    <div class="row mt-3">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title"></h4>
                    </div>
                    <div>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#fundRequestModal" class="btn btn-primary" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"><b><i class="icon-plus2"></i></b> New Request</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" id="datatable">
                            <thead class="bg-light">
                                <tr>
                                    <th>#</th>
                                    <th>Deposit Bank Details</th>
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
    </div>
</div>


<div class="modal fade" id="fundRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Wallet Fund Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form id="invfundRequestForm" action="{{route('invfundtransaction')}}" method="post">
                <div class="modal-body">
                    <input type="hidden" name="user_id">
                    <input type="hidden" name="type" value="request">
                    {{ csrf_field() }}
                    <div class="row">
                    <div class="form-group my-1 col-md-6">
                            <label>Deposit Bank</label>
                            <select name="fundbank_id" class="form-control my-1" id="select" required>
                                <option value="">Select Bank</option>
                                @foreach ($banks as $bank)
                                <option value="{{$bank->id}}">{{$bank->name}} ( {{$bank->account}} )</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-1 col-md-6">
                            <label>Amount</label>
                            <input type="number" name="amount" step="any" class="form-control my-1" placeholder="Enter Amount" required="">
                        </div>
                        <div class="form-group my-1 col-md-6">
                            <label>Payment Mode</label>
                            <select name="paymode" class="form-control my-1" id="select" required>
                                <option value="">Select Paymode</option>
                                @foreach ($paymodes as $paymode)
                                <option value="{{$paymode->name}}">{{$paymode->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-1 col-md-6">
                            <label>Pay Date</label>
                            <input type="text" name="paydate" class="form-control mydate" placeholder="Select date">
                        </div>
                        <div class="form-group my-1 col-md-6">
                            <label>Ref No.</label>
                            <input type="text" name="ref_no" class="form-control my-1" placeholder="Enter Refrence Number" required="">
                        </div>
                        <div class="form-group my-1 col-md-6">
                            <label>Pay Slip (Optional)</label>
                            <input type="file" name="payslips" class="form-control my-1">
                        </div>
                        <div class="form-group my-1 col-md-12">
                            <label>Remark</label>
                            <textarea name="remark" class="form-control my-1" rows="2" placeholder="Enter Remark"></textarea>
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

@push('style')

@endpush

@push('script')
<script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script type="text/javascript" src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/investfundrequest/0";
        var onDraw = function() {};
        var options = [{
                "data": "name",
                render: function(data, type, full, meta) {
                    return `<span class='text-inverse m-l-10'><b>` + full.id + `</b> </span><br>
                        <span style='font-size:13px'>` + full.updated_at + `</span>`;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Name - ` + full.fundbank.name + `<br>Account No. - ` + full.fundbank.account + `<br>Branch - ` + full.fundbank.branch;
                }
            },
            {
                "data": "ref_no"
            },
           
            {
                "data": "amount"
            },
            {
                "data": "remark"
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    var out = '';
                    if (full.status == "approved") {
                        out += `<span class="badge badge-success">Approved</span>`;
                    } else if (full.status == "pending") {
                        out += `<span class="badge badge-warning">Pending</span>`;
                    } else if (full.status == "rejected") {
                        out += `<span class="badge badge-danger">Rejected</span>`;
                    }

                    return out;
                }
            }
        ];

        datatableSetup(url, options, onDraw);



        $("#invfundRequestForm").validate({
            rules: {
             
                amount: {
                    required: true
                },
                paymode: {
                    required: true
                },
                ref_no: {
                    required: true
                },
            },
            messages: {
              
                amount: {
                    required: "Please enter request amount",
                },
                paymode: {
                    required: "Please select payment mode",
                },
                ref_no: {
                    required: "Please enter transaction refrence number",
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
                var form = $('#invfundRequestForm');
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
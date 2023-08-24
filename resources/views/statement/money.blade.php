@extends('layouts.app')
@section('title', "Money Statement")
@section('pagetitle', "Money Statement")

@php
$table = "yes";
$export = "money";

$table = "yes";

$status['type'] = "Report";
$status['data'] = [
"success" => "Success",
"pending" => "Pending",
"reversed" => "Reversed",
];
@endphp

@section('content')
<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle') </span>
                    </h5>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>Order ID</th>
                            <th>User Details</th>
                            <th>Beneficary Details</th>
                            <th>Refrence Details</th>
                            <th>Amount/Commission</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                       
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="receipt" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between bg-primary ">
                <h5 class="modal-title text-light">DMT Receipt</h5>
                <img src="{{asset('')}}logos/{{Auth::user()->company->logo}}" height="50px" width="60px" alt="">

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin: -1rem -1rem -1rem !important;">
                   
                </button>
            </div>
            <div class="modal-body">
                <div id="receptTable">

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="table-responsive-sm">
                                <table class="table">
                                      <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Txn Date</th>
                                            <th scope="col">Bank Details</th>
                                            <th scope="col">Shop Details</th>
                                            <th scope="col">Remitter Details</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="created_at"></td>
                                            <td>
                                                <address class="m-b-10">
                                                    <strong>Beneficary Name : </strong> <span class="option2"></span><br>
                                                    <strong>Account No : </strong> <span class="number"></span><br>
                                                    <strong>Bank Name : </strong> <span class="option3"></span>
                                                </address>
                                            </td>
                                            <td>
                                                <address class="m-b-10">
                                                    <strong>Agent Name :</strong> <span>{{Auth::user()->name}}</span><br>
                                                    <strong>Shop Name :</strong> <span>{{Auth::user()->shopname}}</span><br>
                                                    <strong>Phone :</strong> <span>{{Auth::user()->mobile}}</span>
                                                </address>
                                            </td>
                                            <td>
                                                <address class="m-b-10">
                                                    <strong>Date : </strong> <span class="created_at"></span><br>
                                                    <strong>Remitter Name :</strong> <span class="option1"></span><br>
                                                    <strong>Phone :</strong> <span class="mobile"></span>
                                                </address>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h5>Transaction Details :</h5>
                                <div class="table-responsive-sm">
                                    <table class="table table-striped">
                                          <thead class="thead-light">
                                            <tr>
                                                <th scope="col">Order Id</th>
                                                <th scope="col">Amount</th>
                                                <th scope="col">UTR No.</th>
                                                <th scope="col">Status</th>
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

                        <div class="col-sm-12">
                        <b class="text-danger">Notes:</b>
                        <p>* As per RBI guideline, maximum charges allowed is 2%.</p>
                    </div>
                    </div>
                    <!-- <div class="col-sm-12">

                        <h5 class="mt-5"> Details</h5>
                        <div class="table-responsive-sm">
                            <table class="table table-striped">
                                  <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Bank</th>
                                        <th scope="col">.Acc.No</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="option3"></th>
                                        <td class="number"></td>
                                        <td class="created_at"></td>
                                        <td class="amount"><b></b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> -->


                    
                </div>
            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="otpModal" abindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Refund Via Otp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                   
                </button>
            </div>
            <form action="{{route('dmt2pay')}}" method="post" id="otpForm">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="type" value="getrefund">
                    <input type="hidden" name="transid">
                    <div class="form-group">
                        <label>OTP</label>
                        <input type="text" class="form-control" name="otp" placeholder="enter otp" required>
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
@endsection

@push('style')

@endpush

@push('script')
<script src="{{ asset('/assets/js/core/jQuery.print.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/moneystatement/{{$id}}";

        $('#print').click(function() {
            $('#receptTable').print();
        });

        var onDraw = function() {
            $('.print').click(function(event) {
                var data = DT.row($(this).parent().parent().parent().parent().parent()).data();
                $.each(data, function(index, values) {
                    $("." + index).text(values);
                });
                $('#receipt').modal();
            });
        };
        var options = [{
                "data": "name",
                render: function(data, type, full, meta) {
                    return `<div>
                            <span class=''>` + full.apiname + `</span><br>
                            <span class='text-inverse m-l-10'><b>` + full.id + `</b> </span>
                            <div class="clearfix"></div>
                        </div><span style='font-size:13px' class="pull=right">` + full.created_at + `</span>`;
                }
            },
            {
                "data": "username"
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Name - ` + full.option2 + `<br>Account - ` + full.number + `<br>Bank - ` + full.option3;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Remitter -  ${full.option1} (${full.mobile})<br>Bank Ref - ${(full.refno=='You have Insufficent balance')?'Service is down for some time':full.refno} <br> Txnid - ${full.txnid} <br>Payid -  ${full.payid} `;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Amount - <i class="fa fa-inr"></i> ` + full.amount + `<br>Charge - <i class="fa fa-inr"></i> ` + full.charge + `<br>Profit - <i class="fa fa-inr"></i> ` + parseFloat(full.profit + full.gst) + `<br>Gst - <i class="fa fa-inr"></i> ` + full.gst
                }
            },
            {
                "data": "status",
                render: function(data, type, full, meta) {
                   
                    var menu = ``;
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item print"><i class="icon-info22"></i>Print Invoice</a></li>`;
                    if (full.status == "refund" || full.status == "success") {
                        menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="getrefund(` + full.id + `)"><i class="icon-info22"></i>Get Refund</a></li>`;
                    }

                    @if(Myhelper::can('money_status'))
                    menu += `<a href="javascript:void(0)" class="dropdown-item" onclick="status(` + full.id + `, 'money')"><i class="icon-info22"></i>Check Status</a>`;
                    @endif

                    @if(Myhelper::can('money_statement_edit'))
                    menu += `<a href="javascript:void(0)" class="dropdown-item" onclick="editReport(` + full.id + `,'` + full.refno + `','` + full.txnid + `','` + full.payid + `','` + full.remark + `', '` + full.status + `', 'money')"><i class="icon-pencil5"></i> Edit</a>`;
                    @endif

                    @if(Myhelper::can('complaint'))
                    menu += `<a href="javascript:void(0)" class="dropdown-item" onclick="complaint(` + full.id + `, 'recharge')"><i class="icon-cogs"></i> Complaint</a>`;
                    @endif


                    return `<div class="btn-group" role="group">
                                    <span id="btnGroupDrop1" class="badge ${full.status=='success'? 'badge-success' : full.status=='pending'? 'badge-warning':full.status=='reversed'? 'badge-info':full.status=='refund'? 'badge-dark':'badge-danger'} dropdown-toggle"  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ` + full.status + `
                                    </span>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                       ` + menu + `
                                    </div>
                                 </div>`;

                }
            }
        ];

        var DT = datatableSetup(url, options, onDraw);

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
                    error.insertAfter(element.closest(".form-group").find(".select2"));
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
                            form[0].reset();
                            $('#otpModal').find('[name="transid"]').val("");
                            $('#otpModal').modal('hide');
                            notify('Transaction Successfully Refunded, Amount Credited', 'success');
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

    });

    function getrefund(id, type = "none") {
        $.ajax({
                url: `{{route('dmt2pay')}}`,
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    'id': id,
                    "type": "refundotp"
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
            })
            .done(function(data) {
                swal.close();
                if (type == "none") {
                    if (data.statuscode == "TXN") {
                        $('#otpModal').find('[name="transid"]').val(id);
                        $('#otpModal').modal('show');
                    } else {
                        notify(data.message, 'error');
                    }
                } else {
                    if (data.statuscode == "TXN") {
                        notify(data.message, 'success', "inline", $('#otpForm'));
                    } else {
                        notify(data.message, 'error', "inline", $('#otpForm'));
                    }
                    $('#datatable').dataTable().api().ajax.reload();
                }
            })
            .fail(function(errors) {
                swal.close();
            });
    }

    function viewUtiid(id) {
        $.ajax({
                url: `{{url('statement/fetch')}}/utiidstatement/` + id,
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                data: {
                    'scheme_id': id
                }
            })
            .done(function(data) {
                $.each(data, function(index, values) {
                    $("." + index).text(values);
                });
                $('#utiidModal').modal();
            })
            .fail(function(errors) {
                notify('Oops', errors.message + '! ' + errors.statusText, 'error');
            });
    }
</script>
@endpush
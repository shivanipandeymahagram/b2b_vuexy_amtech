@extends('layouts.app')
@section('title', "Micro-Atm Statement")
@section('pagetitle', "Micro-Atm Statement")

@php
$table = "yes";
$export = "aeps";

$status['type'] = "Report";
$status['data'] = [
"complete" => "Complete",
"success" => "Success",
"pending" => "Pending",
"failed" => "Failed",
"reversed" => "Reversed",
"refunded" => "Refunded",
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
                            <th>Bank Details</th>
                            <th>Refrences Details</th>
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

<div class="modal fade" id="receipt" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div id="receptTable">
                <div class="table-responsive">
                    <table class="table">
                          <thead class="thead-light">
                            <tr>
                                <th style="padding: 10px 0px">Receipt</th>
                                <th style="padding: 10px 0px; text-align: right;">
                                    @if(Auth::user()->company->logo)
                                    <img src="{{asset('')}}public/logos/{{Auth::user()->company->logo}}" class=" img-responsive pull-right" alt="" style="width: 260px;height: 56px;">
                                    @else
                                    {{Auth::user()->company->companyname}}
                                    @endif
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 10px 0px">
                                    <address class="m-b-10">
                                        <strong>Agent :</strong> <span>{{Auth::user()->name}}</span><br>
                                        <strong>Shop Name :</strong> <span>{{Auth::user()->shopname}}</span><br>
                                        <strong>Phone :</strong> <span>{{Auth::user()->mobile}}</span>
                                    </address>
                                </td>
                                <td style="padding: 10px 0px" class="text-right">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <h5>Transaction Details :</h5>
                                <div class="table-responsive">
                                <table class="table m-t-10">
                                      <thead class="thead-light">
                                        <tr>
                                            <th style="padding: 10px 0px">Order Id</th>
                                            <th style="padding: 10px 0px">Amount</th>
                                            <th style="padding: 10px 0px">Ref. No.</th>
                                            <th style="padding: 10px 0px">Date</th>
                                            <th style="padding: 10px 0px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="mytxnid" style="padding: 10px 0px"></td>
                                            <td class="amount" style="padding: 10px 0px"></td>
                                            <td class="refno" style="padding: 10px 0px"></td>
                                            <td class="created_at" style="padding: 10px 0px"></td>
                                            <td class="status" style="padding: 10px 0px"></td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="border-radius: 0px;">
                        <div class="col-md-6 col-md-offset-6">
                            <h5 class="text-right">Withdraw Amount : <span class="amount"></span></h5>
                        </div>
                    </div>
                    <p>Note* - This is computer generated receipt no need to sign</p>
                    <hr>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary" type="button" id="print"><i class="fa fa-print"></i></button>
            </div>
        </div>
    </div>
</div>


@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/matmstatement/{{$id}}";
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
                    return `Adhaar - ` + full.aadhar + `<br>Mobile - ` + full.mobile;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Ref No. - ` + full.refno + `<br>Txnid - ` + full.txnid + `<br>Payid - ` + full.payid;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Amount - <i class="fa fa-inr"></i> ` + full.amount + `<br>Commission - <i class="fa fa-inr"></i> ` + full.charge;
                }
            },
            {
                "data": "status",
                render: function(data, type, full, meta) {
                   
                    var menu = ``;
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item print"><i class="icon-info22"></i>Print Invoice</a></li>`;
                    @if(Myhelper::can('matm_status'))
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="status(` + full.id + `, 'matm')"><i class="icon-info22"></i>Check Status</a></li>`;
                    @endif

                    @if(Myhelper::can('matm_statement_edit'))
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="editReport(` + full.id + `,'` + full.refno + `','` + full.txnid + `','` + full.payid + `','` + full.remark + `', '` + full.status + `', 'matm')"><i class="icon-pencil5"></i> Edit</a></li>`;
                    @endif

                    if (full.status != "reversed") {
                        menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="complaint(` + full.id + `, 'matm')"><i class="icon-cogs"></i> Complaint</a></li>`;
                    }

                    return `<div class="btn-group" role="group">
                                    <span id="btnGroupDrop1" class="badge ${full.status=='success'? 'badge-success' : full.status=='pending'? 'badge-warning':full.status=='reversed'? 'badge-info':full.status=='complete'? 'badge-primary':'badge-danger'} dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
    });

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
                notify('Oops', errors.status + '! ' + errors.statusText, 'warning');
            });
    }
</script>
@endpush
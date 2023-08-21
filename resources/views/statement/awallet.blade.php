@extends('layouts.app')
@section('title', "Aeps Wallet Statement")
@section('pagetitle', "Aeps Wallet Statement")

@php
$table = "yes";
$export = "awallet";
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
                            <th>#</th>
                            <th>Refrences Details</th>
                            <th>Order ID</th>
                            <th>Transaction Details</th>
                            <th>TXN Type</th>
                            <th>ST Type</th>
                            <th>Status</th>
                            <th>Opening Bal. </th>
                            <th>Amount </th>
                            <th>Closing Bal. </th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>

@endsection

@push('style')

@endpush

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/awalletstatement/{{$id}}";
        var onDraw = function() {
            $('[data-popup="tooltip"]').tooltip();
            $('[data-popup="popover"]').popover({
                template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
            });
        };
        var options = [{
                "data": "name",
                render: function(data, type, full, meta) {
                    var out = "";
                    out += `</a><span style='font-size:13px' class="pull=right">` + full.created_at + `</span>`;
                    return out;
                }
            },
            {
                "data": "username"
            },
            {
                "data": "id"
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.transtype == "fund") {
                        return `<b>` + full.payid + `</b><br> ` + full.remark;
                    } else {
                        if (full.status == "success") {
                            return full.aadhar + ` / ` + full.mobile + ' / ' + full.refno;
                        } else {
                            return full.aadhar + ` / ` + full.mobile + ' / ' + full.mytxnid;
                        }
                    }
                }
            },
            {
                "data": "transtype"
            },
            {
                "data": "rtype"
            },
            {
                "data": "status"
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `<i class="fa fa-inr"></i> ` + full.balance;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.aepstype == "AP") {
                        if (full.type == "credit") {
                            return `<i class="text-success icon-plus22"></i> <i class="fa fa-inr"></i> ` + (full.amount - full.charge);
                        } else if (full.type == "debit") {
                            return `<i class="text-danger icon-dash"></i> <i class="fa fa-inr"></i> ` + (full.amount - full.charge);
                        } else {
                            return `<i class="fa fa-inr"></i> ` + (full.amount - full.charge);
                        }
                    } else {
                        if (full.type == "credit") {
                            return `<i class="text-success icon-plus22"></i> <i class="fa fa-inr"></i> ` + (full.amount + full.charge);
                        } else if (full.type == "debit") {
                            return `<i class="text-danger icon-dash"></i> <i class="fa fa-inr"></i> ` + (full.amount + full.charge);
                        } else {
                            return `<i class="fa fa-inr"></i> ` + (full.amount + full.charge);
                        }
                    }
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.aepstype == "AP") {
                        if (full.type == "credit") {
                            return `<i class="fa fa-inr"></i> ` + (parseFloat(full.balance) + parseFloat(parseFloat(full.amount) - parseFloat(full.charge)));
                        } else if (full.type == "debit") {
                            return `<i class="fa fa-inr"></i> ` + (parseFloat(full.balance) - parseFloat(parseFloat(full.amount) - parseFloat(full.charge)));
                        } else {
                            return `<i class="fa fa-inr"></i> ` + full.balance;
                        }
                    } else {
                        if (full.type == "credit") {
                            if (full.status == "success") {
                                return `<i class="fa fa-inr"></i> ` + (parseFloat(full.balance) + parseFloat(parseFloat(full.amount) + parseFloat(full.charge)));
                            } else {
                                return `<i class="fa fa-inr"></i> ` + full.balance;
                            }

                        } else if (full.type == "debit") {
                            return `<i class="fa fa-inr"></i> ` + (parseFloat(full.balance) - parseFloat(parseFloat(full.amount) + parseFloat(full.charge)));
                        } else {
                            return `<i class="fa fa-inr"></i> ` + full.balance;
                        }
                    }
                }
            }
        ];

        datatableSetup(url, options, onDraw, '#datatable', {
            columnDefs: [{
                orderable: false,
                width: '80px',
                targets: [0]
            }]
        });
    });
</script>
@endpush
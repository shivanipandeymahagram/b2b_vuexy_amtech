@extends('layouts.app')
@section('title', "Account Statement")
@section('pagetitle', "Account Statement")

@php
$table = "yes";
$export = "wallet";
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
                            <th>Product</th>
                            <th>Provider</th>
                            <th>Txnid</th>
                            <th>Order ID</th>
                            <th>Number</th>
                            <th>ST Type</th>
                            <th>Status</th>
                            <th>Opening Bal. </th>
                            <th>Amount </th>
                            <th>Charge </th>
                            <th>Commission/Profit </th>
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
        var url = "{{url('statement/fetch')}}/accountstatement/{{$id}}";
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
                "data": "full.username",
                render: function(data, type, full, meta) {
                    var uid = "{{Auth::id()}}";
                    if (full.credited_by == uid) {
                        var name = full.username;
                    } else {
                        var name = full.sendername;
                    }
                    return name;
                }
            },
            {
                "data": "product"
            },
            {
                "data": "providername"
            },
            {
                "data": "id"
            },
            {
                "data": "number"
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
                    return `<i class="fa fa-inr"></i> ` + full.amount;
                    //   if(full.status == "pending" || full.status == "success" || full.status == "reversed" || full.status == "refunded"){
                    //     if(full.trans_type == "credit"){
                    //         return `<i class="fa fa-inr"></i> `+ (parseFloat(full.amount) + parseFloat(full.charge) - parseFloat(full.profit));
                    //     }else if(full.trans_type == "debit"){
                    //         return `<i class="fa fa-inr"></i> `+ (parseFloat(full.amount) - parseFloat(full.charge) - parseFloat(full.profit));
                    //     }else if(full.trans_type == "none"){
                    //         return `<i class="fa fa-inr"></i> `+ (parseFloat(full.amount) + parseFloat(full.charge) - parseFloat(full.profit));
                    //     }
                    // }else{
                    //    return `<i class="fa fa-inr"></i> `+full.balance;
                    //}
                }
            },
            {
                "data": "charge",
                render: function(data, type, full, meta) {
                    if (full.charge > 0) {
                        return `<i class="fa fa-inr"></i> ` + full.charge;
                    } else {
                        return 0;
                    }
                }
            },
            {
                "data": "profit",
                render: function(data, type, full, meta) {
                    if (full.profit > 0) {
                        return `<i class="fa fa-inr"></i> ` + full.profit
                    } else {
                        return `<i class="fa fa-inr"></i> ` + full.profit
                    }
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.status == "pending" || full.status == "success" || full.status == "reversed" || full.status == "refunded") {
                        if (full.trans_type == "credit") {
                            return `<i class="fa fa-inr"></i> ` + (parseFloat(full.balance) + parseFloat(parseFloat(full.amount) + parseFloat(full.charge) - parseFloat(full.profit))).toFixed(2);
                        } else if (full.trans_type == "debit") {
                            return `<i class="fa fa-inr"></i> ` + (parseFloat(full.balance) - parseFloat(parseFloat(full.amount) + parseFloat(full.charge) - parseFloat(full.profit))).toFixed(2);
                        } else if (full.trans_type == "none") {
                            return `<i class="fa fa-inr"></i> ` + (parseFloat(full.balance) - parseFloat(parseFloat(full.amount) + parseFloat(full.charge) - parseFloat(full.profit))).toFixed(2);
                        }
                    } else {
                        return `<i class="fa fa-inr"></i> ` + full.balance;
                    }
                }
            },
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
@extends('layouts.app')
@section('title', "Fund Statement")
@section('pagetitle', "Fund Statement")

@php
$table = "yes";
$export = "fund";
$status['type'] = "Fund";
$status['data'] = [
"success" => "Success",
"pending" => "Pending",
"failed" => "Failed",
"approved" => "Approved",
"rejected" => "Rejected",
];

$product['type'] = "Fund Type";
$product['data'] = [
"transfer" => "Transfer",
"return" => "Return",
"request" => "Request"
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
                            <th>#</th>
                            <th>User Details</th>
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

@endsection


@push('script')
<script type="text/javascript">
    $(document).ready(function() {

        var url = "{{url('statement/fetch')}}/fundstatement/0";
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
                    var uid = "{{Auth::id()}}";
                    if (full.credited_by == uid) {
                        return full?.username;
                    } else {
                        return full?.sendername;
                    }
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.product == "fund request") {
                        return `Name - ` + full?.fundbank?.name + `<br>Account No. - ` + full?.fundbank?.account + `<br>Ref - ` + full?.refno + `(` + full?.product + `)`;
                    } else {
                        return full?.refno + `<br>` + full?.product;
                    }
                }
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
                    if (full?.status == "approved" || full?.status == "success") {
                        out += `<label class="label label-success">` + full.status + `</label>`;
                    } else if (full?.status == "pending") {
                        out += `<label class="label label-warning">Pending</label>`;
                    } else {
                        out += `<label class="label label-danger">` + full?.status + `</label>`;
                    }

                    return out;
                }
            }
        ];

        datatableSetup(url, options, onDraw);
    });
</script>
@endpush
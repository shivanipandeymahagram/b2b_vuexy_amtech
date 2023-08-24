@extends('layouts.app')
@section('title', "Recharge Statement")
@section('pagetitle', "Recharge Statement")

@php
$table = "yes";
$export = "recharge";

$billers = App\Models\Provider::whereIn('type', ['mobile', 'dth'])->get(['id', 'name']);
foreach ($billers as $item){
$product['data'][$item->id] = $item->name;
}
$product['type'] = "Operator";

$status['type'] = "Report";
$status['data'] = [
"success" => "Success",
"pending" => "Pending",
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
                            <th>Transaction Details</th>
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


<div class="modal fade" id="utiidModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Uti Id Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>Vle Id</th>
                                <td class="vleid"></td>
                            </tr>
                            <tr>
                                <th>Vle Password</th>
                                <td class="vlepassword"></td>
                            </tr>
                            <tr>
                                <th>Name</th>
                                <td class="name"></td>
                            </tr>
                            <tr>
                                <th>Localtion</th>
                                <td class="location"></td>
                            </tr>
                            <tr>
                                <th>Contact Person</th>
                                <td class="contact_person"></td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td class="state"></td>
                            </tr>
                            <tr>
                                <th>Pincode</th>
                                <td class="pincode"></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td class="email"></td>
                            </tr>
                            <tr>
                                <th>Mobile</th>
                                <td class="mobile"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-hidden="true">Close</button>
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
        var url = "{{url('statement/fetch')}}/rechargestatement/{{$id}}";
        var onDraw = function() {};
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
                    return `Number - ` + full.number + `<br>Operator - ` + full.providername;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Ref No.  - ` + full.refno + `<br>Txnid - ` + full.txnid;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Amount - <i class="fa fa-inr"></i> ` + full.amount + `<br>Profit - <i class="fa fa-inr"></i> ` + full.profit;
                }
            },
            {
                "data": "status",
                render: function(data, type, full, meta) {

                    var menu = ``;
                    if (full.status == "success" || full.status == "pending") {
                        @if(Myhelper::can('recharge_status'))
                        menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="status(` + full.id + `, 'recharge')"><i class="icon-info22"></i>Check Status</a></li>`;
                        @endif

                        @if(Myhelper::can('recharge_statement_edit'))
                        menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="editReport(` + full.id + `,'` + full.refno + `','` + full.txnid + `','` + full.payid + `','` + full.remark + `', '` + full.status + `', 'recharge')"><i class="icon-pencil5"></i> Edit</a></li>`;
                        @endif
                    }

                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="complaint(` + full.id + `, 'recharge')"><i class="icon-cogs"></i> Complaint</a></li>`;


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

        datatableSetup(url, options, onDraw);
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
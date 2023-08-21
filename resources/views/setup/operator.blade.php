@extends('layouts.app')
@section('title', 'Operator List')
@section('pagetitle', 'Operator List')
@php
$table = "yes";

$agentfilter = "hide";
$product['type'] = "Operator Type";
$product['data'] = [
"mobile" => "Mobile",
"dth" => "Dth",
"electricity" => "Electricity",
"pancard" => "Pancard",
"dmt" => "Dmt",
"fund" => "Fund",
"lpggas" => "Lpg Gas",
"gas" => "Piped Gas",
"landline" => "Landline",
"postpaid" => "Postpaid",
"broadband" => "Broadband",
"loanrepay" => "Loan Repay",
"lifeinsurance" => "Life Insurance",
"fasttag" => "Fast Tag",
"cable" => "Cable",
"insurance" => "Insurance",
"schoolfees" => "School Fees",
"muncipal" => "Minicipal",
"housing" => "Housing"
];
asort($product['data']);
$status['type'] = "Operator";
$status['data'] = [
"1" => "Active",
"0" => "De-active"
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
                <div class="col-sm-12 col-md-2 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <button type="button" class="btn btn-success text-white ms-4" onclick="addSetup()">
                            <i class="ti ti-plus ti-xs"></i> Add New</a>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Operator Api</th>
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


<div class="modal fade" id="setupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Add Operator</h3>
                </div>

                <form id="setupManager" action="{{route('setupupdate')}}" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="operator">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control my-1" placeholder="Enter value" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Recharge1</label>
                                <input type="text" name="recharge1" class="form-control my-1" placeholder="Enter value" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 my-1">
                                <label>Recharge2</label>
                                <input type="text" name="recharge2" class="form-control my-1" placeholder="Enter value" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Operator Type</label>
                                <select name="type" class="form-control my-1" required>
                                    <option value="">Select Operator Type</option>
                                    <option value="mobile">Mobile</option>
                                    <option value="dth">DTH</option>
                                    <option value="electricity">Electricity Bill</option>
                                    <option value="pancard">Pancard</option>
                                    <option value="dmt">Dmt</option>
                                    <option value="aeps">Aeps</option>
                                    <option value="fund">Fund</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 my-1">
                                <label>Api</label>
                                <select name="api_id" class="form-control my-1" required>
                                    <option value="">Select Api</option>
                                    @foreach ($apis as $api)
                                <option value="{{$api->id}}">{{$api->product}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-hidden="true">Close</button>
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/setup{{$type}}/0";

        var onDraw = function() {
            // $('select').select2();
            $('input.operatorStatusHandler').on('click', function(evt) {
                evt.stopPropagation();
                var ele = $(this);
                var id = $(this).val();
                var status = "0";
                if ($(this).prop('checked')) {
                    status = "1";
                }

                $.ajax({
                        url: `{{ route('setupupdate') }}`,
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        data: {
                            'id': id,
                            'status': status,
                            "actiontype": "operator"
                        }
                    })
                    .done(function(data) {
                        if (data.status == "success") {
                            notify("Operator Updated", 'success');
                            $('#datatable').dataTable().api().ajax.reload();
                        } else {
                            if (status == "1") {
                                ele.prop('checked', false);
                            } else {
                                ele.prop('checked', true);
                            }
                            notify("Something went wrong, Try again.", 'warning');
                        }
                    })
                    .fail(function(errors) {
                        if (status == "1") {
                            ele.prop('checked', false);
                        } else {
                            ele.prop('checked', true);
                        }
                        showError(errors, "withoutform");
                    });
            });
        };

        var options = [{
                "data": "id"
            },
            {
                "data": "name"
            },
            {
                "data": "type"
            },
            {
                "data": "name",
                render: function(data, type, full, meta) {
                    var check = "";
                    if (full.status == "1") {
                        check = "checked='checked'";
                    }

                    return `<div class="custom-control custom-switch custom-control-inline">
                              <input type="checkbox" class="custom-control-input operatorStatusHandler" id="operatorStatus_${full.id}" ${check} value="` + full.id + `" actionType="` + type + `">
                              <label class="custom-control-label" for="operatorStatus_${full.id}"></label>
                           </div>`;
                }
            },
            {
                "data": "name",
                render: function(data, type, full, meta) {
                    var out = "";
                    out += `<select class="form-control" required="" onchange="apiUpdate(this, ` + full.id + `)">`;
                    @foreach($apis as $api)
                    var apiid = "{{$api->id}}";
                    out += `<option value="{{$api->id}}"`;
                    if (apiid == full.api_id) {
                        out += `selected="selected"`;
                    }
                    out += `>{{$api->product}}</option>`;
                    @endforeach
                    out += `</select>`;
                    return out;
                }
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    return `<button type="button" class="btn btn-primary" onclick="editSetup(` + full.id + `, \`` + full.name + `\`, \`` + full.recharge1 + `\`, \`` + full.recharge2 + `\`, \`` + full.type + `\`, \`` + full.api_id + `\`)"> Edit</button>`;
                }
            },
        ];
        datatableSetup(url, options, onDraw);

        $("#setupManager").validate({
            rules: {
                name: {
                    required: true,
                },
                recharge1: {
                    required: true,
                },
                recharge2: {
                    required: true,
                },
                type: {
                    required: true,
                },
                api_id: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter operator name",
                },
                recharge1: {
                    required: "Please enter value",
                },
                recharge2: {
                    required: "Please enter value",
                },
                type: {
                    required: "Please select operator type",
                },
                api_id: {
                    required: "Please select api",
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
                var form = $('#setupManager');
                var id = form.find('[name="id"]').val();
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button[type="submit"]').button('loading');
                    },
                    success: function(data) {
                        if (data.status == "success") {
                            if (id == "new") {
                                form[0].reset();
                                $('[name="api_id"]').val(null).trigger('change');
                            }
                            form.find('button[type="submit"]').button('reset');
                            notify("Task Successfully Completed", 'success');
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

        $("#setupModal").on('hidden.bs.modal', function() {
            $('#setupModal').find('.msg').text("Add");
            $('#setupModal').find('form')[0].reset();
        });

    });

    function addSetup() {
        $('#setupModal').find('.msg').text("Add");
        $('#setupModal').find('input[name="id"]').val("new");
        $('#setupModal').modal('show');
    }

    function editSetup(id, name, recharge1, recharge2, type, api_id) {
        $('#setupModal').find('.msg').text("Edit");
        $('#setupModal').find('input[name="id"]').val(id);
        $('#setupModal').find('input[name="name"]').val(name);
        $('#setupModal').find('input[name="recharge1"]').val(recharge1);
        $('#setupModal').find('input[name="recharge2"]').val(recharge2);
        $('#setupModal').find('[name="type"]').val(type).trigger('change');
        $('#setupModal').find('[name="api_id"]').val(api_id).trigger('change');
        $('#setupModal').modal('show');
    }

    function apiUpdate(ele, id) {
        var api_id = $(ele).val();
        if (api_id != "") {
            $.ajax({
                    url: `{{ route('setupupdate') }}`,
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    data: {
                        'id': id,
                        'api_id': api_id,
                        "actiontype": "operator"
                    }
                })
                .done(function(data) {
                    if (data.status == "success") {
                        notify("Operator Updated", 'success');
                    } else {
                        notify("Something went wrong, Try again.", 'warning');
                    }
                    $('#datatable').dataTable().api().ajax.reload();
                })
                .fail(function(errors) {
                    showError(errors, "withoutform");
                });
        }
    }
</script>
@endpush
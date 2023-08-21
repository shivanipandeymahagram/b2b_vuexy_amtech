@extends('layouts.app')
@section('title', 'Api Manager')
@section('pagetitle', 'Api Manager')
@php
$table = "yes";


$agentfilter = "hide";
$status['type'] = "Api";
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
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Display Name</th>
                            <th>Api Code</th>
                            <th>Credentials</th>
                            <th>Status</th>
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
                    <h3 class="mb-2">@yield('pagetitle')</h3>
                </div>

                <form id="setupManager" action="{{route('setupupdate')}}" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="api">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Product Name</label>
                                <input type="text" name="product" class="form-control my-1" placeholder="Enter value" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Display Name</label>
                                <input type="text" name="name" class="form-control my-1" placeholder="Enter value" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Url</label>
                                <input type="text" name="url" class="form-control my-1" placeholder="Enter url">
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control my-1" placeholder="Enter Value">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Password</label>
                                <input type="text" name="password" class="form-control my-1" placeholder="Enter url">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Optional1</label>
                                <input type="text" name="optional1" class="form-control my-1" placeholder="Enter Value">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Api Code</label>
                                <input type="text" name="code" class="form-control my-1" placeholder="Enter url" required="">
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label>Product Type</label>
                                <select name="type" class="form-control my-1" required>
                                    <option value="">Select Type</option>
                                    <option value="recharge">Recharge</option>
                                    <option value="bill">Bill Payment</option>
                                    <option value="money">Money transfer</option>
                                    <option value="pancard">Pancard</option>
                                    <option value="fund">Fund</option>
                                </select>
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Commission Type</label>
                                <select name="commissiontype" class="form-control my-1" required>
                                    <option value="">Select Type</option>
                                    <option value="percent">Percent</option>
                                    <option value="flat">Flat</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label>Commission/Charge</label>
                                <input type="text" name="commissionCharge" class="form-control my-1" placeholder="Commission or Charge" required="">
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
</div>
@endsection


@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/setup{{$type}}/0";

        var onDraw = function() {
            $('[data-popup="popover"]').popover({
                template: '<div class="popover border-teal-400"><div class="arrow"></div><h3 class="popover-title bg-teal-400"></h3><div class="popover-content"></div></div>'
            });

            $('input.apiStatusHandler').on('click', function(evt) {
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
                            "actiontype": "api"
                        }
                    })
                    .done(function(data) {
                        if (data.status == "success") {
                            notify("Api Status Updated", 'success');
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
                "data": "product"
            },
            {
                "data": "name"
            },
            {
                "data": "code"
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    return `<a href="javascript:void(0)" data-popup="popover" data-placement="top" title="" data-html="true" data-trigger="hover" data-content="Url - ` + full.url + `<br>Username - ` + full.username + `<br>Password - ` + full.password + `<br>Optional - ` + full.optional1 + `" data-original-title="` + full.product + `">Api Credentials</a>`;
                }
            },
            {
                "data": "name",
                render: function(data, type, full, meta) {
                    var check = "";
                    if (full.status == "1") {
                        check = "checked='checked'";
                    }

                    return `<div class="custom-control custom-switch custom-control-inline">
                              <input type="checkbox" class="custom-control-input apiStatusHandler" id="apiStatus_${full.id}" ${check} value="` + full.id + `" actionType="` + type + `">
                              <label class="custom-control-label" for="apiStatus_${full.id}"></label>
                           </div>`;
                }
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    return `<button type="button" class="btn btn-primary" onclick="editSetup(` + full.id + `, \`` + full.product + `\`, \`` + full.name + `\`, \`` + full.url + `\`, \`` + full.username + `\`, \`` + full.password + `\`, \`` + full.optional1 + `\`, \`` + full.code + `\`, \`` + full.type + `\`,\`` + full.commissiontype + `\`,\`` + full.commissionCharge + `\`)"> Edit</button>`;
                }
            },
        ];
        datatableSetup(url, options, onDraw);

        $("#setupManager").validate({
            rules: {
                name: {
                    required: true,
                },
                product: {
                    required: true,
                },
                code: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter display name",
                },
                product: {
                    required: "Please enter product name",
                },
                url: {
                    required: "Please enter api url",
                },
                code: {
                    required: "Please enter api code",
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

    function editSetup(id, product, name, url, username, password, optional1, code, type, commissiontype, commissionCharge) {
        $('#setupModal').find('.msg').text("Edit");
        $('#setupModal').find('input[name="id"]').val(id);
        $('#setupModal').find('input[name="product"]').val(product);
        $('#setupModal').find('input[name="name"]').val(name);
        $('#setupModal').find('input[name="url"]').val(url);
        $('#setupModal').find('input[name="username"]').val(username);
        $('#setupModal').find('input[name="password"]').val(password);
        $('#setupModal').find('input[name="optional1"]').val(optional1);
        $('#setupModal').find('input[name="code"]').val(code);
        $('#setupModal').find('[name="type"]').select2().val(type).trigger('change');
        $('#setupModal').find('[name="commissiontype"]').select2().val(commissiontype).trigger('change');
        $('#setupModal').find('input[name="commissionCharge"]').val(commissionCharge);
        $('#setupModal').modal('show');

    }
</script>
@endpush
@extends('layouts.app')
@section('title', 'BanK Account List')
@section('pagetitle', 'Bank Account List')
@php
$table = "yes";
$agentfilter = "hide";
$status['type'] = "Bank";
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
                        <button class="btn btn-success text-white ms-4" onclick="addSetup()">
                            <i class="ti ti-plus ti-xs"></i> Add New</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Account</th>
                            <th>IFSC</th>
                            <th>Branch</th>
                            <th>Charge</th>
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
                            <input type="hidden" name="actiontype" value="bank">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control my-1" placeholder="Enter Bank Name" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Account Number</label>
                                <input type="text" name="account" class="form-control my-1" placeholder="Enter Account Number" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 my-1">
                                <label>IFSC</label>
                                <input type="text" name="ifsc" class="form-control my-1" placeholder="Enter Ifsc Code" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Branch</label>
                                <input type="text" name="branch" class="form-control my-1" placeholder="Enter Branch" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 my-1">
                                <label>Charges/Lakh</label>
                                <input type="number" name="charge" class="form-control my-1" placeholder="Enter charge amount" value="0" required="">
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
            $('input.bankStatusHandler').on('click', function(evt) {
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
                            "actiontype": "bank"
                        }
                    })
                    .done(function(data) {
                        if (data.status == "success") {
                            notify("Bank Account Updated", 'success');
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
                "data": "account"
            },
            {
                "data": "ifsc"
            },
            {
                "data": "branch"
            },
            {
                "data": "charge"
            },
            {
                "data": "name",
                render: function(data, type, full, meta) {
                    var check = "";
                    if (full.status == "1") {
                        check = "checked='checked'";
                    }

                    return `<div class="custom-control custom-switch custom-control-inline">
                              <input type="checkbox" class="custom-control-input bankStatusHandler" id="bankStatus_${full.id}" ${check} value="` + full.id + `" actionType="` + type + `">
                              <label class="custom-control-label" for="bankStatus_${full.id}"></label>
                           </div>`;
                }
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    return `<button type="button" class="btn btn-primary" onclick="editSetup(this)"> Edit</button>`;
                }
            },
        ];
        datatableSetup(url, options, onDraw);

        $("#setupManager").validate({
            rules: {
                name: {
                    required: true,
                },
                account: {
                    required: true,
                },
                ifsc: {
                    required: true,
                },
                branch: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter bank name",
                },
                account: {
                    required: "Please enter account number",
                },
                ifsc: {
                    required: "Please enter ifsc code",
                },
                branch: {
                    required: "Please enter bank branch",
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
    });

    function addSetup() {
        $('#setupModal').find('.msg').text("Add");
        $('#setupModal').find('input[name="id"]').val("new");
        $('#setupModal').modal('show');
    }

    function editSetup(ele) {
        var id = $(ele).closest('tr').find('td').eq(0).text();
        var name = $(ele).closest('tr').find('td').eq(1).text();
        var account = $(ele).closest('tr').find('td').eq(2).text();
        var ifsc = $(ele).closest('tr').find('td').eq(3).text();
        var branch = $(ele).closest('tr').find('td').eq(4).text();
        var charge = $(ele).closest('tr').find('td').eq(5).text();
        $('#setupModal').find('.msg').text("Edit");
        $('#setupModal').find('input[name="id"]').val(id);
        $('#setupModal').find('input[name="name"]').val(name);
        $('#setupModal').find('input[name="account"]').val(account);
        $('#setupModal').find('input[name="ifsc"]').val(ifsc);
        $('#setupModal').find('input[name="branch"]').val(branch);
        $('#setupModal').find('input[name="charge"]').val(charge);
        $('#setupModal').modal('show');
    }
</script>
@endpush
@extends('layouts.app')
@section('title', "Fund Transfer or Return")
@section('pagetitle', "Fund Transfer & Return")

@php
$table = "yes";
@endphp

@section('content')
<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-5">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle')</span>
                    </h5>
                </div>
            </div>

            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Parent Details</th>
                            <th>Company Profile</th>
                            <th>Wallet Details</th>
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

<div class="modal fade" id="transferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">@yield('pagetitle')</h3>
                </div>
                <form id="transferForm" action="{{route('fundtransaction')}}" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Fund Action</label>
                                <select name="type" class="form-control my-1" id="select" required>
                                    <option value="">Select Action</option>
                                    @if (Myhelper::can('fund_transfer'))
                                    <option value="transfer">Transfer</option>
                                    @endif
                                    @if (Myhelper::can('fund_return'))
                                    <option value="return">Return</option>
                                    @endif

                                </select>
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Amount</label>
                                <input type="number" name="amount" step="any" class="form-control my-1" placeholder="Enter Amount" required="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-md-12 my-1">
                                <label>Remark</label>
                                <input type="text" name="remark" class="form-control my-1" placeholder="Enter Remark">

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

@push('style')
<style>
    .md-checkbox {
        margin: 5px 0px;
    }
</style>
@endpush

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/tr/0";
        var onDraw = function() {
            $('input#membarStatus').on('click', function(evt) {
                evt.stopPropagation();
                var ele = $(this);
                var id = $(this).val();
                var status = "block";
                if ($(this).prop('checked')) {
                    status = "active";
                }

                $.ajax({
                        url: `{{ route('profileUpdate') }}`,
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        data: {
                            'id': id,
                            'status': status
                        }
                    })
                    .done(function(data) {
                        if (data.status == "success") {
                            notify("Member Updated", 'success');
                        } else {
                            notify("Something went wrong, Try again.", 'warning');
                        }
                    })
                    .fail(function(errors) {
                        if (status == "active") {
                            ele.prop('checked', false);
                        } else {
                            ele.prop('checked', true);
                        }
                        showError(errors, "withoutform");
                    });
            });
        };
        var options = [{
                "data": "name",
                'className': "notClick",
                render: function(data, type, full, meta) {
                    var check = "";
                    if (full.kyc == "pending") {
                        check += `<span class="badge badge-warning">Kyc Pending</span>`;
                    } else {
                        check += `<span class="badge badge-success">Kyc Success</span>`;
                    }
                    return `<div>` + check + `<span class='text-inverse pull-right m-l-10'><b>` + full.id + `</b> </span>
                            <div class="clearfix"></div>
                        </div>
                        <span style='font-size:13px'>` + full.updated_at + `</span>`;
                }
            },
            {
                "data": "name",
                render: function(data, type, full, meta) {
                    return `<span class="name">` + full.name + `</span>` + `<br>` + full.mobile + `<br>` + full.role.name;
                }
            },
            {
                "data": "parents"
            },
            {
                "data": "name",
                render: function(data, type, full, meta) {
                    return `<span class="name">` + full.company.companyname + `</span>` + `<br>` + full.company.website;
                }
            },
            {
                "data": "name",
                render: function(data, type, full, meta) {
                    return `Main - ` + full.mainwallet + " /-<br>Locked - " + full.lockedamount + " /-";
                }
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    return `<button class="btn btn-primary" onclick="transfer('` + full.id + `')">Transfer / Return</button>`;
                }
            }
        ];

        datatableSetup(url, options, onDraw);

        $("#transferForm").validate({
            rules: {
                type: {
                    required: true
                },
                amount: {
                    required: true,
                    min: 1
                }
            },
            messages: {
                type: {
                    required: "Please select transfer action",
                },
                amount: {
                    required: "Please enter amount",
                    min: "Amount value should be greater than 0"
                },
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
                var form = $('#transferForm');
                var type = $('#transferForm').find('[name="type"]').val();
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
                            getbalance();
                            form.closest('.modal').modal('hide');
                            notify("Fund " + type + " Successfull", 'success');
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

    function transfer(id) {
        $('#transferForm').find('[name="user_id"]').val(id);
        $('#transferModal').modal('show');
    }
</script>
@endpush
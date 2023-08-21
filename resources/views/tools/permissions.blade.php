@extends('layouts.app')
@section('title', 'Permissions')
@section('pagetitle', 'Permissions List')
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
                        <span>@yield('pagetitle') </span>
                    </h5>
                </div>
                <div class="col-sm-12 col-md-2 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <button type="button" class="btn btn-success text-white ms-4" onclick="addrole()">
                            <i class="ti ti-plus ti-xs"></i> Add New</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th> Name</th>
                            <th>Display Name</th>
                            <th>Type</th>
                            <th>Last Updated</th>
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


<div class="modal fade" id="permissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Add Permission</h3>
                </div>

                <form id="permissionManager" action="{{route('toolsstore', ['type'=>'permission'])}}" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="bank">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control my-1" placeholder="Enter Permission Name" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Display Name</label>
                                <input type="text" name="account" class="form-control my-1" placeholder="Enter Display Name" required="">
                            </div>
                            <div class="form-group col-md-12 my-1">
                                <label>Type</label>
                                    <input type="text" name="type" class="form-control my-1" placeholder="Enter Permission Type" required="">
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
        var url = "{{url('statement/fetch/permissions/0')}}";
        var onDraw = function() {};
        var options = [{
                "data": "id"
            },
            {
                "data": "slug"
            },
            {
                "data": "name"
            },
            {
                "data": "type"
            },
            {
                "data": "updated_at"
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    return `<button type="button" class="btn btn-primary" onclick="editRole(this)"> Edit</button>`;
                }
            },
        ];
        datatableSetup(url, options, onDraw);

        $("#permissionManager").validate({
            rules: {
                slug: {
                    required: true,
                },
                name: {
                    required: true,
                },
            },
            messages: {
                mobile: {
                    required: "Please enter role slug",
                },
                name: {
                    required: "Please enter role name",
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
                var form = $('#permissionManager');
                var id = $('#permissionManager').find("[name='id']").val();
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

        $("#permissionModal").on('hidden.bs.modal', function() {
            $('#permissionModal').find('.msg').text("Add");
            $('#permissionModal').find('form')[0].reset();
        });
    });

    function addrole() {
        $('#permissionModal').find('.msg').text("Add");
        $('#permissionModal').find('input[name="id"]').val("new");
        $('#permissionModal').modal('show');
    }

    function editRole(ele) {
        var id = $(ele).closest('tr').find('td').eq(0).text();
        var slug = $(ele).closest('tr').find('td').eq(1).text();
        var name = $(ele).closest('tr').find('td').eq(2).text();
        var type = $(ele).closest('tr').find('td').eq(3).text();

        $('#permissionModal').find('.msg').text("Edit");
        $('#permissionModal').find('input[name="id"]').val(id);
        $('#permissionModal').find('input[name="slug"]').val(slug);
        $('#permissionModal').find('input[name="name"]').val(name);
        $('#permissionModal').find('input[name="type"]').val(type);
        $('#permissionModal').modal('show');
    }
</script>
@endpush
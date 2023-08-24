@extends('layouts.app')
@section('title', 'Roles')
@section('pagetitle', 'Role List')
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


<div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Add Role</h3>
                </div>

                <form id="rolemanager" action="{{route('toolsstore', ['type'=>'roles'])}}" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="id">
                            <input type="hidden" name="actiontype" value="bank">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Role Name</label>
                                <input type="text" name="name" class="form-control my-1" placeholder="Enter Role Name" required="">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Display Name</label>
                                <input type="text" name="account" class="form-control my-1" placeholder="Enter Display Name" required="">
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


@if (isset($permissions) && $permissions)

<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-hidden="true" id="permissionModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Member Permission</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="permissionForm" action="{{route('toolssetpermission')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="role_id">
                <input type="hidden" name="type" value="permission">
                <div class="modal-body p-0">
                    <div class="table-responsive">
                        <table id="datatable" class="table table-hover">
                              <thead class="thead-light">
                                <tr>
                                    <th width="170px;">Section Category</th>
                                    <th>
                                        <span class="pull-left m-t-5 m-l-10">Permissions</span>
                                        <div class="md-checkbox pull-right">
                                            <input type="checkbox" id="selectall">
                                            <label for="selectall">Select All</label>
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $key => $value)
                                <tr>
                                    <td>
                                        <div class="md-checkbox mymd">
                                            <input type="checkbox" class="selectall" id="{{ucfirst($key)}}">
                                            <label for="{{ucfirst($key)}}">{{ucfirst($key)}}</label>
                                        </div>
                                    </td>

                                    <td class="row">
                                        @foreach ($value as $permission)
                                        <div class="md-checkbox col-md-4 p-0">
                                            <input type="checkbox" class="case" id="{{$permission->id}}" name="permissions[]" value="{{$permission->id}}">
                                            <label for="{{$permission->id}}">{{$permission->name}}</label>
                                        </div>
                                        @endforeach
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="schemeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Scheme Manager</h3>
                </div>

                <form id="schemeForm" method="post" action="{{ route('toolssetpermission') }}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-md-12 my-1">
                                <label>Scheme</label>
                                <select class="form-control my-1" name="permissions[]">
                                    <option value="">Select Scheme</option>
                                    @foreach ($scheme as $element)
                                <option value="{{$element->id}}">{{$element->name}}</option>
                                @endforeach
                                </select>
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
        var url = "{{url('statement/fetch/roles/0')}}";
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
                "data": "updated_at"
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    var menu = ``;

                    @if(Myhelper::can(['fund_transfer', 'fund_return']))
                    menu += `<a href="javascript:void(0)" class="dropdown-item" onclick="editRole(this)"><i class="fa fa-pencil"></i> Edit</a>`;
                    @endif

                    @if(Myhelper::can('member_permission_change'))
                    menu += `<a href="javascript:void(0)" class="dropdown-item" onclick="getPermission(` + full.id + `)"><i class="icon-cogs"></i> Permission</a>`;
                    @endif

                    @if(Myhelper::can('member_scheme_change'))
                    menu += `<a href="javascript:void(0)" class="dropdown-item" onclick="scheme(` + full.id + `, '` + full.scheme + `')"><i class="icon-wallet"></i> Scheme</a>`;
                    @endif

                    out = ` <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle"  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   More
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                       ` + menu + `
                                    </div>
                                 </div>`;

                    return out;
                }
            },
        ];
        datatableSetup(url, options, onDraw);

        $("#rolemanager").validate({
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
                var form = $('#rolemanager');
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

        $("#roleModal").on('hidden.bs.modal', function() {
            $('#roleModal').find('.msg').text("Add");
            $('#roleModal').find('form')[0].reset();
        });

        $('form#permissionForm').submit(function() {
            var form = $(this);
            $(this).ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button[type="submit"]').button('loading');
                },
                complete: function() {
                    form.find('button[type="submit"]').button('reset');
                },
                success: function(data) {
                    if (data.status == "success") {
                        notify('Permission Set Successfully', 'success');
                    } else {
                        notify('Transaction Failed', 'warning');
                    }
                },
                error: function(errors) {
                    showError(errors, form);
                }
            });
            return false;
        });

        $('#selectall').click(function(event) {
            if (this.checked) {
                $('.case').each(function() {
                    this.checked = true;
                });
                $('.selectall').each(function() {
                    this.checked = true;
                });
            } else {
                $('.case').each(function() {
                    this.checked = false;
                });
                $('.selectall').each(function() {
                    this.checked = false;
                });
            }
        });

        $('.selectall').click(function(event) {
            if (this.checked) {
                $(this).closest('tr').find('.case').each(function() {
                    this.checked = true;
                });
            } else {
                $(this).closest('tr').find('.case').each(function() {
                    this.checked = false;
                });
            }
        });

        $("#schemeForm").validate({
            rules: {
                scheme_id: {
                    required: true
                }
            },
            messages: {
                scheme_id: {
                    required: "Please select scheme",
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
                var form = $('#schemeForm');
                var type = $('#schemeForm').find('[name="type"]').val();
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
                            notify("Role Scheme Updated Successfull", 'success');
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

    function addrole() {
        $('#roleModal').find('.panel-title').text("Add New Role");
        $('#roleModal').find('input[name="id"]').val("new");
        $('#roleModal').modal('show');
    }

    function editRole(ele) {
        var id = $(ele).closest('tr').find('td').eq(0).text();
        var slug = $(ele).closest('tr').find('td').eq(1).text();
        var name = $(ele).closest('tr').find('td').eq(2).text();

        $('#roleModal').find('.msg').text("Edit");
        $('#roleModal').find('input[name="id"]').val(id);
        $('#roleModal').find('input[name="slug"]').val(slug);
        $('#roleModal').find('input[name="name"]').val(name);
        $('#roleModal').modal('show');
    }

    function getPermission(id) {
        if (id.length != '') {
            $.ajax({
                    url: `{{url('tools/getdefault/permission')}}/` + id,
                    type: 'post',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                })
                .done(function(data) {
                    $('#permissionForm').find('[name="role_id"]').val(id);
                    $('.case').each(function() {
                        this.checked = false;
                    });
                    $.each(data, function(index, val) {
                        $('#permissionForm').find('input[value=' + val.permission_id + ']').prop('checked', true);
                    });
                    $('#permissionModal').modal();
                })
                .fail(function() {
                    notify('Somthing went wrong', 'warning');
                });
        }
    }

    function scheme(id, scheme) {
        $('#schemeForm').find('[name="role_id"]').val(id);
        if (scheme != '' && scheme != null && scheme != 'null') {
            $('#schemeForm').find('[name="permissions[]"]').select2().val(scheme).trigger('change');
        }
        $('#schemeModal').modal();
    }
</script>
@endpush
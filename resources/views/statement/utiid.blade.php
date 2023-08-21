@extends('layouts.app')
@section('title', "Uti Id Statement")
@section('pagetitle', "Uti Id Statement")

@php
$table = "yes";
$export = "utiid";

$status['type'] = "Id";
$status['data'] = [
"success" => "Success",
"pending" => "Pending",
"failed" => "Failed",
];
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
                    <thead class=" bg-light">
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th> Uti Id Details</th>
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
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" aria-hidden="true">Close</button>
            </div>
        </div>
    </div>
</div>

@if (Myhelper::can('Utiid_statement_edit'))
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Edit Report</h3>
                </div>
                <form id="editUtiidForm" action="{{route('statementUpdate')}}" method="post">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                            <div class="form-group col-md-12 my-1">
                                <label>Status</label>
                                <select name="type" class="form-control my-1" id="select" required>
                                    <option value="">Select Type</option>
                                    <option value="success">Success</option>
                                    <option value="pending">Pending</option>
                                    <option value="failed">Failed</option>

                                </select>
                            </div>

                            <div class="form-group col-md-12 my-1">
                                <label>Vle Id</label>
                                <input type="text" name="vleid" class="form-control my-1" required="">
                            </div>
                            <div class="form-group col-md-12 my-1">
                                <label>Vle Password</label>
                                <input type="text" name="vlepassword" class="form-control my-1" required="">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" aria-label="Close">Close</button>
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('style')

@endpush

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/utiidstatement/{{$id}}";
        var onDraw = function() {};
        var options = [{
                "data": "name",
                render: function(data, type, full, meta) {
                    return `<div>
                            <span class='text-inverse pull-left m-l-10 text-capitalize'><b>` + full.type + `</b> </span>
                            <span class='text-inverse pull-right m-l-10'><b>` + full.id + `</b> </span>
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
                    return `Vle Id - ` + full.vleid + `<br>Vle Name - <a href="javascript:void(0)" onclick="viewUtiid(` + full.id + `)">` + full.name + `</a>`;
                }
            },
            {
                "data": "status",
                render: function(data, type, full, meta) {
                    
                    var menu = ``;
                    @if(Myhelper::can('utiid_status'))
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="status(` + full.id + `, 'utiid')"><i class="icon-info22"></i>Check Status</a></li>`;
                    @endif

                    @if(Myhelper::can('Utiid_statement_edit'))
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="editUtiid(` + full.id + `,'` + full.vleid + `','` + full.vlepassword + `', '` + full.status + `')"><i class="icon-pencil5"></i> Edit</a></li>`;
                    @endif


                    return `<div class="btn-group" role="group">
                                    <span id="btnGroupDrop1" class="badge ${full.status=='success'? 'badge-success' : full.status=='pending'? 'badge-warning':full.status=='reversed'? 'badge-info':full.status=='refund'? 'badge-dark':'badge-danger'} dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

        $("#editUtiidForm").validate({
            rules: {
                status: {
                    required: true,
                },
                vleid: {
                    required: true,
                },
                vlepassword: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please select status",
                },
                vleid: {
                    required: "Please enter vle id",
                },
                vlepassword: {
                    required: "Please enter vle password",
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
                var form = $('#editUtiidForm');
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

        $("#editModal").on('hidden.bs.modal', function() {
            $('#setupModal').find('form')[0].reset();
        });
    });

    function viewUtiid(id) {
        $.ajax({
                url: `{{url('statement/fetch')}}/utiidstatement/` + id + `/view`,
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

    function editUtiid(id, vleid, vlepassword, status) {
        $('#editModal').find('[name="id"]').val(id);
        $('#editModal').find('[name="status"]').val(status).trigger('change');
        $('#editModal').find('[name="vleid"]').val(vleid);
        $('#editModal').find('[name="vlepassword"]').val(vlepassword);
        $('#editModal').modal('show');
    }
</script>
@endpush
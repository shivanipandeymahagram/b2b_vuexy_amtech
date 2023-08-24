@extends('layouts.app')
@section('title', "Aeps Agents List")
@section('pagetitle', "Aeps Agent List")

@php
$table = "yes";

$status['type'] = "Id";
$status['data'] = [
"success" => "Success",
"pending" => "Pending",
"failed" => "Failed",
"approved" => "Approved",
"rejected" => "Rejected",
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
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th> BC Details</th>
                            <th> BBPS Details</th>
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

@if (Myhelper::can('aepsid_statement_edit'))
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
                                <label>BBPS Agent Id</label>
                                <input type="text" name="bbps_agent_id" class="form-control my-1" required="">
                            </div>
                            <div class="form-group col-md-12 my-1">
                                <label>BBPS Id</label>
                                <input type="text" name="bbps_id" class="form-control my-1" required="">
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal" aria-label="Close" >Close</button>
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<div class="modal fade" id="viewFullDataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agent Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>Bc Id</th>
                            <td class="bc_id"></td>
                        </tr>
                        <tr>
                            <th>Bbps Agent Id</th>
                            <td class="bbps_agent_id"></td>
                        </tr>
                        <tr>
                            <th>Bbps Id</th>
                            <td class="bbps_id"></td>
                        </tr>
                        <tr>
                            <th>Bc Name</th>
                            <td><span class="bc_f_name"></span> <span class="bc_l_name"></span></td>
                        </tr>
                        <tr>
                            <th>Bc Mailid</th>
                            <td class="emailid"></td>
                        </tr>
                        <tr>
                            <th>Phone 1</th>
                            <td class="phone1"></td>
                        </tr>
                        <tr>
                            <th>Phone 2</th>
                            <td class="phone2"></td>
                        </tr>
                        <tr>
                            <th>Shopname</th>
                            <td class="shopname"></td>
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
@endsection

@push('style')

@endpush

@push('script')
<script type="text/javascript">
    $(document).ready(function() {

        $("#editUtiidForm").validate({
            rules: {
                bbps_agent_id: {
                    required: true,
                },
            },
            messages: {
                bbps_agent_id: {
                    required: "Please enter id",
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

        var url = "{{url('statement/fetch')}}/aepsagentstatement/{{$id}}";
        var onDraw = function() {};
        var options = [{
                "data": "name",
                render: function(data, type, full, meta) {
                    return `<div>
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
                    return `Bc Id - ` + full.bc_id + `<br>BC Name - <a href="javascript:void(0)" onclick="viewFullData(` + full.id + `)">` + full.bc_f_name + `</a>`;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Agent Id - ` + full.bbps_agent_id + `<br>Bbps Id -` + full.bbps_id;
                }
            },
            {
                "data": "status",
                render: function(data, type, full, meta) {

                    var menu = '';
                    @if(Myhelper::can('aepsid_statement_edit'))
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="editUtiid(` + full.id + `,'` + full.bbps_agent_id + `','` + full.bbps_id + `')"><i class="icon-pencil5"></i> Edit</a></li>`;
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="status('` + full.id + `','bcstatus')""><i class="icon-refresh"></i> Status</a></li>`;
                    @endif


                    return `<div class="btn-group" role="group">
                                    <span id="btnGroupDrop1" class="badge ${full.status=='success'? 'badge-success' : full.status=='pending'? 'badge-warning':full.status=='approved'? 'badge-success':full.status=='refund'? 'badge-dark':'badge-danger'} dropdown-toggle"  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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

    function viewFullData(id) {
        $.ajax({
                url: `{{url('statement/fetch')}}/aepsagentstatement/` + id + `/view`,
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
                $('#viewFullDataModal').modal();
            })
            .fail(function(errors) {
                notify('Oops', errors.status + '! ' + errors.statusText, 'warning');
            });
    }

    function editUtiid(id, bbps_agent_id, bbps_id) {
        $('#editModal').find('[name="id"]').val(id);
        $('#editModal').find('[name="bbps_agent_id"]').val(bbps_agent_id);
        $('#editModal').find('[name="bbps_id"]').val(bbps_id);
        $('#editModal').modal('show');
    }
</script>
@endpush
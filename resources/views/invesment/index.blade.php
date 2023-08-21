@extends('layouts.app')
@section('title', 'Investment List')
@section('pagetitle', 'Investment List')
@php
$table = "yes";
$agentfilter = "hide";
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
                <div class="col-sm-12 col-md-3 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <button class="btn btn-success text-white ms-5" data-bs-toggle="modal" data-bs-target="#frontslideModal">
                            <i class="ti ti-plus ti-xs"></i> Add Investment</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>Banner</th>
                            <th>Start Date </th>
                            <th>End Date </th>
                            <th>Mature Amount </th>
                            <th>Maturity Amount </th>
                            <th>Amount </th>
                            <th>Status </th>
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


<div class="modal fade" id="frontslideModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Investment</h3>
                </div>
                <form id="transferForm" action="{{route('investment')}}">
                    <div class="modal-body">
                        <div class="row">
                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                            <div class="form-group col-md-6 my-1">
                                <label>Banner</label>
                                <select class="form-control my-1" name="banner_id" id="banner_id" required>
                                    @foreach($banner as $val)
                                    <option value="{{$val->id}}">{{$val->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>From </label>
                                <input type="datetime-local" name="start_date" class="form-control my-1" placeholder="Enter start date">
                            </div>

                            <div class="form-group col-md-6 my-1">
                                <label>To </label>
                                <input type="datetime-local" name="end_date" class="form-control my-1" placeholder="Enter end date">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Mature Amount </label>
                                <input type="number" name="mature_amount" class="form-control my-1" placeholder="Enter mature amount">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Maturity At </label>
                                <input type="date" name="maturity_at" class="form-control my-1" placeholder="Enter maturity at">
                            </div>
                            <div class="form-group col-md-6 my-1">
                                <label>Amount </label>
                                <input type="number" name="amount" class="form-control my-1" placeholder="Enter amount">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="button" onclick="invesmentFun()" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')

<script type="text/javascript">
    function invesmentFun() {
        var form = $('#walletLoadForms');
        form.ajaxSubmit({
            dataType: 'json',
            data: form.serialize(),

            beforeSubmit: function() {
                form.find('button:submit').button('loading');
            },
            complete: function() {
                form.find('button:submit').button('reset');
            },
            success: function(data) {
                if (data.status) {
                    form[0].reset();
                    form.closest('.modal').modal('hide');
                    notify("Invesment added successfully", 'success');
                } else {
                    notify(data.status, 'warning');
                }
            },
            error: function(errors) {
                showError(errors, form);
            }
        });
    }

    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/investment/0";

        var onDraw = function() {

        };

        var options = [{
                "data": "id"
            },

            {
                "data": "action",
                "className": "text-center",
                render: function(data, type, full, meta) {
                    if (full.banner != null)
                        return `<a href="{{asset('/banner/')}}/` + full.banner.slides + `" target="_blank"><img src="{{asset('/banner/')}}/` + full.banner.slides + `" width="100px" height="50px"></a>`;
                    else
                        return `Banner Deleted`;
                }
            },
            {
                "data": "start_date"
            },
            {
                "data": "end_date"
            },
            {
                "data": "mature_amount"
            },
            {
                "data": "maturity_at"
            },
            {
                "data": "amount"
            },
            {
                "data": "status",
                render: function(data, type, full, meta) {
                    if (full.status == 'inactive') {
                        return `<span class="badge badge-danger">Inactive</span>`;
                    } else {
                        return `<span class="badge badge-success">Active</span>`;
                    }

                }
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    return `<button type="button" class="btn btn-primary" onclick="statusChange('` + full.id + `')"> Status Change</button>`;
                }
            }
        ];
        datatableSetup(url, options, onDraw);


    });

    function statusChange(id) {
        $.ajax({
                url: '{{route("statementDelete")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "slide": id,
                    'type': 'invesment_status'
                },
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        text: 'Please wait, we are status change',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                }
            })
            .success(function(data) {
                swal.close();
                $('#datatable').dataTable().api().ajax.reload();
                notify("Status changed Successfully", 'success');
            })
            .fail(function() {
                swal.close();
                notify('Somthing went wrong', 'warning');
            });
    }
</script>
@endpush
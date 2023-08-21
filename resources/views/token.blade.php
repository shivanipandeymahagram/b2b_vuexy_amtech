@extends('layouts.app')
@section('title', "Token Delete")
@section('pagetitle', "Token Delete")

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
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">

                        <tr>

                            <th>Agent Id</th>
                            <th>IP</th>
                            <th>Last Login</th>
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

@endsection

@push('script')

<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/securedata/0";

        var onDraw = function() {};

        var options = [{
                "data": "user_id"
            },
            {
                "data": "ip"
            },
            {
                "data": "updated_at"
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    console.log(full);
                    return `<button type="button" class="btn btn-sm btn-danger btn-raised heading-btn legitRipple" onclick="deleteToken(` + full.id + `)"> <i class="fa fa-trash"></i></button>`;
                }
            },
        ];
        datatableSetup(url, options, onDraw);


    });



    function deleteToken(id) {
        swal({
            title: 'Are you sure ?',
            text: "You want to Logout This user From Application",
            type: 'warning',
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: 'Yes delete it!',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !swal.isLoading(),
            preConfirm: () => {
                return new Promise((resolve) => {
                    $.ajax({
                        url: "{{ route('tokenDelete') }}",
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        data: {
                            'id': id
                        },
                        success: function(result) {
                            resolve(result);
                        },
                        error: function(error) {
                            resolve(error);
                        }
                    });
                });
            },
        }).then((result) => {
            if (result.value.status == "1") {
                notify("Token Successfully Deleted", 'success');
                $('#datatable').dataTable().api().ajax.reload();
            } else {
                notify('Something went wrong, try again', 'Oops', 'error');
            }
        });
    }
</script>
@endpush
<style>
    .modal-title {
        margin-bottom: 0;
        line-height: 1.5;
        margin-left: -261px;
        margin-right: 198px;
    }
</style>
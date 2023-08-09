@extends('layouts.app')
@section('title', 'Uti Id Statement')
@section('pagetitle', 'Uti Id Statement')


@section('content')

@include('layouts.pageheader')
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
                        <tr>
                            <td scope="col">

                                <div>New</div>
                                <div>09 Aug - 11:36</div>
                            </td>
                            <td scope="col">
                                <div><small>Shivani (4) (Retailer)</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Vle Id - MAHA26-AMTW1459039</small></div>
                                <div><small>Vle Name - Shivani</small></div>
                            </td>

                            <td scope="col">
                                <div class="btn-group ">
                                    <button type="button" class="btn btn-success dropdown-toggle btn-xs me-1" data-bs-toggle="dropdown" aria-expanded="false">
                                       Success
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editutiModal">Edit</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Check Status</a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</div>


<div class="modal fade" id="editutiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-simple modal-edit-user">
        <div class="modal-content p-md-5">
            <div class="modal-body">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="text-center mb-3">
                    <h3 class="mb-2">Edit Report</h3>
                </div>
                <form id="transferForm" action="{{route('aeps')}}">
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
                                <input type="text" name="vle_id" class="form-control my-1" required="">
                            </div>
                            <div class="form-group col-md-12 my-1">
                                <label>Vle Password</label>
                                <input type="text" name="vle_pwd" class="form-control my-1" required="">
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
@endsection
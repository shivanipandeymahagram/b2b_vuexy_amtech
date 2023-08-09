@extends('layouts.app')
@section('title', 'Aeps Agent List')
@section('pagetitle', 'Aeps Agent List')


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
                        <tr>
                            <td scope="col">

                                <div>2023-08-09</div>
                                <div>T07:39:23.000000Z</div>
                            </td>
                            <td scope="col">
                                <div><small>Shivani (4) (Retailer)</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Bc Id - BC296842201</small></div>
                                <div><small>BC Name - Shivani</small></div>
                            </td>
                            <td scope="col">
                                <div><small>Agent Id - BBPS676610</small></div>
                                <div><small>Bbps Id -TJ01TJ50AGT000004794</small></div>
                            </td>

                            <td scope="col">
                                <div class="btn-group ">
                                    <button type="button" class="btn btn-warning dropdown-toggle btn-xs me-1" data-bs-toggle="dropdown" aria-expanded="false">
                                        Pending
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#editaepsModal">Edit</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Status</a></li>
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


<div class="modal fade" id="editaepsModal" tabindex="-1" aria-hidden="true">
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
                                <label>BBPS Agent Id</label>
                                <input type="text" name="agent_id" class="form-control my-1" required="">
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
@endsection
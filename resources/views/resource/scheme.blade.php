@extends('layouts.app')
@section('title', 'Scheme Manager')
@section('pagetitle', 'Scheme Manager')

@section('content')
<!-- Content -->

@include('layouts.pageheader')
<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle') - Table</span>
                    </h5>
                </div>
                <div class="col-sm-12 col-md-2 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <button type="button" class="btn btn-success text-white ms-4" onclick="addSetup()" data-bs-toggle="modal" data-bs-target="#setupModal"> <i class="ti ti-plus ti-xs"></i> Add New</button>

                    </div>
                </div>
            </div>

            <div class="card-datatable table-responsive">

                <table class="table text-center border-top mb-5" id="datatable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td scope="col">1</td>
                            <td scope="col">Shivi</td>
                            <td scope="col">
                                <label class="switch">
                                    <input type="checkbox" class="switch-input" />
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on"></span>
                                        <span class="switch-off"></span>
                                    </span>
                                </label>
                            </td>
                            <td scope="col">
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#setupEditModal"><i class="ti ti-pencil"></i></button>
                                <button class="btn btn-primary"><i class="ti ti-eye ti-xs"></i> &nbsp;Commission</button>

                                <div class="btn-group">
                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        Commission/Charge
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="javascript:void(0);">Online Electricity Bill</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Offline Electricity Bill</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">AEPS</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">CMS</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">MATM</a></li>
                                        <li>
                                            <hr class="dropdown-divider" />
                                        </li>
                                        <li><a class="dropdown-item disabled" href="javascript:void(0);">Charge</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">DMT</a></li>
                                        <li><a class="dropdown-item" href="javascript:void(0);">Aadhaar Pay</a></li>
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


<!-- / Content -->
<!-- Modal -->


<div class="modal fade" id="setupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Add Scheme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="setupManager" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="scheme">

                        <div class="form-group col-md-10 m-auto">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Scheme Name" required="">
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="setupEditModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Update Scheme</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="setupManager" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="scheme">

                        <div class="form-group col-md-10 m-auto">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Scheme Name" required="">
                        </div>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-xl" id="mobileModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mobile Recharge Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">

                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>
                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]" value="1">
                                    Shivi
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>

                        </tbody>
                    </table>

                    <div class="modal-footer">
                        <button class="btn bg-primary btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="dthModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">DTH Recharge Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body"> <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>
                            <th>Commission Type</th>
                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="electricModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Electricity Bill Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                    Dummy
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>


                        </tbody>
                    </table>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="electricOfflineModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Offline Electricity Bill Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                    Dummy
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>


                        </tbody>
                    </table>

                    <div class="modal-footer">

                        <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade bd-example-modal-xl" id="lpggasModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">LPG GAS Bill Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                    Dummy
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>


                        </tbody>
                    </table>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" tabindex="-1" id="waterModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Water Bill Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                    Dummy
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>


                        </tbody>
                    </table>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>

                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="loanrepayModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Loanrepay Bill Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                    Dummy
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="fasttagModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fasttag Bill Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                    Dummy
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="cableModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cable Bill Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">

                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>

                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                    Dummy
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>

                </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="postpaidModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Postpaid Bill Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                    Dummy
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="matmModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">MATM Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>
                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <td>
                                <input type="hidden" name="slab[]">
                                <input type="hidden" name="type[]" value="flat">
                                Dummy
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="aepsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">AePS Commission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">

                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Commission Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <td>
                                <input type="hidden" name="slab[]">
                                Dummy
                            </td>

                            <td class="p-t-0 p-b-0">
                                <select class="form-control" name="type[]" required="">
                                    <option value="">Select Type</option>
                                    <option value="percent">Percent (%)</option>
                                    <option value="flat">Flat (Rs)</option>
                                </select>
                            </td>


                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="dmtModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Money Transfer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">

                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Type</th>

                            <th>Whitelable</th>
                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <td>
                                <input type="hidden" name="slab[]">
                                Dummy
                            </td>

                            <td>

                                <input type="hidden" name="type[]" value="flat">
                                Flat

                                <select class="form-control" name="type[]" required="">
                                    <option value="">Select Type</option>
                                    <option value="percent">Percent (%)</option>
                                    <option value="flat">Flat (Rs)</option>
                                </select>

                            </td>

                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="AadharpayModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aadharpay Charge</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">

                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Type</th>

                            <th>Whitelable</th>

                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <tr>
                                <td>
                                    <input type="hidden" name="slab[]">
                                    Dummy
                                </td>

                                <td>

                                    <input type="hidden" name="type[]" value="flat">
                                    Flat

                                    <select class="form-control" name="type[]" required="">
                                        <option value="">Select Type</option>
                                        <option value="percent">Percent (%)</option>
                                        <option value="flat">Flat (Rs)</option>
                                    </select>

                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                                </td>

                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                                <td class="p-t-0 p-b-0">
                                    <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                                </td>
                            </tr>


                        </tbody>
                    </table>

                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="cmsModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">CMS Charge</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="commissionForm" method="post">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead>
                            <th>Operator</th>

                            <th>Type</th>

                            <th>Whitelable</th>

                            <th>Master Distributor</th>
                            <th>Distributor</th>
                            <th>Retailer</th>
                        </thead>
                        <tbody>

                            <td>
                                <input type="hidden" name="slab[]">
                                Dummy
                            </td>

                            <td>

                                <input type="hidden" name="type[]" value="flat">
                                Flat

                                <select class="form-control" name="type[]" required="">
                                    <option value="">Select Type</option>
                                    <option value="percent">Percent (%)</option>
                                    <option value="flat">Flat (Rs)</option>
                                </select>

                            </td>

                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="whitelable[]" placeholder="Enter Value" class="form-control" required="">
                            </td>

                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="md[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="distributor[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            <td class="p-t-0 p-b-0">
                                <input type="number" step="any" name="retailer[]" placeholder="Enter Value" class="form-control" required="">
                            </td>
                            </tr>


                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-xl" id="commissionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ">
                    <span class="schemename"></span>

                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body commissioData">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>


@endsection

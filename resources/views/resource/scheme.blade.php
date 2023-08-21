@extends('layouts.app')
@section('title', 'Scheme Manager')
@section('pagetitle', 'Scheme Manager')


@php
$table = "yes";
$agentfilter = "hide";
$status['type'] = "Scheme";
$status['data'] = [
"1" => "Active",
"0" => "De-active"
];
@endphp

@section('content')
<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                    <h5 class="mb-0">
                        <span>@yield('pagetitle') </span>
                    </h5>
                </div>
                <div class="col-sm-12 col-md-2 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <button type="button" class="btn btn-success text-white ms-4" onclick="addSetup()"> <i class="ti ti-plus ti-xs"></i> Add New</button>

                    </div>
                </div>
            </div>

            <div class="card-datatable table-responsive">

                <table class="table text-center border-top mb-5" id="datatable">
                    <thead class="bg-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>

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
            <form id="setupManager" action="{{route('resourceupdate')}}" method="post">
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="scheme">
                        {{ csrf_field() }}

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



<div class="modal fade bd-example-modal-xl" id="mobileModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mobile Recharge Commission</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">

                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>
                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                        <button class="btn b-primary btn-raised legitRipple" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body"> <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>
                                <th>Commission Type</th>
                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">

                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>
                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">

                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Commission Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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

<div class="modal fade bd-example-modal-xl" id="dmtModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Money Transfer</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">

                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Type</th>

                                <th>Whitelable</th>
                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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

<div class="modal fade bd-example-modal-xl" id="AadharpayModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Aadharpay Charge</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">

                <div class="modal-body">


                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Type</th>

                                <th>Whitelable</th>

                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <form class="commissionForm">
                <div class="modal-body">

                    <input type="hidden" name="actiontype" value="commission">
                    <input type="hidden" name="scheme_id" value="">
                    <table class="table table-bordered m-0">
                        <thead class="bg-light">
                            <tr>
                                <th>Operator</th>

                                <th>Type</th>

                                <th>Whitelable</th>

                                <th>Master Distributor</th>
                                <th>Distributor</th>
                                <th>Retailer</th>
                            </tr>
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

<div class="modal fade bd-example-modal-xl" id="commissionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title ">
                    <span class="schemename"></span>

                </h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
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


@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/resource{{$type}}/0";

        var onDraw = function() {
            $('input.schemeStatusHandler').on('click', function(evt) {
                evt.stopPropagation();
                var ele = $(this);
                var id = $(this).val();
                var status = "0";
                if ($(this).prop('checked')) {
                    status = "1";
                }

                $.ajax({
                        url: `{{route('resourceupdate')}}`,
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        dataType: 'json',
                        data: {
                            'id': id,
                            'status': status,
                            "actiontype": "scheme"
                        }
                    })
                    .done(function(data) {
                        if (data.status == "success") {
                            notify("Scheme Updated", 'success');
                            $('#datatable').dataTable().api().ajax.reload();
                        } else {
                            if (status == "1") {
                                ele.prop('checked', false);
                            } else {
                                ele.prop('checked', true);
                            }
                            notify("Something went wrong, Try again.", 'warning');
                        }
                    })
                    .fail(function(errors) {
                        if (status == "1") {
                            ele.prop('checked', false);
                        } else {
                            ele.prop('checked', true);
                        }
                        showError(errors, "withoutform");
                    });
            });
        };

        var options = [{
                "data": "id"
            },
            {
                "data": "name"
            },
            {
                "data": "name",
                render: function(data, type, full, meta) {
                    var check = "";
                    if (full.status == "1") {
                        check = "checked='checked'";
                    }

                    return ` <div class="custom-control custom-switch custom-control-inline">
                              <input type="checkbox" class="custom-control-input schemeStatusHandler" id="schemeStatus_${full.id}" ${check} value="` + full.id + `" actionType="` + type + `">
                              <label class="custom-control-label" for="schemeStatus_${full.id}"></label>
                           </div>`;

                }
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    var menu = ``;

                    menu += `<li class="dropdown-header">Commission</li>
                    <a href="javascript:void(0)"  class="dropdown-item" onclick="commission(` + full.id + `, 'mobile','mobileModal')">Mobile Recharge</a>
                                <a href="javascript:void(0)"  class="dropdown-item" onclick="commission(` + full.id + `, 'dth','dthModal')">Dth Recharge</a>`;
                    menu += `<a href="javascript:void(0)"  class="dropdown-item" onclick="commission(` + full.id + `, 'electricity','electricModal')">Online Electricity Bill</a>`;
                    menu += `<a href="javascript:void(0)"  class="dropdown-item" onclick="commission(` + full.id + `, 'electricity','electricOfflineModal')">Offline Electricity Bill</a>`;
                    menu += `<a href="javascript:void(0)"  class="dropdown-item" onclick="commission(` + full.id + `, 'aeps','aepsModal')">AePS</a>`;
                    menu += `<a href="javascript:void(0)"  class="dropdown-item" onclick="commission(` + full.id + `, 'aeps','cmsModal')">CMS</a>`;
                    menu += `<a href="javascript:void(0)"  class="dropdown-item" onclick="commission(` + full.id + `, 'aeps','matmModal')">Matm</a> <hr>`;

                    menu += `<li class="dropdown-header">Charge</li>
                    <a href="javascript:void(0)"  class="dropdown-item" onclick="commission(` + full.id + `, 'dmt','dmtModal')">DMT</a>`;
                    menu += `<a href="javascript:void(0)"  class="dropdown-item" onclick="commission(` + full.id + `, 'Aadharpay','AadharpayModal')">Aadharpay</a>`;


                    var out = `<button type="button" class="btn btn-primary btn-xs" onclick="editSetup(this)">Edit</button>
                                <button type="button" class="btn btn-primary btn-xs" onclick="viewCommission(` + full.id + `, '` + full.name + `')"> View Commission</button>
                                <div class="btn-group" role="group">
                                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Commission/Charge 
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

        $("#setupManager").validate({
            rules: {
                name: {
                    required: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter bank name",
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
                var form = $('#setupManager');
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

        $('form.commissionForm').submit(function() {
            var form = $(this);
            form.closest('.modal').find('tbody').find('span.pull-right').remove();
            $(this).ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button[type="submit"]').button('loading');
                },
                complete: function() {
                    form.find('button[type="submit"]').button('reset');
                },
                success: function(data) {
                    $.each(data.status, function(index, values) {
                        if (values.id) {
                            form.find('input[value="' + index + '"]').closest('tr').find('td').eq(0).append('<span class="pull-right text-success"><i class="fa fa-check"></i></span>');
                        } else {
                            form.find('input[value="' + index + '"]').closest('tr').find('td').eq(0).append('<span class="pull-right text-danger"><i class="fa fa-times"></i></span>');
                            if (values != 0) {
                                form.find('input[value="' + index + '"]').closest('tr').find('input[name="value[]"]').closest('td').append('<span class="text-danger pull-right"><i class="fa fa-times"></i> ' + values + '</span>');
                            }
                        }
                    });

                    setTimeout(function() {
                        form.find('span.pull-right').remove();
                    }, 10000);
                },
                error: function(errors) {
                    showError(errors, form);
                }
            });
            return false;
        });

        $("#setupModal").on('hidden.bs.modal', function() {
            $('#setupModal').find('.msg').text("Add");
            $('#setupModal').find('form')[0].reset();
        });

    });

    function addSetup() {
        $('#setupModal').find('.msg').text("Add");
        $('#setupModal').find('input[name="id"]').val("new");
        $('#setupModal').modal('show');
    }

    function editSetup(ele) {
        var id = $(ele).closest('tr').find('td').eq(0).text();
        var name = $(ele).closest('tr').find('td').eq(1).text();

        $('#setupModal').find('.msg').text("Edit");
        $('#setupModal').find('input[name="id"]').val(id);
        $('#setupModal').find('input[name="name"]').val(name);
        $('#setupModal').modal('show');
    }

    function commission(id, type, modal) {
        $.ajax({
                url: `{{url('resources/get')}}/` + type + "/commission",
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
                if (data.length > 0) {
                    $.each(data, function(index, values) {
                        if (type != "gst" && type != "itr") {
                            @if(Myhelper::hasRole('admin'))
                            $('#' + modal).find('input[value="' + values.slab + '"]').closest('tr').find('select[name="type[]"]').val(values.type);
                            @endif
                        }
                        $('#' + modal).find('input[value="' + values.slab + '"]').closest('tr').find('input[name="whitelable[]"]').val(values.whitelable);
                        $('#' + modal).find('input[value="' + values.slab + '"]').closest('tr').find('input[name="md[]"]').val(values.md);
                        $('#' + modal).find('input[value="' + values.slab + '"]').closest('tr').find('input[name="distributor[]"]').val(values.distributor);
                        $('#' + modal).find('input[value="' + values.slab + '"]').closest('tr').find('input[name="retailer[]"]').val(values.retailer);
                    });
                }
            })
            .fail(function(errors) {
                notify('Oops', errors.status + '! ' + errors.statusText, 'warning');
            });

        $('#' + modal).find('input[name="scheme_id"]').val(id);
        $('#' + modal).modal();
    }

    @if(isset($mydata['schememanager']) && $mydata['schememanager'] -> value == "all")

    function viewCommission(id, name) {
        if (id != '') {
            $.ajax({
                    url: '{{route("getMemberPackageCommission")}}',
                    type: 'post',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "scheme_id": id
                    },
                    beforeSend: function() {
                        swal({
                            title: 'Wait!',
                            text: 'Please wait, we are fetching commission details',
                            onOpen: () => {
                                swal.showLoading()
                            },
                            allowOutsideClick: () => !swal.isLoading()
                        });
                    }
                })
                .success(function(data) {
                    swal.close();
                    $('#commissionModal').find('.schemename').text(name);
                    $('#commissionModal').find('.commissioData').html(data);
                    $('#commissionModal').modal('show');
                })
                .fail(function() {
                    swal.close();
                    notify('Somthing went wrong', 'warning');
                });
        }
    }
    @else

    function viewCommission(id, name) {
        if (id != '') {
            $.ajax({
                    url: '{{route("getMemberCommission")}}',
                    type: 'post',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "scheme_id": id
                    },
                    beforeSend: function() {
                        swal({
                            title: 'Wait!',
                            text: 'Please wait, we are fetching commission details',
                            onOpen: () => {
                                swal.showLoading()
                            },
                            allowOutsideClick: () => !swal.isLoading()
                        });
                    }
                })
                .success(function(data) {
                    swal.close();
                    $('#commissionModal').find('.schemename').text(name);
                    $('#commissionModal').find('.commissioData').html(data);
                    $('#commissionModal').modal('show');
                })
                .fail(function() {
                    swal.close();
                    notify('Somthing went wrong', 'warning');
                });
        }
    }
    @endif
</script>
@endpush
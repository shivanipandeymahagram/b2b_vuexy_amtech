@extends('layouts.app')
@section('title', "Loan Enquiry")
@section('pagetitle', "Loan Enquiry")

@php
$table = "yes";
$export = "loandata";
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
                            <th>Order ID</th>
                            <th>User Details</th>
                            <th>Ref. Details</th>
                            <th>Customer Name</th>
                            <th>Amount</th>
                            <th>Aadhar No.</th>
                            <th>Pancard No.</th>
                            <th>Address</th>
                            <th>Pincode</th>
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

@push('style')

@endpush

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/loanenquirystatement/{{$id}}";
        var onDraw = function() {};
        var options = [{
                "data": "id",
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
                "data": "ref"
            },
            {
                "data": "name",
                render: function(data, type, full, meta) {
                    return full.c_name;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Amount - <i class="fa fa-inr"></i> ` + full.loanamount;
                }
            },
            {
                "data": "adhar"
            },
            {
                "data": "pan"
            },
            {
                "data": "address",
                render: function(data, type, full, meta) {
                    return `Address - ` + full.address + `<br>City-` + full.city + `<br>State-` + full.state;
                }
            },
            {
                "data": "pincode"
            },

        ];

        datatableSetup(url, options, onDraw);


        $("#editUtiModal").on('hidden.bs.modal', function() {
            $('#setupModal').find('form')[0].reset();
        });
    });
</script>
@endpush
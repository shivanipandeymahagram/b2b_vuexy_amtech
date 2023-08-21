@extends('layouts.app')
@section('title', "MicroAtm Pending Request")
@section('pagetitle', "MicroAtm Pending Request")

@php
$table = "yes";
$export = "microfundrequestview";
$status['type'] = "Fund";
$status['data'] = [
"success" => "Success",
"pending" => "Pending",
"failed" => "Failed",
"approved" => "Approved",
"rejected" => "Rejected",
];

$product['type'] = "Transaction";
$product['data'] = [
"wallet" => "Move To Wallet",
"bank" => "Move To Bank"
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
                            <th> Bank Details</th>
                            <th> Description</th>
                            <th>Remark</th>
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
@if (Myhelper::hasRole('admin'))

<div class="modal fade" id="transferModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Fund Request From <span class="payeename text-capitalize"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
      
                </button>
            </div>
            <form id="transferForm" method="post" action="{{ route('fundtransaction') }}">
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id">
                    <input type="hidden" name="type" value="microatmtransfer">
                    <div class="form-group my-1">
                        <label>Action Type</label>
                        <select class="form-control my-1" name="status" required="">
                            <option value="">Select Action Type</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>

                    <div class="form-group my-1">
                        <label>Ref No</label>
                        <input text="text" name="refno" class="form-control my-1" required>
                    </div>

                    <div class="form-group my-1">
                        <label>Remark</label>
                        <textarea name="remark" class="form-control my-1" rows="3" placeholder="Enter Value"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </form>
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
        var url = "{{url('statement/fetch')}}/microatmfundrequestview/0";
        var onDraw = function() {};
        var options = [{
                "data": "name",
                render: function(data, type, full, meta) {
                    var out = '';
                    if (full.api) {
                        out += `<span class='myspan'>` + full.api.api_name + `</span><br>`;
                    }
                    out += `<span class='text-inverse'>` + full.id + `</span><br><span style='font-size:12px'>` + full.created_at + `</span>`;
                    return out;
                }
            },
            {
                "data": "account",
                render: function(data, type, full, meta) {
                    return full.user.name + `<br>` + full.user.mobile;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.type == "wallet") {
                        return "Wallet"
                    } else {
                        if (full.account != 'null' && full.account != '' && full.account != null) {
                            return full.account + " ( " + full.bank + " )<br>" + full.ifsc;
                        } else {
                            return full.user.account + " ( " + full.user.bank + " )<br>" + full.user.ifsc;
                        }
                    }
                }
            },
            {
                "data": "description",
                render: function(data, type, full, meta) {
                    return `<span class='text-inverse'><i class="fa fa-rupee"></i> ` + full.amount + `</span> / ` + full.type;
                }
            },
            {
                "data": "remark"
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    if (full.status == "approved") {
                        var btn = '<span class="label label-success text-uppercase"><b>' + full.status + '</b></span>';
                    } else if (full.status == 'pending') {
                        var btn = '<span class="label label-warning text-uppercase"><b>' + full.status + '</b></span>';
                    } else {
                        var btn = '<span class="label label-danger text-uppercase"><b>' + full.status + '</b></span>';
                    }
                    @if(Myhelper::hasRole('admin'))
                    btn += `<br><button class="btn bg-slate btn-xs waves-effect mt-10" onclick="transfer('` + full.id + `', '` + full.user.name + `')"><i class="fa fa-pencil"></i> Edit</button>`;
                    @endif
                    return btn;
                }
            }
        ];

        datatableSetup(url, options, onDraw);

        $('form#transferForm').submit(function() {
            var form = $(this);
            $(this).ajaxSubmit({
                dataType: 'json',
                beforeSubmit: function() {
                    form.find('button:submit').button('loading');
                },
                success: function(data) {
                    if (data.status == "success") {
                        form.find('button:submit').button('reset');
                        form[0].reset();
                        notify('Fund request successfully updated', 'success');
                        $('#transferModal').modal('hide');
                        $('#datatable').dataTable().api().ajax.reload();
                    } else {
                        notify('Something went wrong', 'danger');
                    }
                },
                error: function(errors) {
                    form.find('button:submit').button('reset');
                    notify(errors.statusText, 'Oops!', 'error');
                }
            });
            return false;
        });

        $("#transferModal").on('hidden.bs.modal', function() {
            $('#transferModal').find('form')[0].reset();
            $('#transferForm').find('input[name="id"]').val('');
            $('#transferModal').find('.payeename').text('');
        });
    });

    function transfer(id, name) {
        $('#transferModal').find('.payeename').text(name);
        $('#transferForm').find('input[name="id"]').val(id);
        $('#transferModal').modal();
    }
</script>
@endpush
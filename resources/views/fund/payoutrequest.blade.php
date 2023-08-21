@extends('layouts.app')
@section('title', "Payout Pending Request")
@section('pagetitle', "Payout Pending Request")

@php
$table = "yes";
$export = "aepsfundrequestview";
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
                        <span>@yield('pagetitle') - Table</span>
                    </h5>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                        <tr>
                            <th>#</th>
                            <th>User Details</th>
                            <th>Bank Details</th>
                            <th>Reference Details</th>
                            <th>Amount</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Fund Request From</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="transferForm" method="post" action="{{ route('statementUpdate') }}">
                <div class="modal-body">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id">
                    <input type="hidden" name="actiontype" value="payout">
                    <div class="form-group">
                        <label>Action Type</label>
                        <select class="form-control" name="status" required="">
                            <option value="">Select Action Type</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Reject</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Ref No</label>
                        <input text="text" name="payoutref" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Remark</label>
                        <textarea name="remark" class="form-control" rows="3" placeholder="Enter Value"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                   <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
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
        var url = "{{url('statement/fetch')}}/aepspayoutrequestview/0";
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
                    return full.username ;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    if (full.type == "wallet") {
                        return "Wallet"
                    } else {
                        if (full.account != '' && full.account != null) {
                            return full?.account + " ( " + full?.bank + " )<br>" + full?.ifsc;
                        } else {
                            return full?.user?.account + " ( " + full?.user?.bank + " )<br>" + full?.user?.ifsc;
                        }
                    }
                }
            },
            {
                "data": "account",
                render: function(data, type, full, meta) {
                    return "RRN - " + full?.payoutref + "<br>Payout Id - " + full?.payoutid;
                }
            },
            {
                "data": "description",
                render: function(data, type, full, meta) {
                    return `<span class='text-inverse'><i class="fa fa-rupee"></i> ` + full?.amount;
                }
            },
            {
                "data": "remark"
            },
            {
                "data": "action",
                render: function(data, type, full, meta) {
                    if (full.status == "approved") {
                        var btn = `<span class="badge badge-success">${full?.status}</span>`;
                    } else if (full.status == 'pending') {
                        var btn = `<span class="badge badge-warning">${full?.status}</span>`;
                    } else {
                        var btn = `<span class="badge badge-danger">${full?.status}</span>`;
                    }
                    @if(Myhelper::hasRole('admin'))
                    if (full?.pay_type != "payout" && (full?.status == 'pending' || full?.status == 'approved')) {
                        btn += `<br><i class="fa fa-pencil" onclick="transfer('` + full.username + `')"></i>`;
                    }
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
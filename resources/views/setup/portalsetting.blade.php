@extends('layouts.app')
@section('title', 'Portal Settings')
@section('pagetitle', 'Portal Settings')

@section('content')

<div class="row">
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Wallet Settlement Type</span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="settlementtype">
                <input type="hidden" name="name" value="Wallet Settlement Type">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Settlement Type</label>
                            <select name="value" class="form-control my-1" required>
                                <option value="">Select Type</option>
                                <option value="auto" {{(isset($settlementtype->value) && $settlementtype->value == "auto") ? "selected=''" : ''}}>Auto</option>
                                <option value="mannual" {{(isset($settlementtype->value) && $settlementtype->value == "mannual") ? "selected=''" : ''}}>Mannual</option>

                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>
                </div>
            </form>
        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Bank Settlement Type</span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="settlementtype">
                <input type="hidden" name="name" value="Wallet Settlement Type">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Settlement Type</label>
                            <select name="value" class="form-control my-1" required>
                                <option value="">Select Type</option>
                                <option value="auto" {{(isset($banksettlementtype->value) && $banksettlementtype->value == "auto") ? "selected=''" : ''}}>Auto</option>
                                <option value="mannual" {{(isset($banksettlementtype->value) && $banksettlementtype->value == "mannual") ? "selected=''" : ''}}>Mannual</option>
                                <option value="down" {{(isset($banksettlementtype->value) && $banksettlementtype->value == "down") ? "selected=''" : ''}}>Down</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>

        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Bank Settlement Charge
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="settlementtype">
                <input type="hidden" name="name" value="Wallet Settlement Type">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Charge</label>
                            <input type="number" name="value" value="{{$settlementcharge->value ?? ''}}" class="form-control my-1" required="" placeholder="Enter value">

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>

    </div>
    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Bank Settlement Charge Upto 25000
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="impschargeupto25">
                <input type="hidden" name="name" value="Bank Settlement Charge Upto 25000">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 ">
                            <label>Charge</label>
                            <input class="form-control my-1" name="charge" value="{{$impschargeupto25->value ?? ''}}" placeholder="Charge" />
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>

    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>login with OTP
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="otplogin">
                <input type="hidden" name="name" value="Login required otp">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Login Type</label>
                            <select name="value" class="form-control my-1" required>
                                <option value="">Select Type</option>
                                <option value="yes" {{(isset($otplogin->value) && $otplogin->value == "yes") ? "selected=''" : ''}}>With Otp</option>
                                <option value="no" {{(isset($otplogin->value) && $otplogin->value == "no") ? "selected=''" : ''}}>Without Otp</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>

    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Sending mail id for otp
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="otpsendmailid">
                <input type="hidden" name="name" value="Sending mail id for otp">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Mail Id</label>
                            <input type="text" name="value" value="{{$otpsendmailid->value ?? ''}}" class="form-control my-1" required="" placeholder="Enter value">

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>
    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Sending mailer name id for otp
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="otpsendmailname">
                <input type="hidden" name="name" value="Sending mailer name id for otp">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Mailer Name</label>
                            <input type="text" name="value" value="{{$otpsendmailname->value ?? ''}}" class="form-control my-1" required="" placeholder="Enter value">

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>
    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Bc Id for DMT
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="bcid">
                <input type="hidden" name="name" value="Bc Id for dmt">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Bcid</label>
                            <input type="text" name="value" value="{{$bcid->value ?? ''}}" class="form-control my-1" required="" placeholder="Enter value">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>
    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>CP Id For DMT
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="cpid">
                <input type="hidden" name="name" value="CP Id for dmt">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>CP Id</label>
                            <input type="text" name="value" value="{{$cpid->value ?? ''}}" class="form-control my-1" required="" placeholder="Enter value">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>
    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Transaction Id Code
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="transactioncode">
                <input type="hidden" name="name" value="Transaction Id Code">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Code</label>
                            <input type="text" name="value" value="{{$transactioncode->value ?? ''}}" class="form-control my-1" required="" placeholder="Enter value">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>
    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Main Wallet Locked Amount
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="mainlockedamount">
                <input type="hidden" name="name" value="Main Wallet Locked Amount">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Amount</label>
                            <input type="text" name="value" value="{{$mainlockedamount->value ?? ''}}" class="form-control my-1" required="" placeholder="Enter value">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>
    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Aeps Bank Settlement Locked Amount
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="aepslockedamount">
                <input type="hidden" name="name" value="Aeps Bank Settlement Locked Amount">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Amount</label>
                            <input type="text" name="value" value="{{$aepslockedamount->value ?? ''}}" class="form-control my-1" required="" placeholder="Enter value">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>
    </div>

    <div class="col-4 col-xl-4 col-sm-4 order-1 order-lg-2 my-3 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Aeps Settlement Time
                        </span>
                    </h5>
                </div>
            </div>
            <form class="actionForm" action="{{route('setupupdate')}}" method="post">
                {{ csrf_field() }}
                <input type="hidden" name="actiontype" value="portalsetting">
                <input type="hidden" name="code" value="aepsslabtime">
                <input type="hidden" name="name" value="Aeps Settlement Time">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Time (Comma Seperated)</label>
                            <textarea name="value" class="form-control my-1" required="" placeholder="Enter value">{{$aepsslabtime->value ?? ''}}</textarea>
                        </div>
                        <p class="text-muted">Example - 11:00 Am, 2:00 PM</p>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update Info</button>

                </div>
            </form>
        </div>
    </div>


</div>


@endsection
@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('.actionForm').submit(function(event) {
            var form = $(this);
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
                            $('[name="api_id"]').select2().val(null).trigger('change');
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
            return false;
        });

        $("#setupModal").on('hidden.bs.modal', function() {
            $('#setupModal').find('.msg').text("Add");
            $('#setupModal').find('form')[0].reset();
        });

        $('')
    });
</script>
@endpush
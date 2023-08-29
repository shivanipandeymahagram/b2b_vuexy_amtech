@extends('layouts.app')
@section('title', ucfirst($type).' Recharge')
@section('pagetitle', ucfirst($type).' Recharge')
@php
$table = "yes";
@endphp

@section('content')
<div class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="card card mb-3">
                <div class="card-body">
                    <h4 class="card-title">{{ucfirst($type)}} Recharge</h4>
                    <form id="rechargeForm" action="{{route('rechargepay')}}" method="post" autocomplete="off">
                        {{ csrf_field() }}
                        <input type="hidden" name="type" value="{{$type}}">
                        <div class="row">
                            <div class="form-group col-sm-12 col-lg-3 my-1">
                                <label>{{ucfirst($type)}} Number <span class="text-danger fw-bold">*</span></label>
                                <input type="text" name="number" class="form-control my-1" placeholder="Enter {{$type}} number" required="">
                                <!--onchange="getoperator()"-->
                            </div>
                            <div class="form-group col-sm-12 col-lg-3 my-1">
                                <label>{{ucfirst($type)}} Operator <span class="text-danger fw-bold">*</span></label>
                                <select name="provider_id" class="form-control my-1" required="" onchange="getdthinfo()">
                                    <option value="">Select Operator</option>
                                    @foreach ($providers as $provider)
                                    <option value="{{$provider->id}}">{{$provider->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @if($type == "mobile")
                            <div class="form-group col-sm-12 col-lg-3 my-1">
                                <label>Circle</label>
                                <select name="circle" class="form-control my-1" id="circle" required>
                                    <option value="">Select Circle</option>
                                    @foreach ($circles as $circle)
                                    <option value="{{$circle->maha_circle_name}}">{{$circle->maha_circle_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="form-group col-sm-12 col-lg-3 my-1">
                                <label>Recharge Amount <span class="text-danger fw-bold">*</span></label>
                                <input type="text" name="amount" class="form-control my-1" placeholder="Enter  amount" required="">
                            </div>
                            <div class="form-group col-sm-12 col-lg-3 my-1">
                                <label>T-Pin <span class="text-danger fw-bold">*</span></label>
                                <input type="password" name="pin" class="form-control my-1" placeholder="Enter transaction pin" required="">
                                <a href="{{url('profile/view?tab=pinChange')}}" target="_blank" class="text-primary pull-right">Generate Or Forgot Pin??</a>
                            </div>

                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Paying"><b><i class=" icon-paperplane"></i></b> Pay Now</button>
                                <button type="button" class="btn submit-button btn-success" onclick="getplan()">GET Plan</button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-sm-12 ">

            <div class="card mb-3">
                <div class="card-body">
                    <h4 class="card-title">Recent {{ucfirst($type)}} Recharge</h4>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover" id="datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Recharge Details</th>
                                    <th>Amount/Commission</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="planModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Recharge Plans</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body planData">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@if($type == "dth")


<div class="modal fade" id="dthinfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">DTH Customer Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Name</th>
                        <td class="name"></td>
                    </tr>
                    <tr>
                        <th>Plan Name</th>
                        <td class="planname"></td>
                    </tr>
                    <tr>
                        <th>Balance</th>
                        <td class="balance"></td>
                    </tr>
                    <tr>
                        <th>Monthly Plan</th>
                        <td class="mplan"></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td class="status"></td>
                    </tr>
                    <tr>
                        <th>Recharge Date</th>
                        <td class="date"></td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endif
@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        var url = "{{url('statement/fetch')}}/{{$type}}statement/0";

        var onDraw = function() {};

        var options = [{
                "data": "name",
                render: function(data, type, full, meta) {
                    return `<div>
                            <span class='text-inverse m-l-10'><b>` + full.id + `</b> </span>
                            <div class="clearfix"></div>
                        </div><span style='font-size:13px' class="pull=right">` + full.created_at + `</span>`;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Number - ` + full.number + `<br>Operator - ` + full.providername;
                }
            },
            {
                "data": "bank",
                render: function(data, type, full, meta) {
                    return `Amount - <i class="fa fa-inr"></i> ` + full.amount + `<br>Profit - <i class="fa fa-inr"></i> ` + full.profit;
                }
            },
            {
                "data": "status",
                render: function(data, type, full, meta) {
                    if (full.status == "success") {
                        var out = `<span class="label label-success">Success</span>`;
                    } else if (full.status == "pending") {
                        var out = `<span class="label label-warning">Pending</span>`;
                    } else if (full.status == "reversed") {
                        var out = `<span class="label bg-slate">Reversed</span>`;
                    } else {
                        var out = `<span class="label label-danger">Failed</span>`;
                    }
                    return out;
                }
            }
        ];

        datatableSetup(url, options, onDraw);

        $("#rechargeForm").validate({
            rules: {
                provider_id: {
                    required: true,
                    number: true,
                },
                number: {
                    required: true,
                    number: true,
                    minlength: 8
                },
                amount: {
                    required: true,
                    number: true,
                    min: 10
                },
            },
            messages: {
                provider_id: {
                    required: "Please select {{$type}} operator",
                    number: "Operator id should be numeric",
                },
                number: {
                    required: "Please enter {{$type}} number",
                    number: "Mobile number should be numeric",
                    min: "Mobile number length should be atleast 8",
                },
                amount: {
                    required: "Please enter {{$type}} amount",
                    number: "Amount should be numeric",
                    min: "Min {{$type}} amount value rs 10",
                }
            },
            errorElement: "p",
            errorPlacement: function(error, element) {
                if (element.prop("tagName").toLowerCase() === "select") {
                    error.insertAfter(element.closest(".form-group"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function() {
                var form = $('#rechargeForm');
                var id = form.find('[name="id"]').val();
                form.ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button[type="submit"]').button('loading');
                    },
                    success: function(data) {
                        form.find('button[type="submit"]').button('reset');
                        if (data.status == "success" || data.status == "pending") {
                            getbalance();
                            form[0].reset();
                            form.find('select').val(null).trigger('change')
                            form.find('button[type="submit"]').button('reset');
                            notify("Recharge Successfully Submitted", 'success');
                            $('#datatable').dataTable().api().ajax.reload();
                        } else {
                            notify("Recharge " + data.status + "! " + data.description, 'error');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });
    });

    function activeTab(targetEle) {
        // alert(targetEle);
        $('.class_for_remove').removeClass('show active');
        $(`#${targetEle}`).addClass('show active');
    }

    function getplan() {
        var operator = $('[name="provider_id"]').val();
        var number = $('[name="number"]').val();
        var circle = $('[name="circle"]').val();
        var type = $('[name="type"]').val();

        if (number != '' && operator != '' && circle != '') {
            $.ajax({
                url: '{{route("getplan")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "operator": operator,
                    'number': number,
                    'circle': circle,
                    'type': type
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
                },
                success: function(data) {
                    swal.close();
                    alert(data);
                    if ((typeof data.data[0] !== 'undefined' && data.data[0] != null) || data.data.desc == 'Plan Not Available') {

                        notify(' Plan Not Available', 'error');
                        return;
                    }

                    if (data.status == "success") {
                        var head = `<ul class="nav nav-tabs" id="myTab-1" role="tablist">`;
                        var tabdata = ``;

                        var count = 0;

                        $.each(data.data, function(index, val) {
                            count = count + 1;
                            if (count == "1") {
                                var active = "active";
                            } else {
                                var active = "";
                            }

                            head += `<li class="nav-item">
                            <a onClick="activeTab('${count}-tab')" class="nav-link ` + active + `" id="` + count + `-tabLink" data-toggle="tab" href="#` + count + `-tab" role="tab" aria-controls="` + count + `-tab" aria-selected="true">` + index + ` </a>
                            </li>`;
                            var plandata = ``;
                            $.each(val, function(index, value) {


                                @if($type == "mobile")
                                plandata += `<tr><td><button class="btn btn-xs btn-primary" onclick="setAmount('` + value.rs + `')" style="width: 70px;padding:2px 0px;font-size: 15px;"><i class="fa fa-inr"></i> ` + value.rs + `</button></td><td>` + value.validity + `</td><td>` + value.desc + `</td>
                                    </tr>`;
                                @else
                                var rss = '';
                                var validitys = '';
                                $.each(value.rs, function(validity, rs) {
                                    rss = rs;
                                    validitys = validity;
                                });

                                plandata += `<tr><td><button class="btn  btn-primary" onclick="setAmount('` + rss + `')" style="width: 70px;padding:2px 0px;font-size: 15px;"><i class="fa fa-inr"></i> ` + rss + `</button></td><td>` + validitys + `</td><td>` + value.desc + `</td>
                                    </tr>`;
                                @endif
                            });

                            tabdata += `
                            <div class="tab-pane class_for_remove fade show ` + active + `" id="` + count + `-tab">
                            <table class="table table-bordered">
                                  <thead class="thead-light">
                                    <tr>
                                        <th width="150px">Amount</th>
                                        <th width="150px">Validity</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    ` + plandata + `
                                </tbody>
                            </table>
                        </div>
                        `;
                        });

                        head += '</ul>';

                        var htmldata = ` ` + head + `
                            <div class="tab-content" id="myTabContent-2">
                                ` + tabdata + `
                            </div>
                        </div>
                    </div>`;

                        $('.planData').html(htmldata);
                        $('#planModal').modal('show');
                    } else {
                        notify(data.message, 'error');
                    }
                },
                fail: function() {
                    swal.close();
                    notify('Somthing went wrong', 'error');
                }
            })

        } else {
            notify('Mobile number and operator field required', 'error');
        }

    }

    function getoperator() {
        @if($type == "mobile" || $type == "dth")
        var number = $('[name="number"]').val();
        if (number != '') {
            $.ajax({
                url: '{{route("getoperator")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'number': number,
                    "type": "{{$type}}"
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
                },
                success: function(data) {
                    swal.close();
                    console.log(data);
                    if (data.status == "success") {
                        $("[name='provider_id']").val(data.data).trigger('change');
                        $("[name='circle']").val(data.circle);
                        $("[name='providername']").val(data.providername);
                    } else {
                        notify(data.message, 'error');
                    }
                },
                fail: function() {
                    swal.close();
                    notify('Somthing went wrong', 'error');
                }
            })

        } else {
            notify('Mobile number and operator field required', 'error');
        }
        @endif
    }

    function getdthinfo() {
        @if($type == "dth")
        var number = $('[name="number"]').val();
        var operator = $('[name="provider_id"]').val();
        if (number != '' && operator != '') {
            $.ajax({
                url: '{{route("getdthinfo")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    'number': number,
                    "operator": operator
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
                },
                success: function(data) {
                    swal.close();
                    console.log(data);
                    if (data.message == "Success") {
                        $('#dthinfo').modal('show');
                        $('.name').text(data.data.customerName);
                        $('.planname').text(data.data.planname);
                        $('.balance').text(data.data.Balance);
                        $('.mplan').text(data.data.MonthlyRecharge);
                        $('.date').text(data.data.NextRechargeDate);
                        $('.status').text(data.data.status);
                    } else {
                        $('.dthinfo').hide();
                        notify(data.message, 'error');
                    }
                },
                fail: function() {
                    $('.dthinfo').hide();
                    swal.close();
                    notify('Somthing went wrong', 'error');
                }
            })

        } else {
            notify('Mobile number and operator field required', 'error');
        }
        @endif
    }

    function setAmount(amount) {
        $("[name='amount']").val(amount);
        $('#planModal').modal('hide');
    }
</script>
@endpush
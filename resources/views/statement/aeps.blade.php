@extends('layouts.app')
@section('title', "Aeps Statement")
@section('pagetitle',  "Aeps Statement")

@php
    $table = "yes";
    $export = "aeps";

    $status['type'] = "Report";
    $status['data'] = [
        "success" => "Success",
        "pending" => "Pending",
        "failed" => "Failed",
        "reversed" => "Reversed",
        "refunded" => "Refunded",
    ];
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
                            <th>Type</th>
                            <th>User Details</th>
                            <th>Bank Details</th>
                            <th>Refrences Details</th>
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

@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function () {
        var url = "{{url('statement/fetch')}}/aepsstatement/{{$id}}";
        var onDraw = function() {
        };
        var options = [
            { "data" : "name",
                render:function(data, type, full, meta){
                    return `<div>
                            <span class='text-inverse m-l-10'><b>`+full.id +`</b> </span>
                            <div class="clearfix"></div>
                        </div><span style='font-size:13px' class="pull=right">`+full.created_at+`</span>`;
                }
            },
            { "data" : "name",
                render:function(data, type, full, meta){
                    if(full.transtype == "fund"){
                         return `<div>
                            <span class='text-inverse m-l-10'><b>Fund</b> </span>
                            <div class="clearfix"></div>
                        </div>`;
                    }else{
                    return `<div>
                            <span class='text-inverse m-l-10'><b>`+full.aepstype +`</b> </span>
                            <div class="clearfix"></div>
                        </div>`;
                    }
                }
            },
            { "data" : "username"},
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Adhaar - `+full.aadhar+`<br>Mobile - `+full.mobile;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    return `Ref No. - `+full.refno+`<br>Txnid - `+full.txnid+`<br>Payid - `+full.payid;
                }
            },
            { "data" : "bank",
                render:function(data, type, full, meta){
                    if(full.aepstype == "AP"){
                        return `Amount - <i class="fa fa-inr"></i> `+full.amount+`<br>Charge - <i class="fa fa-inr"></i> `+full.charge;
                    }else if(full.transtype == "fund"){
                          return `Amount - <i class="fa fa-inr"></i> `+full.amount+`<br>Charge - <i class="fa fa-inr"></i> `+full.charge;
                        }else{
                        return `Amount - <i class="fa fa-inr"></i> `+full.amount+`<br>Commission - <i class="fa fa-inr"></i> `+full.charge;
                    }
                }
            },
            
            { "data" : "status",
                render:function(data, type, full, meta){
                    
                    var menu = ``;
                    @if (Myhelper::can('aeps_status'))
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="status(`+full.id+`, 'aeps')"><i class="icon-info22"></i>Check Status</a></li>`;
                    @endif

                    @if (Myhelper::can('aeps_statement_edit'))
                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="editReport(`+full.id+`,'`+full.refno+`','`+full.txnid+`','`+full.payid+`','`+full.remark+`', '`+full.status+`', 'aeps')"><i class="icon-pencil5"></i> Edit</a></li>`;
                    @endif

                    menu += `<li><a href="javascript:void(0)" class="dropdown-item" onclick="complaint(`+full.id+`, 'aeps')"><i class="icon-cogs"></i> Complaint</a></li>`;
                    

                            return `<div class="btn-group" role="group">
                                    <span id="btnGroupDrop1" class="badge ${full.status=='success'? 'badge-success' : full.status=='pending'? 'badge-warning':full.status=='reversed'? 'badge-info':full.status=='complete'? 'badge-primary':'badge-danger'} dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    ` + full.status + `
                                    </span>
                                    <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                       ` + menu + `
                                    </div>
                                 </div>`;

                
                }
            }
        ];

        datatableSetup(url, options, onDraw);
    });

    function viewUtiid(id){
        $.ajax({
            url: `{{url('statement/fetch')}}/utiidstatement/`+id,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType:'json',
            data:{'scheme_id':id}
        })
        .done(function(data) {
            $.each(data, function(index, values) {
                $("."+index).text(values);
            });
            $('#utiidModal').modal();
        })
        .fail(function(errors) {
            notify('Oops', errors.status+'! '+errors.statusText, 'warning');
        });
    }
</script>
@endpush
@extends('layouts.app')
@section('title', 'Admin Profit')
@section('pagetitle',  'Admin Profit')
@php
    $table = "yes";
    $agentfilter = "hide";
    
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
                <div class="col-sm-12 col-md-2 mb-5">
                    <div class="user-list-files d-flex float-right">
                        <button class="btn btn-success text-white ms-2"  onclick="addSetup()">
                            <i class="ti ti-plus ti-xs"></i> Add New</button>
                    </div>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table width="100%" class="table border-top mb-5" id="datatable" role="grid" aria-describedby="user-list-page-info">
                    <thead class=" text-center bg-light">
                    <tr>
                            <th>#</th>
                            <th>Api Name</th>
                            <th>Type</th>
                            <th>Provider Name</th>
                            <th>Commission Type</th>
                            <th>Commission Amount(% or flat)</th>
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


<div id="setupModal" class="modal fade" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h6 class="modal-title"><span class="msg">Add</span> Admin Profit</h6>
            </div>
            <form id="setupManager" action="{{route('statementUpdate')}}" method="post">
                <div class="modal-body">
                    
                    <div class="row">
                        <input type="hidden" name="id">
                        <input type="hidden" name="actiontype" value="admincommission">
                        {{ csrf_field() }}
                        <div class="form-group my-1 col-md-6">
                            <label>Operator Type</label>
                            <select name="type" class="form-control my-1 select" required>
                                <option value="">Select Operator Type</option>
                                <option value="mobile">Mobile</option>
                                <option value="dth">DTH</option>
                                <option value="electricity">Electricity Bill</option>
                                <option value="pancard">Pancard</option>
                                <option value="dmt">Dmt</option>
                                <option value="aeps">Aeps</option>
                                <option value="fund">Fund</option>
                                <option value="insurance">Insurance</option>
                                <option value="fasttag">Fasttag</option>
                                <option value="postpaid">Postpaid</option>
                                <option value="watter">Watter</option>
                                <option value="broadband">Broadband</option>
                                <option value="loggas">Lpg Gas</option>
                                <option value="pipegas">Pipe Gas</option>
                                <option value="landline">Landline</option>
                                <option value="educationfees">Education Fees</option>
                                <option value="microatm">Micro Atm</option>
                            </select>
                        </div>
                    </div>

                    
                    <div class="row">
                        <div class="form-group my-1 col-md-6">
                            <label>Api</label>
                            <select name="api_id" class="form-control my-1 select" required>
                                <option value="">Select Api</option>
                                @foreach ($apis as $api)
                                <option value="{{$api->id}}">{{$api->product}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group my-1 col-md-6">
                            <label>Provider Slab</label>
                            <select name="provider_id" class="form-control my-1 select" required>
                                <option value="">Select Provider</option>
                                @foreach ($providers as $provider)
                                <option value="{{$provider->id}}">{{$provider->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                         <div class="form-group my-1 col-md-6">
                            <label>Provider Slab</label>
                            <select name="commissiontype" class="form-control my-1 select" required>
                                <option value="percent">Percent</option>
                               <option value="flat">Flat</option>
                            </select>
                        </div>
                        <div class="form-group my-1 col-md-6">
                            <label>Commission/charge</label>
                            <input type="text" name="commission" class="form-control my-1" placeholder="Enter value" required="">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-hidden="true">Close</button>
                    <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@push('script')
	<script type="text/javascript">
    $(document).ready(function () {
        var url = "{{url('statement/fetch')}}/setup{{$type}}/0";

        var onDraw = function() {
            $('select').select2();
            
        };

        var options = [
            { "data" : "id"},
            { "data" : "apiname"},
            { "data" : "type"},
            { "data" : "providername"},
            { "data" : "commissiontype"},
            { "data" : "commission"},
            { "data" : "action",
                render:function(data, type, full, meta){
                    return `<button type="button" class="btn btn-primary btn-xs" onclick="editSetup(`+full.id+`,\``+full.type+`\`, \``+full.api_id+`\`,\``+full.provider_id+`\`,\``+full.commissiontype+`\`,\``+full.commission+`\`)"> Edit</button>`;
                }
            },
        ];
        datatableSetup(url, options, onDraw);

        $( "#setupManager" ).validate({
            rules: {
                name: {
                    required: true,
                },
                recharge1: {
                    required: true,
                },
                recharge2: {
                    required: true,
                },
                type: {
                    required: true,
                },
                api_id: {
                    required: true,
                },
            },
            messages: {
                name: {
                    required: "Please enter operator name",
                },
                recharge1: {
                    required: "Please enter value",
                },
                recharge2: {
                    required: "Please enter value",
                },
                type: {
                    required: "Please select operator type",
                },
                api_id: {
                    required: "Please select api",
                }
            },
            errorElement: "p",
            errorPlacement: function ( error, element ) {
                if ( element.prop("tagName").toLowerCase() === "select" ) {
                    error.insertAfter( element.closest( ".form-group" ).find(".select2") );
                } else {
                    error.insertAfter( element );
                }
            },
            submitHandler: function () {
                var form = $('#setupManager');
                var id = form.find('[name="id"]').val();
                form.ajaxSubmit({
                    dataType:'json',
                    beforeSubmit:function(){
                        form.find('button[type="submit"]').button('loading');
                    },
                    success:function(data){
                        if(data.status == "success"){
                            if(id == "new"){
                                form[0].reset();
                                $('[name="api_id"]').select2().val(null).trigger('change');
                            }
                            form.find('button[type="submit"]').button('reset');
                            notify("Task Successfully Completed", 'success');
                            $('#datatable').dataTable().api().ajax.reload();
                        }else{
                            notify(data.status, 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form);
                    }
                });
            }
        });

    	$("#setupModal").on('hidden.bs.modal', function () {
            $('#setupModal').find('.msg').text("Add");
            $('#setupModal').find('form')[0].reset();
        });
    
    });

    function addSetup(){
    	$('#setupModal').find('.msg').text("Add");
    	$('#setupModal').find('input[name="id"]').val("new");
    	$('#setupModal').modal('show');
	}

	function editSetup(id,type, api_id,provider_id,commissiontype,commission){
		$('#setupModal').find('.msg').text("Edit");
    	$('#setupModal').find('input[name="id"]').val(id);
        $('#setupModal').find('[name="type"]').select2().val(type).trigger('change');
        $('#setupModal').find('[name="api_id"]').select2().val(api_id).trigger('change');
        $('#setupModal').find('[name="provider_id"]').select2().val(provider_id).trigger('change');
        $('#setupModal').find('[name="commissiontype"]').select2().val(commissiontype).trigger('change');
        $('#setupModal').find('input[name="commission"]').val(commission);
    	$('#setupModal').modal('show');
    }
    
    function apiUpdate(ele, id){
        var api_id = $(ele).val();
        if(api_id != ""){
            $.ajax({
                url: '{{ route('statementUpdate') }}',
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType:'json',
                data: {'id':id, 'api_id':api_id, "actiontype":"operator"}
            })
            .done(function(data) {
                if(data.status == "success"){
                    notify("Operator Updated", 'success');
                }else{
                    notify("Something went wrong, Try again." ,'warning');
                }
                $('#datatable').dataTable().api().ajax.reload();
            })
            .fail(function(errors) {
                showError(errors, "withoutform");
            });
        }
    }
</script>
@endpush
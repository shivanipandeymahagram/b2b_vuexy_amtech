@extends('layouts.app')
@section('title', 'Scheme Manager')
@section('pagetitle', 'Scheme Manager')

@section('content')
<div class="content">

    <div class="row mt-4">
        <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
            <div class="card">
                <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                    <div class="card-title mb-0">
                        My Commission
                    </div>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs nav-pills" role="tablist">
                    @foreach ($commission as $key => $value)
                        <li class="nav-item">
                            <button type="button" class="nav-link {{($key == 'mobile') ? 'active' : ''}}" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-{{$key}}" aria-controls="navs-justified-home" aria-selected="true">
                                <i class="tf-icons ti ti-home ti-xs me-1"></i> {{ucfirst($key)}}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                    
                    <div class="tab-content">

                        @if(isset($mydata['schememanager']) && $mydata['schememanager']->value == "admin")
                        @foreach ($commission as $key => $value)
                        <div class="tab-pane fade my-2 show {{($key == 'mobile') ? 'active' : ''}}"   id="navs-justified-{{$key}}" role="tabpanel">

                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                        <th>Provider</th>
                                        <th>Type</th>
                                        @if(Myhelper::hasRole('whitelable'))
                                        <th>Whitelable</th>
                                        @endif
                                        @if(Myhelper::hasRole('md'))
                                        <th>Md</th>
                                        @endif
                                        @if(Myhelper::hasRole('distributor'))
                                        <th>Distributor</th>
                                        @endif
                                        @if(Myhelper::hasRole('retailer'))
                                        <th>Retailer</th>
                                        @endif
                                    </thead>

                                    <tbody>
                                        @foreach ($value as $comm)
                                        <tr>
                                            <td>{{ucfirst($comm->provider->name)}}</td>
                                            <td>{{ucfirst($comm->type)}}</td>
                                            @if(Myhelper::hasRole('whitelable'))
                                            <td>{{ucfirst($comm->whitelable)}}</td>
                                            @endif
                                            @if(Myhelper::hasRole('md'))
                                            <td>{{ucfirst($comm->md)}}</td>
                                            @endif
                                            @if(Myhelper::hasRole('distributor'))
                                            <td>{{ucfirst($comm->distributor)}}</td>
                                            @endif
                                            @if(Myhelper::hasRole('retailer'))
                                            <td>{{ucfirst($comm->retailer)}}</td>
                                            @endif
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                        @else
                        @foreach ($commission as $key => $value)
                        <div class="tab-pane fade my-2 show {{($key == 'mobile') ? 'active' : ''}}" id="navs-justified-{{$key}}" role="tabpanel" >
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="thead-light">
                                        <th>Provider</th>
                                        <th>Type</th>
                                        <th>Value</th>
                                    </thead>

                                    <tbody>
                                        @foreach ($value as $comm)
                                        <tr>
                                            <td>{{ucfirst($comm->provider->name)}}</td>
                                            <td>{{ucfirst($comm->type)}}</td>
                                            <td>{{ucfirst($comm->value)}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endforeach
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
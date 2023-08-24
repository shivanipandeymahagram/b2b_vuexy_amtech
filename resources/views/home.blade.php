@extends('layouts.app')
@section('title', 'Dashboard')
@section('pagetitle', 'Dashboard')
@section('content')
<!-- Content -->


<div id="loading">
   <div id="loading-center">
   </div>
</div>

<div class="row">
   <!-- Main Wallet Balance -->
   <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
      <div class="card">
         <div class="card-body pb-0">
            <div class="card-icon">
               <span class="badge bg-label-success rounded-pill p-2">
                  <i class="ti ti-wallet ti-sm"></i>
               </span>
            </div>
            <h5 class="card-title mb-0 mt-2">{{Auth::user()->mainwallet}}</h5>
            <small>Main Wallet Balance</small>
         </div>
         <div id="revenueGenerated1"></div>
      </div>
   </div>
   <!--/ Main Wallet Balance -->


   <!-- AEPS Balance -->
   <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
      <div class="card">
         <div class="card-body pb-0">
            <div class="card-icon">
               <span class="badge bg-label-primary rounded-pill p-2">
                  <i class="ti ti-brand-paypal ti-sm"></i>
               </span>
            </div>
            <h5 class="card-title mb-0 mt-2">{{Auth::user()->aepsbalance}}</h5>
            <small>AEPS Balance</small>
         </div>
         <div id="revenueGenerated2"></div>
      </div>
   </div>
   <!--/ AEPS Balance -->

   <!-- AEPS Reports -->
   <div class="col-lg-6 mb-4">
      <div class="card h-100">
         <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
            <div class="card-title mb-0">
               <h5 class="mb-0">AEPS</h5>
            </div>
         </div>
         <div class="card-body">
            <div class="border rounded p-3 mt-5">
               <div class="row gap-4 gap-sm-0">
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-success p-1">
                           <i class="ti ti-clock-check ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Success</h6>
                     </div>
                     <h6 class="my-2 pt-1">140 | ₹4.85 Lac</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-warning p-1">
                           <i class="ti ti-clock-exclamation ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Pending</h6>
                     </div>
                     <h6 class="my-2 pt-1">0 | ₹0.00</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-danger p-1">
                           <i class="ti ti-clock-x ti-sm"></i>
                           <!-- <i class="ti ti-brand-paypal ti-sm"></i> -->
                        </div>
                        <h6 class="mb-0">Failed</h6>
                     </div>
                     <h6 class="my-2 pt-1">98 | ₹2.91 Lac</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--/ AEPS Reports -->

   <!-- MATM Reports -->
   <div class="col-lg-6 mb-4">
      <div class="card h-100">
         <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
            <div class="card-title mb-0">
               <h5 class="mb-0">MATM</h5>
            </div>
         </div>
         <div class="card-body">
            <div class="border rounded p-3 mt-5">
               <div class="row gap-4 gap-sm-0">
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-success p-1">
                           <i class="ti ti-clock-check ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Success</h6>
                     </div>
                     <h6 class="my-2 pt-1">157 | ₹5.74 Lac</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-warning p-1">
                           <i class="ti ti-clock-exclamation ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Pending</h6>
                     </div>
                     <h6 class="my-2 pt-1">1 | ₹3.50 K</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-danger p-1">
                           <i class="ti ti-clock-x ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Failed</h6>
                     </div>
                     <h6 class="my-2 pt-1">47 | ₹3.02 Lac</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--/ MATM Reports -->

   <!-- DMT Reports -->
   <div class="col-lg-6 mb-4">
      <div class="card h-100">
         <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
            <div class="card-title mb-0">
               <h5 class="mb-0">DMT</h5>
            </div>
         </div>
         <div class="card-body">
            <div class="border rounded p-3 mt-5">
               <div class="row gap-4 gap-sm-0">
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-success p-1">
                           <i class="ti ti-clock-check ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Success</h6>
                     </div>
                     <h6 class="my-2 pt-1">0 | ₹0.00</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-warning p-1">
                           <i class="ti ti-clock-exclamation ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Pending</h6>
                     </div>
                     <h6 class="my-2 pt-1">0 | ₹0.00</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-danger p-1">
                           <i class="ti ti-clock-x ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Failed</h6>
                     </div>
                     <h6 class="my-2 pt-1">0 | ₹0.00</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--/ DMT Reports -->

   <!-- Recharge Reports -->
   <div class="col-lg-6 mb-4">
      <div class="card h-100">
         <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
            <div class="card-title mb-0">
               <h5 class="mb-0">Recharge</h5>
            </div>

            <!-- </div> -->
         </div>
         <div class="card-body">
            <div class="border rounded p-3 mt-5">
               <div class="row gap-4 gap-sm-0">
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-success p-1">
                           <i class="ti ti-clock-check ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Success</h6>
                     </div>
                     <h6 class="my-2 pt-1">64 | ₹15.69 K</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-warning p-1">
                           <i class="ti ti-clock-exclamation ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Pending</h6>
                     </div>
                     <h6 class="my-2 pt-1">0 | ₹0.00</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-danger p-1">
                           <i class="ti ti-clock-x ti-sm"></i>
                           <!-- <i class="ti ti-brand-paypal ti-sm"></i> -->
                        </div>
                        <h6 class="mb-0">Failed</h6>
                     </div>
                     <h6 class="my-2 pt-1">238 | ₹80.09 K</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--/ Recharge Reports -->

   <!-- UTI PAN Reports -->
   <div class="col-lg-6 mb-4">
      <div class="card h-100">
         <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
            <div class="card-title mb-0">
               <h5 class="mb-0">UTI PAN</h5>
            </div>
         </div>
         <div class="card-body">
            <div class="border rounded p-3 mt-5">
               <div class="row gap-4 gap-sm-0">
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-success p-1">
                           <i class="ti ti-clock-check ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Success</h6>
                     </div>
                     <h6 class="my-2 pt-1">0 | ₹0.00</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-warning p-1">
                           <i class="ti ti-clock-exclamation ti-sm"></i>
                        </div>
                        <h6 class="mb-0">Pending</h6>
                     </div>
                     <h6 class="my-2 pt-1">0 | ₹0.00</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
                  <div class="col-12 col-sm-4">
                     <div class="d-flex gap-2 align-items-center">
                        <div class="badge rounded bg-label-danger p-1">
                           <i class="ti ti-clock-x ti-sm"></i>
                           <!-- <i class="ti ti-brand-paypal ti-sm"></i> -->
                        </div>
                        <h6 class="mb-0">Failed</h6>
                     </div>
                     <h6 class="my-2 pt-1">195 | ₹11.75 Lac</h6>
                     <div class="progress w-75" style="height: 4px">
                        <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <!--/ UTI PAN Reports -->

   <div class="col-xl-4 col-md-6 mb-4">
      <div class="card h-100">
         <div class="card-header d-flex justify-content-between">
            <div class="card-title m-0 me-2">
               <h5 class="m-0 me-2">Balances</h5>
            </div>

         </div>
         <div class="card-body">
            <ul class="p-0 m-0">
               @if (in_array(Auth::user()->role->slug, ['admin']))
               <li class="d-flex mb-4 pb-1 align-items-center">
                  <div class="badge rounded bg-label-success me-2 p-1">
                     <i class="ti ti-currency-dollar ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">Downline Balance</h6>
                        </div>

                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">736</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="secondary" data-series="85"></div>
                  </div>
               </li>
               @endif

               <li class="d-flex mb-4 pb-1 align-items-center">
                  <div class="badge rounded bg-label-success me-2 p-1">
                     <i class="ti ti-currency-dollar ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">Investment Balance</h6>
                        </div>

                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">{{Auth::user()->investment_wallet}}</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="secondary" data-series="85"></div>
                  </div>
               </li>
               @if (in_array(Auth::user()->role->slug, ['admin']))
               <li class="d-flex mb-4 pb-1 align-items-center">
                  <div class="badge rounded bg-label-success me-2 p-1">
                     <i class="ti ti-currency-dollar ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">API Balance</h6>
                        </div>

                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="secondary" data-series="85"></div>
                  </div>
               </li>
               @endif
            </ul>
         </div>
      </div>
   </div>

   @if (in_array(Auth::user()->role->slug, ['whitelable', 'md', 'distributor', 'admin']))
   <div class="col-xl-4 col-md-6 mb-4">
      <div class="card h-100">
         <div class="card-header d-flex justify-content-between">
            <div class="card-title m-0 me-2">
               <h5 class="m-0 me-2">User Counts</h5>
            </div>

         </div>
         <div class="card-body">
            <ul class="p-0 m-0">
               @if (in_array(Auth::user()->role->slug, ['admin']))
               <li class="d-flex mb-4 pb-1 align-items-center">
                  <div class="badge rounded bg-label-success me-2 p-1">
                     <i class="ti ti-refresh ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">White Label</h6>
                        </div>

                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">{{$whitelable}}</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="secondary" data-series="85"></div>
                  </div>
               </li>
               @endif
               @if (in_array(Auth::user()->role->slug, ['admin', 'whitelable']))

               <li class="d-flex mb-4 pb-1 align-items-center">
                  <div class="badge rounded bg-label-danger me-2 p-1">
                     <i class="ti ti-user ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">Master Distributor</h6>
                        </div>
                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">{{$md}}</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="success" data-series="70"></div>
                  </div>
               </li>
               @endif
               @if (in_array(Auth::user()->role->slug, ['admin', 'whitelable', 'md']))

               <li class="d-flex mb-4 pb-1 align-items-center">
                  <div class="badge rounded bg-label-warning me-2 p-1">
                     <i class="ti ti-id ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">Distributor</h6>
                        </div>
                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">{{$distributor}}</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="primary" data-series="25"></div>
                  </div>
               </li>
               @endif

               @if (in_array(Auth::user()->role->slug, ['admin', 'whitelable', 'md', 'distributor']))

               <li class="d-flex mb-4 pb-1 align-items-center">
                  <div class="badge rounded bg-label-primary me-2 p-1">
                     <i class="ti ti-brand-paypal ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">Retailer</h6>
                        </div>

                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">{{$retailer}}</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="danger" data-series="75"></div>
                  </div>
               </li>
               @endif
            </ul>
         </div>
      </div>
   </div>
   @endif

   <div class="col-xl-4 col-md-6 mb-4">
      <div class="card h-100">

         <div class="card-body text-center">
            <div>
               <img src="https://www.livermoreschools.org/cms/lib/CA50000061/Centricity/Domain/72/HelpDesk%20White%20C.png" class="img-responsive mb-10" style="margin: auto; width: 200px">
            </div>

            <a href="#">
               <img src="https://static.vecteezy.com/system/resources/previews/001/991/656/original/customer-service-flat-design-concept-illustration-icon-support-call-center-help-desk-hotline-operator-abstract-metaphor-can-use-for-landing-page-mobile-app-free-vector.jpg" class="img-responsive mb-10" style="margin: auto; width: 150px">
            </a>
            <div class="mt-1">
               <b>Timing - 10 AM to 7 PM</b>
            </div>

            <div class="form-group mb-3">
               <span class="text-semibold">
                  <h5><i class="fa fa-phone"></i></h5>
                  <small> {{$mydata['supportnumber']}}</small>
               </span>
            </div>

            <div class="form-group mb-3">
               <span class="text-semibold">
                  <h5><i class="fa fa-envelope"></i></h5>
                  <small>{{$mydata['supportemail']}}</small>
               </span>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- / Content -->

@if (Myhelper::hasNotRole('admin'))
@if (Auth::user()->kyc == "pendinggg" || Auth::user() -> kyc == "rejected")

<div class="modal fade" id="kycModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Complete your profile with kyc</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
         </div>

         @if (Auth::user()->kyc == "rejected")
         <div class="alert text-white bg-danger" role="alert">
            <div class="iq-alert-text">Kyc Rejected! —{{ Auth::user()->remark }}</div>
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close">
               <i class="ri-close-line"></i>
            </button>
         </div>
         @endif

         <form id="kycForm" action="{{route('profileUpdate')}}" method="post" enctype="multipart/form-data">
            <div class="modal-body">
               <input type="hidden" name="id" value="{{Auth::id()}}">
               <input type="hidden" name="type" value="kycdata">
               <input type="hidden" name="kyc" value="submitted">
               {{ csrf_field() }}
               <div class="row">
                  <div class="form-group col-md-12">
                     <label>Address</label>
                     <textarea name="address" class="form-control" rows="2" required="" placeholder="Enter Value">{{ Auth::user()->address}}</textarea>
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-4">
                     <label>State</label>
                     <select name="state" class="form-control select" required="">
                        <option value="">Select State</option>
                        @foreach ($state as $state)
                        <option value="{{$state->state}}" {{ (Auth::user()->state == $state->state)? 'selected=""': '' }}>{{$state->state}}</option>
                        @endforeach
                     </select>
                  </div>
                  <div class="form-group col-md-4">
                     <label>City</label>
                     <input type="text" name="city" class="form-control" required="" placeholder="Enter Value" value="{{Auth::user()->city}}">
                  </div>
                  <div class="form-group col-md-4">
                     <label>Pincode</label>
                     <input type="number" name="pincode" value="{{ Auth::user()->pincode}}" class="form-control" value="" required="" maxlength="6" minlength="6" placeholder="Enter Value">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-4">
                     <label>Shop Name</label>
                     <input type="text" name="shopname" value="{{ Auth::user()->shopname}}" class="form-control" value="" required="" placeholder="Enter Value">
                  </div>

                  <div class="form-group col-md-4">
                     <label>Pancard Number</label>
                     <input type="text" name="pancard" value="{{ Auth::user()->pancard}}" class="form-control" value="" required="" placeholder="Enter Value">
                  </div>

                  <div class="form-group col-md-4">
                     <label>Adhaarcard Number</label>
                     <input type="text" name="aadharcard" value="{{ Auth::user()->aadharcard}}" class="form-control" value="" required="" placeholder="Enter Value" maxlength="12" minlength="12">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-6">
                     <label>Pancard Pic</label>
                     <input type="file" name="pancardpics" class="form-control" value="" placeholder="Enter Value" required="">
                  </div>

                  <div class="form-group col-md-6">
                     <label>Adhaarcard Pic</label>
                     <input type="file" name="aadharcardpics" class="form-control" value="" placeholder="Enter Value" required="">
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Complete Profile</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endif

@if (Auth::user()->resetpwd == "default")
<div class="modal fade" id="pwdModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change Password</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
         </div>
         <form id="passwordForm" action="{{route('profileUpdate')}}" method="post">
            <div class="modal-body">
               <input type="hidden" name="id" value="{{Auth::id()}}">
               <input type="hidden" name="actiontype" value="password">
               {{ csrf_field() }}

               <div class="row">
                  <div class="form-group col-md-6  ">
                     <label>Old Password</label>
                     <input type="password" name="oldpassword" class="form-control" required="" placeholder="Enter Value">
                  </div>
                  <div class="form-group col-md-6  ">
                     <label>New Password</label>
                     <input type="password" name="password" id="password" class="form-control" required="" placeholder="Enter Value">
                  </div>
               </div>
               <div class="row">
                  <div class="form-group col-md-6  ">
                     <label>Confirmed Password</label>
                     <input type="password" name="password_confirmation" class="form-control" required="" placeholder="Enter Value">
                  </div>
               </div>

            </div>
            <div class="modal-footer">
               <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Change Password</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endif
@endif

<div class="modal fade bd-example-modal-xl" id="noticeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
   <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Necessary Notice ( आवश्यक सूचना )</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

            </button>
         </div>
         <div class="modal-body">
            {!! nl2br($mydata['notice']) !!}
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>

         </div>
      </div>
   </div>
</div>
@endsection

@push('script')
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>

<script>
   $(window).on('load', function() {
      $('#noticeModal').modal('show');
   });

   $(document).ready(function() {

      @if(Myhelper::hasNotRole('admin'))
      @if(Auth::user() -> kyc == "pending" || Auth::user() -> kyc == "rejected")
      $('#kycModal').modal();
      @endif
      @endif

      @if(Myhelper::hasNotRole('admin') && Auth::user() -> resetpwd == "default")
      $('#pwdModal').modal();
      @endif

      // @if($mydata['notice'] != null || $mydata['notice'] != '')
      // $('#noticeModal').modal();
      // @endif


      $("#searchbydate").validate({
         rules: {
            fromdate: {
               required: true,
            },
            todate: {
               required: true,
            }
         },
         messages: {
            fromdate: {
               required: "Please select fromdate",
            },
            todate: {
               required: "Please select fromdate",
            },
         },
         errorElement: "p",
         errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase().toLowerCase() === "select") {
               error.insertAfter(element.closest(".form-group").find(".select2"));
            } else {
               error.insertAfter(element);
            }
         },
         submitHandler: function() {
            var form = $('form#searchbydate');
            form.find('span.text-danger').remove();
            form.ajaxSubmit({
               dataType: 'json',
               beforeSubmit: function() {
                  form.find('button:submit').button('loading');
               },
               complete: function() {
                  form.find('button:submit').button('reset');
               },
               success: function(data) {

                  $.each(data, function(index, value) {
                     $('.' + index).text(value);
                  });
               },
               error: function(errors) {
                  showError(errors, form.find('.modal-body'));
               }
            });
         }
      });

      $("#kycForm").validate({
         rules: {
            state: {
               required: true,
            },
            city: {
               required: true,
            },
            pincode: {
               required: true,
               minlength: 6,
               number: true,
               maxlength: 6
            },
            address: {
               required: true,
            },
            aadharcard: {
               required: true,
               minlength: 12,
               number: true,
               maxlength: 12
            },
            pancard: {
               required: true,
            },
            shopname: {
               required: true,
            },
            pancardpics: {
               required: true,
            },
            aadharcardpics: {
               required: true,
            }
         },
         messages: {
            state: {
               required: "Please select state",
            },
            city: {
               required: "Please enter city",
            },
            pincode: {
               required: "Please enter pincode",
               number: "Mobile number should be numeric",
               minlength: "Your mobile number must be 6 digit",
               maxlength: "Your mobile number must be 6 digit"
            },
            address: {
               required: "Please enter address",
            },
            aadharcard: {
               required: "Please enter aadharcard",
               number: "Mobile number should be numeric",
               minlength: "Your mobile number must be 12 digit",
               maxlength: "Your mobile number must be 12 digit"
            },
            pancard: {
               required: "Please enter pancard",
            },
            shopname: {
               required: "Please enter shop name",
            },
            pancardpics: {
               required: "Please upload pancard pic",
            },
            aadharcardpics: {
               required: "Please upload aadharcard pic",
            }
         },
         errorElement: "p",
         errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase().toLowerCase() === "select") {
               error.insertAfter(element.closest(".form-group").find(".select2"));
            } else {
               error.insertAfter(element);
            }
         },
         submitHandler: function() {
            var form = $("#kycForm");
            form.find('span.text-danger').remove();
            form.ajaxSubmit({
               dataType: 'json',
               beforeSubmit: function() {
                  form.find('button:submit').button('loading');
               },
               complete: function() {
                  form.find('button:submit').button('reset');
               },
               success: function(data) {
                  if (data.status == "success") {
                     form[0].reset();
                     $('select').val('');
                     $('select').trigger('change');
                     notify("Profile Successfully Updated, wait for kyc approval", 'success');
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

      $("#passwordForm").validate({
         rules: {
            @if(!Myhelper::can('member_password_reset'))
            oldpassword: {
               required: true,
               minlength: 6,
            },
            password_confirmation: {
               required: true,
               minlength: 8,
               equalTo: "#password"
            },
            @endif
            password: {
               required: true,
               minlength: 8
            }
         },
         messages: {
            @if(!Myhelper::can('member_password_reset'))
            oldpassword: {
               required: "Please enter old password",
               minlength: "Your password lenght should be atleast 6 character",
            },
            password_confirmation: {
               required: "Please enter confirmed password",
               minlength: "Your password lenght should be atleast 8 character",
               equalTo: "New password and confirmed password should be equal"
            },
            @endif
            password: {
               required: "Please enter new password",
               minlength: "Your password lenght should be atleast 8 character"
            }
         },
         errorElement: "p",
         errorPlacement: function(error, element) {
            if (element.prop("tagName").toLowerCase().toLowerCase() === "select") {
               error.insertAfter(element.closest(".form-group").find(".select2"));
            } else {
               error.insertAfter(element);
            }
         },
         submitHandler: function() {
            var form = $('form#passwordForm');
            form.find('span.text-danger').remove();
            form.ajaxSubmit({
               dataType: 'json',
               beforeSubmit: function() {
                  form.find('button:submit').button('loading');
               },
               complete: function() {
                  form.find('button:submit').button('reset');
               },
               success: function(data) {
                  if (data.status == "success") {
                     form[0].reset();
                     form.closest('.modal').modal('hide');
                     notify("Password Successfully Changed", 'success');
                  } else {
                     notify(data.status, 'warning');
                  }
               },
               error: function(errors) {
                  showError(errors, form.find('.modal-body'));
               }
            });
         }
      });
   });

   const getDashboardData = (start, end) => {

      $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

      $.ajax({
         url: "{{route('home')}}",
         type: "POST",
         headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
         },
         data: {

            fromDate: start?.format('YYYY-MM-DD') || '',
            toDate: end.format('YYYY-MM-DD') || '',
         },
         success: function(resp) {

            $(`#aeps_success`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.aeps.success.toFixed(2) + `</span>`);
            $(`#aeps_successCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.aeps.successCount + `</span>`);
            $(`#aeps_pending`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.aeps.pending.toFixed(2) + `</span>`);
            $(`#aeps_pendingCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.aeps.pendingCount + `</span>`);
            $(`#aeps_failed`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.aeps.failed.toFixed(2) + `</span>`);
            $(`#aeps_failedCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.aeps.failedCount + `</span>`);

            $(`#bbps_success`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.billpayment.success.toFixed(2) + `</span>`);
            $(`#bbps_successCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.billpayment.successCount + `</span>`);
            $(`#bbps_pending`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.billpayment.pending.toFixed(2) + `</span>`);
            $(`#bbps_pendingCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.billpayment.pendingCount + `</span>`);
            $(`#bbps_failed`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.billpayment.failed.toFixed(2) + `</span>`);
            $(`#bbps_failedCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.billpayment.failedCount + `</span>`);

            $(`#money_success`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.money.success.toFixed(2) + `</span>`);
            $(`#money_successCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.money.successCount + `</span>`);
            $(`#money_pending`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.money.pending.toFixed(2) + `</span>`);
            $(`#money_pendingCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.money.pendingCount + `</span>`);
            $(`#money_failed`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.money.failed.toFixed(2) + `</span>`);
            $(`#money_failedCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.money.failedCount + `</span>`);

            $(`#matm_success`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.matm.success.toFixed(2) + `</span>`);
            $(`#matm_successCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.matm.successCount + `</span>`);
            $(`#matm_pending`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.matm.pending.toFixed(2) + `</span>`);
            $(`#matm_pendingCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.matm.pendingCount + `</span>`);
            $(`#matm_failed`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.matm.failed.toFixed(2) + `</span>`);
            $(`#matm_failedCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.matm.failedCount + `</span>`);

            $(`#recharge_success`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.recharge.success.toFixed(2) + `</span>`);
            $(`#recharge_successCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.recharge.successCount + `</span>`);
            $(`#recharge_pending`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.recharge.pending.toFixed(2) + `</span>`);
            $(`#recharge_pendingCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.recharge.pendingCount + `</span>`);
            $(`#recharge_failed`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.recharge.failed.toFixed(2) + `</span>`);
            $(`#recharge_failedCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.recharge.failedCount + `</span>`);

            $(`#utipancard_success`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.utipancard.success.toFixed(2) + `</span>`);
            $(`#utipancard_successCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.utipancard.successCount + `</span>`);
            $(`#utipancard_pending`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.utipancard.pending.toFixed(2) + `</span>`);
            $(`#utipancard_pendingCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.utipancard.pendingCount + `</span>`);
            $(`#utipancard_failed`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + '₹' + resp.utipancard.failed.toFixed(2) + `</span>`);
            $(`#utipancard_failedCount`).html(`<span data-bs-toggle="tooltip" data-bs-placement="top" title="">` + resp.utipancard.failedCount + `</span>`);

         }
      });

   }

   $(function() {

      var start = moment();
      var end = moment();

      $('#reportrange').daterangepicker({
         startDate: start,
         endDate: end,
         ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
         }
      }, getDashboardData);

      getDashboardData(start, end);

   });
</script>

@endpush
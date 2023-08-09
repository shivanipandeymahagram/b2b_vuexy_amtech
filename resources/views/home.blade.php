@extends('.layouts.app')
@section('title', 'Dashboard')
@section('pagetitle', 'Dashboard')
@section('content')
<!-- Content -->


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
            <h5 class="card-title mb-0 mt-2">0</h5>
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
            <h5 class="card-title mb-0 mt-2">123</h5>
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
                           <!-- <i class="ti ti-brand-paypal ti-sm"></i> -->
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

   <!-- Projects table -->
   <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
      <div class="card">
         <div class="card-datatable table-responsive">
            <table class="datatables-projects table border-top">
               <thead class="bg-light">
                  <tr>
                     <th></th>
                     <th></th>
                     <th>Name</th>
                     <th>Leader</th>
                     <th>Team</th>
                     <th class="w-px-200">Status</th>
                     <th>Action</th>
                  </tr>
               </thead>
            </table>
         </div>
      </div>
   </div>
   <!--/ Projects table -->

   <div class="col-xl-4 col-md-6 mb-4">
      <div class="card h-100">
         <div class="card-header d-flex justify-content-between">
            <div class="card-title m-0 me-2">
               <h5 class="m-0 me-2">Balances</h5>
            </div>

         </div>
         <div class="card-body">
            <ul class="p-0 m-0">
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
                           <h6 class="mb-0">1</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="secondary" data-series="85"></div>
                  </div>
               </li>
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
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="secondary" data-series="85"></div>
                  </div>
               </li>
            </ul>
         </div>
      </div>
   </div>
   <div class="col-xl-4 col-md-6 mb-4">
      <div class="card h-100">
         <div class="card-header d-flex justify-content-between">
            <div class="card-title m-0 me-2">
               <h5 class="m-0 me-2">User Counts</h5>
            </div>

         </div>
         <div class="card-body">
            <ul class="p-0 m-0">
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
                           <h6 class="mb-0">1</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="secondary" data-series="85"></div>
                  </div>
               </li>
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
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="success" data-series="70"></div>
                  </div>
               </li>
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
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="primary" data-series="25"></div>
                  </div>
               </li>

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
                           <h6 class="mb-0">2</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="danger" data-series="75"></div>
                  </div>
               </li>
               <li class="d-flex mb-4 pb-1 align-items-center">
                  <div class="badge rounded bg-label-success me-2 p-1">
                     <i class="ti ti-check ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">Other User</h6>
                        </div>
                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="info" data-series="60"></div>
                  </div>
               </li>
               <li class="d-flex align-items-center">
                  <div class="badge rounded bg-label-info me-2 p-1">
                     <i class="ti ti-users ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">Employee</h6>
                        </div>
                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="warning" data-series="45"></div>
                  </div>
               </li>
            </ul>
         </div>
      </div>
   </div>
   <div class="col-xl-4 col-md-6 mb-4">
      <div class="card h-100">
         <div class="card-header d-flex justify-content-between">
            <div class="card-title m-0 me-2">
               <h5 class="m-0 me-2">User Counts</h5>
            </div>

         </div>
         <div class="card-body">
            <ul class="p-0 m-0">
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
                           <h6 class="mb-0">1</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="secondary" data-series="85"></div>
                  </div>
               </li>
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
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="success" data-series="70"></div>
                  </div>
               </li>
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
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="primary" data-series="25"></div>
                  </div>
               </li>
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
                           <h6 class="mb-0">2</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="danger" data-series="75"></div>
                  </div>
               </li>
               <li class="d-flex mb-4 pb-1 align-items-center">
                  <div class="badge rounded bg-label-success me-2 p-1">
                     <i class="ti ti-check ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">Other User</h6>
                        </div>
                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="info" data-series="60"></div>
                  </div>
               </li>

               <li class="d-flex align-items-center">
                  <div class="badge rounded bg-label-info me-2 p-1">
                     <i class="ti ti-users ti-sm "></i>
                  </div>
                  <div class="d-flex w-100 align-items-center gap-2">
                     <div class="d-flex justify-content-between flex-grow-1 flex-wrap">
                        <div>
                           <h6 class="mb-0">Employee</h6>
                        </div>
                        <div class="user-progress d-flex align-items-center gap-2">
                           <h6 class="mb-0">0</h6>
                        </div>
                     </div>
                     <div class="chart-progress" data-color="warning" data-series="45"></div>
                  </div>
               </li>
            </ul>
         </div>
      </div>
   </div>


</div>

<!-- / Content -->

@endsection
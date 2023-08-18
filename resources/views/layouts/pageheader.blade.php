@if (!Request::is('loanenquiry') && !Request::is('dashboard') && !Request::is('profile/*') && !Request::is('recharge/*') && !Request::is('billpay/*') && !Request::is('pancard/*') && !Request::is('member/*/create') && !Request::is('profile') && !Request::is('profile/*') && !Request::is('dmt') && !Request::is('resources/companyprofile') && !Request::is('aeps/*') && !Request::is('developer/*') && !Request::is('resources/commission') && !Request::is('setup/portalsetting') && !Request::is('pdmt') && !Request::is('raeps/*'))


<div class="row">
    <form id="searchForm">
        <div class="col-lg-12 ">
            <div class="card h-100">
                <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                    <div class="card-title mb-0">
                        <h5 class="mb-0">
                            <h4>@yield('pagetitle')</h4>
                        </h5>
                    </div>
                    @if (@$export != null)
                    <div class="col-sm-12 col-md-3">
                        <div class="user-list-files d-flex float-right">
                            <button type="button" class="btn btn-danger  mx-3 " id="formReset" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Refreshing">Refresh</button>
                            <button type="button" class="btn btn-success  text-white mx-3 {{ isset($export) ? '' : 'hide' }}" product="{{ $export ?? '' }}" id="reportExport"> Export</button>

                        </div>
                    </div>
                    @endif
                </div>

                <div class="card-body">
                    <div class=" rounded p-3 mt-5">
                        <div class="row gap-4 gap-sm-0">
                            <div class="col-12 col-sm-12">
                                <div class="d-flex gap-2 align-items-center dataTables_filter" id="user_list_datatable_info">

                                    @if(isset($mystatus))
                                    <input type="hidden" name="status" value="{{$mystatus}}">
                                    @endif
                                    <div class="row">

                                        <div class="col-md-2">
                                            <label for="exampleInputdate">From Date</label>
                                            <input class="form-control mydate mt-1" name="from_date" type="text" required placeholder="From Date" />
                                        </div>

                                        <div class="col-md-2">
                                            <label for="exampleInputdate">To Date</label>
                                            <input class="form-control mt-1" name="to_date" type="text" required placeholder="To Date" />
                                        </div>

                                        <div class="form-group col-md-2 m-b-10">
                                            <label for="exampleInputdate">Search Value</label>
                                            <input type="text" name="searchtext" class="form-control  mt-1" placeholder="Search Value">
                                        </div>

                                        @if (Myhelper::hasNotRole(['retailer', 'apiuser',]))
                                        <div class="form-group col-md-2 m-b-10 {{ isset($agentfilter) ? $agentfilter : ''}}">
                                            <label for=" exampleInputdate">User Id</label>
                                            <input type="text" name="agent" class="form-control  mt-1" placeholder="Agent/Parent id">
                                        </div>
                                        @endif

                                        @if(isset($status))
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputdate">Status</label>

                                            <select name="status" class="form-select mt-1" aria-label="Status">
                                                <option value="">Select status</option>
                                                @if (isset($status['data']) && sizeOf($status['data']) > 0)
                                                @foreach ($status['data'] as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @endif

                                        @if(isset($product))
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputdate">Product</label>
                                            <select name="product" class="form-select  mt-1">
                                                <option value="">Select {{$product['type'] ?? ''}}</option>
                                                @if (isset($product['data']) && sizeOf($product['data']) > 0)
                                                @foreach ($product['data'] as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        @endif

                                        <div class="col-md-2">
                                            <div class="user-list-files d-flex search-button  mt-4">
                                                <button type="submit" class="btn btn-primary" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Searching"> Search</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>

                        </div>
                    </div>
                </div>
            </div>


        </div>

    </form>

</div>



<div id="helpModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-slate">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                <h6 class="modal-title">Help Desk</h6>
            </div>
            <div class="modal-body no-padding">
                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <th>Support Number</th>
                            <td>{{$mydata['supportnumber']}}</td>
                        </tr>
                        <tr>
                            <th>Support Email</th>
                            <td>{{$mydata['supportemail']}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endif
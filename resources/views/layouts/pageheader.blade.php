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
                    <div class="col-sm-12 col-md-3">
                        <div class="user-list-files d-flex float-right">
                            <button type="button" class="btn btn-danger  mx-3 " id="formReset" data-loading-text="<b><i class='fa fa-spin fa-spinner'></i></b> Refreshing">Refresh</button>
                            <button type="button" class="btn btn-success  text-white mx-3" id="reportExport"> Export</button>

                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class=" rounded p-3 mt-5">
                        <div class="row gap-4 gap-sm-0">
                            <div class="col-12 col-sm-12">
                                <div class="d-flex gap-2 align-items-center dataTables_filter" id="user_list_datatable_info">


                                    <div class="row">

                                        <div class="col-md-2">
                                            <label for="html5-date-input">From Date</label>
                                            <input class="form-control mydate mt-1" name="from_date" type="date" id="html5-date-input" required />
                                        </div>

                                        <div class="col-md-2">
                                            <label for="html5-date-input">To Date</label>
                                            <input class="form-control mydate  mt-1" name="to_date" type="date" id="html5-date-input" required />
                                        </div>

                                        <div class="form-group col-md-2 m-b-10">
                                            <label for="exampleInputdate">Search Value</label>
                                            <input type="text" name="searchtext" class="form-control  mt-1" placeholder="Search Value">
                                        </div>
                                        <div class="form-group col-md-2 m-b-10 >
                                            <label for=" exampleInputdate">User Id</label>
                                            <input type="text" name="agent" class="form-control  mt-1" placeholder="Agent/Parent id">
                                        </div>

                                        <div class="form-group col-md-2">
                                            <label for="exampleInputdate">Status</label>

                                            <select name="status" class="form-select mt-1" aria-label="Status">
                                                <option selected>Select status</option>
                                                <option value="active">Active</option>
                                                <option value="inactive">InActive</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-2">
                                            <label for="exampleInputdate">Product</label>
                                            <select name="product" class="form-select  mt-1">
                                                <option selected>Select</option>

                                            </select>
                                        </div>


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
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h6 class="modal-title">Help Desk</h6>
            </div>
            <div class="modal-body no-padding">
                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <tbody>
                        <tr>
                            <th>Support Number</th>
                            <td>1234567890</td>
                        </tr>
                        <tr>
                            <th>Support Email</th>
                            <td>abc@gmail.com</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('script')
<!-- Vendors JS -->
<script src="{{asset('theme_1/assets/vendor/libs/select2/select2.js')}}"></script>
<!-- <script src="{{asset('theme_1/assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script> -->
<script src="{{asset('theme_1/assets/vendor/libs/moment/moment.js')}}"></script>
<script src="{{asset('theme_1/assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('theme_1/assets/vendor/libs/tagify/tagify.js')}}"></script>
<script src="{{asset('theme_1/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>
<script src="{{asset('theme_1/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>
<script src="{{asset('theme_1/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>
@endpush
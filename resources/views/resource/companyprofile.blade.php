@extends('layouts.app')
@section('title', "Company Profile")
@section('pagetitle', "Company Profile")
@section('bodyClass', "has-detached-left")

@section('content')

<div class="row">
    <div class="col-lg-12 ">
        <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                    <h5 class="mb-0">
                        <h4>Company Setting</h4>
                    </h5>
                </div>
            </div>

            <div class="card-body">
                <div class=" rounded mt-5">
                    <div class="row gap-4 gap-sm-0">
                        <div class="">
                            <ul class="nav nav-tabs nav-pills" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="true">
                                        <i class="tf-icons ti ti-home ti-xs me-1"></i> Company Details
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-logo" aria-controls="navs-justified-logo" aria-selected="false">
                                        <i class="tf-icons ti ti-user ti-xs me-1"></i> Company Logo
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-news" aria-controls="navs-justified-news" aria-selected="false">
                                        <i class="tf-icons ti ti-message-dots ti-xs me-1"></i> Company News
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-notice" aria-controls="navs-justified-notice" aria-selected="false">
                                        <i class="tf-icons ti ti-id ti-xs me-1"></i> Company Notice
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-support" aria-controls="navs-justified-support" aria-selected="false">
                                        <i class="tf-icons ti ti-file ti-xs me-1"></i> Company Support Details
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade my-2 show active" id="navs-justified-profile" role="tabpanel">
                                    <form id="profileForm" method="post">

                                        <input type="hidden" name="id" value="">
                                        <input type="hidden" name="actiontype" value="company">
                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label>Company Name</label>
                                                <input type="text" name="companyname" class="form-control mt-1" value="Vuexy" required="" placeholder="Enter Value">
                                            </div>
                                            <div class="form-group  col-md-4">
                                                <label>Company Website</label>
                                                <input type="text" name="website" class="form-control mt-1" value="Vuexy.com" required="" placeholder="Enter Value">
                                            </div>
                                            <div class="col-sm-4">
                                                <button class="btn btn-primary mt-4" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>

                                            </div>
                                        </div>

                                    </form>
                                </div>
                                <div class="tab-pane fade my-2" id="navs-justified-logo" role="tabpanel">
                                    <div class="col-sm-3">
                                        <form class="dropzone" id="logoupload" method="post" enctype="multipart/form-data">
                                            <input type="file" class="form-control my-3 bg-light" />
                                            <input type="hidden" name="actiontype" value="company">
                                            <input type="hidden" name="id" value="">
                                        </form>
                                    </div>
                                    <p><b>Note :</b> Prefered image size is 260px * 56px</p>
                                </div>
                                <div class="tab-pane fade my-2" id="navs-justified-news" role="tabpanel">
                                    <form id="newsForm" method="post">

                                        <input type="hidden" name="id" value="">
                                        <input type="hidden" name="company_id" value="">
                                        <input type="hidden" name="actiontype" value="companydata">
                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>News</label>
                                                <textarea name="news" class="form-control" cols="30" rows="3" placeholder="Enter News">Hello, Everyone</textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Bill Notice</label>
                                                <textarea name="billnotice" class="form-control" cols="30" rows="3" placeholder="Enter News">Something Else Here</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8"></div>
                                            <div class="col-sm-4">
                                                <button class="btn btn-primary mt-4 pull-right float-end" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade my-2" id="navs-justified-notice" role="tabpanel">
                                    <form id="noticeForm" method="post">

                                        <input type="hidden" name="id" value="">
                                        <input type="hidden" name="company_id" value="">
                                        <input type="hidden" name="actiontype" value="companydata">
                                        <input type="hidden" name="notice">

                                        <div class="form-group summernote">
                                            {!! nl2br($companydata->notice ?? '') !!}
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-8"></div>
                                            <div class="col-sm-4">
                                                <button class="btn btn-primary pull-right float-end mt-3" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="tab-pane fade my-1" id="navs-justified-support" role="tabpanel">
                                    <form id="supportForm" method="post">

                                        <input type="hidden" name="company_id" value="">
                                        <input type="hidden" name="actiontype" value="companydata">

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Contact Number</label>
                                                <textarea name="number" class="form-control" cols="30" rows="3" placeholder="Enter Value" required="">9876543221</textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Contact Email</label>
                                                <textarea name="email" class="form-control" cols="30" rows="3" placeholder="Enter Value" required="">abc@gmail.com</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8"></div>
                                            <div class="col-sm-4">
                                                <button class="btn btn-primary pull-right float-end mt-4" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!--
                            <div class="tab-content" id="pills-tabContent-2">
                               
                                <div class="tab-pane fade my-2"  id="support" role="tabpanel" aria-labelledby="pills-contact-tab">
                                    <form id="supportForm" method="post">
                                        
                                        <input type="hidden" name="company_id" value="">
                                        <input type="hidden" name="actiontype" value="companydata">

                                        <div class="row">
                                            <div class="form-group col-md-6">
                                                <label>Contact Number</label>
                                                <textarea name="number" class="form-control" cols="30" rows="3" placeholder="Enter Value" required="">9876543221</textarea>
                                            </div>

                                            <div class="form-group col-md-6">
                                                <label>Contact Email</label>
                                                <textarea name="email" class="form-control" cols="30" rows="3" placeholder="Enter Value" required="">abc@gmail.com</textarea>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-8"></div>
                                            <div class="col-sm-4">
                                                <button class="btn btn-primary pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div> -->
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


</div>

@endsection

@push('style')
<style>
    .dropzone {
        min-height: 127px;
    }

    .dropzone .dz-default.dz-message:before {
        font-size: 50px;
        top: 60px;
    }

    .dropzone .dz-default.dz-message span {
        font-size: 18px;
        margin-top: 100px;
    }
</style>
@endpush

@push('script')

<script type="text/javascript">
    $(document).ready(function() {
        $("#profileForm").validate({
            rules: {
                companyname: {
                    required: true,
                }
            },
            messages: {
                companyname: {
                    required: "Please enter name",
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
                var form = $('form#profileForm');
                form.find('span.text-danger').remove();
                $('form#profileForm').ajaxSubmit({
                    dataType: 'json',
                    beforeSubmit: function() {
                        form.find('button:submit').button('loading');
                    },
                    complete: function() {
                        form.find('button:submit').button('reset');
                    },
                    success: function(data) {
                        if (data.status == "success") {
                            notify("Company Profile Successfully Updated", 'success');
                        } else {
                            notify(data.status, 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form.find('.panel-body'));
                    }
                });
            }
        });

        $("#newsForm").validate({
            rules: {
                company_id: {
                    required: true,
                }
            },
            messages: {
                company_id: {
                    required: "Please enter id",
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
                var form = $('form#newsForm');
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
                            notify("Company News Successfully Updated", 'success');
                        } else {
                            notify(data.status, 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form.find('.panel-body'));
                    }
                });
            }
        });

        $("#supportForm").validate({
            rules: {
                number: {
                    required: true,
                },
                email: {
                    required: true,
                }
            },
            messages: {
                number: {
                    required: "Number value is required",
                },
                email: {
                    required: "Email value is required",
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
                var form = $('form#supportForm');
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
                            notify("Company Support Details Successfully Updated", 'success');
                        } else {
                            notify(data.status, 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form.find('.panel-body'));
                    }
                });
            }
        });

        $("#noticeForm").validate({
            rules: {
                news: {
                    required: true,
                }
            },
            messages: {
                news: {
                    required: "Please enter name",
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
                var form = $('form#noticeForm');
                $('input[name="notice"]').val($('.note-editable').html());
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
                            notify("Company Notice Successfully Updated", 'success');
                        } else {
                            notify(data.status, 'warning');
                        }
                    },
                    error: function(errors) {
                        showError(errors, form.find('.panel-body'));
                    }
                });
            }
        });

        $('.summernote').summernote({
            height: 350, // set editor height
            minHeight: null, // set minimum height of editor
            maxHeight: null, // set maximum height of editor
            focus: false // set focus to editable area after initializing summernote
        });

        Dropzone.options.logoupload = {
            paramName: "logos", // The name that will be used to transfer the file
            maxFilesize: .5, // MB
            complete: function(file) {
                this.removeFile(file);
            },
            success: function(file, data) {
                console.log(file);
                if (data.status == "success") {
                    notify("Company Logo Successfully Uploaded", 'success');
                } else {
                    notify("Something went wrong, please try again.", 'warning');
                }
            }
        };
    });
</script>
@endpush
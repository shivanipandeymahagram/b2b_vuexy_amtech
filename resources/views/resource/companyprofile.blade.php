@extends('theme_1.layouts.app')
@section('title', "Company Profile")
@section('pagetitle', "Company Profile")
@section('bodyClass', "has-detached-left")

@section('theme_1_content')

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
                <div class=" rounded p-3 mt-3">
                    <div class="row gap-4 gap-sm-0">
                        <div class="nav-align-top nav-tabs-shadow mb-4">
                            <!-- <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item">
                                    <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-home" aria-controls="navs-justified-home" aria-selected="true">
                                        <i class="tf-icons ti ti-home ti-xs me-1"></i> Home
                                        <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger ms-1">3</span>
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-profile" aria-controls="navs-justified-profile" aria-selected="false">
                                        <i class="tf-icons ti ti-user ti-xs me-1"></i> Profile
                                    </button>
                                </li>
                                <li class="nav-item">
                                    <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-justified-messages" aria-controls="navs-justified-messages" aria-selected="false">
                                        <i class="tf-icons ti ti-message-dots ti-xs me-1"></i> Messages
                                    </button>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="navs-justified-home" role="tabpanel">
                                    <p>
                                        Icing pastry pudding oat cake. Lemon drops cotton candy caramels cake caramels sesame snaps
                                        powder. Bear claw candy topping.
                                    </p>
                                    <p class="mb-0">
                                        Tootsie roll fruitcake cookie. Dessert topping pie. Jujubes wafer carrot cake jelly. Bonbon
                                        jelly-o jelly-o ice cream jelly beans candy canes cake bonbon. Cookie jelly beans marshmallow
                                        jujubes sweet.
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="navs-justified-profile" role="tabpanel">
                                    <p>
                                        Donut dragée jelly pie halvah. Danish gingerbread bonbon cookie wafer candy oat cake ice
                                        cream. Gummies halvah tootsie roll muffin biscuit icing dessert gingerbread. Pastry ice cream
                                        cheesecake fruitcake.
                                    </p>
                                    <p class="mb-0">
                                        Jelly-o jelly beans icing pastry cake cake lemon drops. Muffin muffin pie tiramisu halvah
                                        cotton candy liquorice caramels.
                                    </p>
                                </div>
                                <div class="tab-pane fade" id="navs-justified-messages" role="tabpanel">
                                    <p>
                                        Oat cake chupa chups dragée donut toffee. Sweet cotton candy jelly beans macaroon gummies
                                        cupcake gummi bears cake chocolate.
                                    </p>
                                    <p class="mb-0">
                                        Cake chocolate bar cotton candy apple pie tootsie roll ice cream apple pie brownie cake. Sweet
                                        roll icing sesame snaps caramels danish toffee. Brownie biscuit dessert dessert. Pudding jelly
                                        jelly-o tart brownie jelly.
                                    </p>
                                </div>
                            </div> -->
                            <ul class="nav nav-pills nav-tabs mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#profile" role="tab" aria-controls="pills-home" aria-selected="true">Company Details</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#logo" role="tab" aria-controls="pills-profile" aria-selected="false">Company Logo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#news" role="tab" aria-controls="pills-contact" aria-selected="false">Company News</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#notice" role="tab" aria-controls="pills-contact" aria-selected="false">Company Notice</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="pills-contact-tab" data-toggle="pill" href="#support" role="tab" aria-controls="pills-contact" aria-selected="false">Company Support Details</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent-2">
                    <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="pills-home-tab">
                        <form id="profileForm" action="{{route('resourceupdate')}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$company->id}}">
                            <input type="hidden" name="actiontype" value="company">
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label>Company Name</label>
                                    <input type="text" name="companyname" class="form-control" value="{{$company->companyname}}" required="" placeholder="Enter Value">
                                </div>
                                <div class="form-group  col-md-4">
                                    <label>Company Website</label>
                                    <input type="text" name="website" class="form-control" value="{{$company->website}}" required="" placeholder="Enter Value">
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
                    <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <form class="dropzone" id="logoupload" action="{{route('resourceupdate')}}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="actiontype" value="company">
                            <input type="hidden" name="id" value="{{$company->id}}">
                        </form>
                        <p>Note : Prefered image size is 260px * 56px</p>
                    </div>
                    <div class="tab-pane fade" id="news" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <form id="newsForm" action="{{route('resourceupdate')}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$companydata->id ?? 'new'}}">
                            <input type="hidden" name="company_id" value="{{$company->id}}">
                            <input type="hidden" name="actiontype" value="companydata">
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>News</label>
                                    <textarea name="news" class="form-control" cols="30" rows="3" placeholder="Enter News">{{$companydata->news ?? ""}}</textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Bill Notice</label>
                                    <textarea name="billnotice" class="form-control" cols="30" rows="3" placeholder="Enter News">{{$companydata->billnotice ?? ""}}</textarea>
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
                    <div class="tab-pane fade" id="notice" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <form id="noticeForm" action="{{route('resourceupdate')}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="id" value="{{$companydata->id ?? 'new'}}">
                            <input type="hidden" name="company_id" value="{{$company->id}}">
                            <input type="hidden" name="actiontype" value="companydata">
                            <input type="hidden" name="notice">

                            <div class="form-group summernote">
                                {!! nl2br($companydata->notice ?? '') !!}
                            </div>

                            <div class="row">
                                <div class="col-sm-8"></div>
                                <div class="col-sm-4">
                                    <button class="btn btn-primary pull-right" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating...">Update Info</button>

                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade" id="support" role="tabpanel" aria-labelledby="pills-contact-tab">
                        <form id="supportForm" action="{{route('resourceupdate')}}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="company_id" value="{{$company->id}}">
                            <input type="hidden" name="actiontype" value="companydata">

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Contact Number</label>
                                    <textarea name="number" class="form-control" cols="30" rows="3" placeholder="Enter Value" required="">{{$companydata->number ?? ""}}</textarea>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Contact Email</label>
                                    <textarea name="email" class="form-control" cols="30" rows="3" placeholder="Enter Value" required="">{{$companydata->email ?? ""}}</textarea>
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
                </div>
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
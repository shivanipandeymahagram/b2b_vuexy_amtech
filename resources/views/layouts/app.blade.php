<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{asset('theme_1/assets')}}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - {{Auth::user()->company->companyname}}</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{asset('theme_1/assets/img/favicon/favicon.ico')}}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/fonts/fontawesome.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/fonts/tabler-icons.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/fonts/flag-icons.css')}}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/css/rtl/core.css?v=1.0.0')}}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/css/rtl/theme-default.css?v=1.0.0')}}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/css/demo.css')}}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/node-waves/node-waves.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/typeahead-js/typeahead.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/apex-charts/apex-charts.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/swiper/swiper.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/flatpickr/flatpickr.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/typeahead-js/typeahead.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/tagify/tagify.css')}}" />
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{asset('theme_1/assets/vendor/css/pages/cards-advance.css')}}" />
    <!-- Helpers -->
    <script src="{{asset('theme_1/assets/vendor/js/helpers.js')}}"></script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="{{asset('theme_1/assets/vendor/js/template-customizer.js')}}"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{asset('theme_1/assets/js/config.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js" defer></script>
    <script src="{{asset('theme_1/assets/vendor/libs/select2/select2.js')}}"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/dropzone.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <script src="http://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.18/summernote.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/sweetalert2.min.js"></script>
    <script type="text/javascript" src="{{asset('')}}assets/js/core/jquery.form.min.js"></script>
    <script src="{{asset('')}}theme/js/jquery.min.js"></script>
    <!-- CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.css" />

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/simple-notify@0.5.5/dist/simple-notify.min.js"></script>

    @if (isset($table) && $table == "yes")
    <script type="text/javascript" src="{{asset('')}}assets/js/plugins/tables/datatables/datatables.min.js"></script>
    @endif


    <script type="text/javascript">
        $(document).ready(function() {
            $('.select').select2();

            $('#profileImg').hover(function() {
                $('span.changePic').show('400');
            });

            $('.changePic').hover(function() {
                $('span.changePic').show('400');
            }, function() {
                $('span.changePic').hide('400');
            });

            $(document).ready(function() {
                $(".sidebar-default a").each(function() {
                    if (this.href == window.location.href) {
                        $(this).addClass("active");
                        $(this).parent().addClass("active");
                        $(this).parent().parent().prev().addClass("active");
                        $(this).parent().parent().prev().click();
                    }
                });
            });

            $('#reportExport').click(function() {
                var type = $(this).attr('product');
                var fromdate = $('#searchForm').find('input[name="from_date"]').val();
                var todate = $('#searchForm').find('input[name="to_date"]').val();
                var searchtext = $('#searchForm').find('input[name="searchtext"]').val();
                var agent = $('#searchForm').find('input[name="agent"]').val();
                var status = $('#searchForm').find('[name="status"]').val();
                var product = $('#searchForm').find('[name="product"]').val();

                @if(isset($id))
                agent = "{{$id}}";
                @endif

                window.location.href = "{{ url('statement/export') }}/" + type + "?fromdate=" + fromdate + "&todate=" + todate + "&searchtext=" + searchtext + "&agent=" + agent + "&status=" + status + "&product=" + product;
            });

            Dropzone.options.profileupload = {
                paramName: "profiles", // The name that will be used to transfer the file
                maxFilesize: .5, // MB
                complete: function(file) {
                    this.removeFile(file);
                },
                success: function(file, data) {
                    console.log(file);
                    if (data.status == "success") {
                        $('#profileImg').removeAttr('src');
                        $('#profileImg').attr('src', file.dataURL);
                        notify("Profile Successfully Uploaded", 'success');
                    } else {
                        notify("Something went wrong, please try again.", 'warning');
                    }
                }
            };

            $('.mydate').datepicker({
                'autoclose': true,
                'clearBtn': true,
                'todayHighlight': true,
                'format': 'yyyy-mm-dd'
            });

            $('input[name="from_date"]').datepicker("setDate", new Date());
            $('input[name="to_date"]').datepicker('setStartDate', new Date());

            $('input[name="to_date"]').focus(function() {
                if ($('input[name="from_date"]').val().length == 0) {
                    $('input[name="to_date"]').datepicker('hide');
                    $('input[name="from_date"]').focus();
                }
            });

            $('input[name="from_date"]').datepicker().on('changeDate', function(e) {
                $('input[name="to_date"]').datepicker('setStartDate', $('input[name="from_date"]').val());
                $('input[name="to_date"]').datepicker('setDate', $('input[name="from_date"]').val());
            });

            $('form#searchForm').submit(function() {
                $('#searchForm').find('button:submit').button('loading');
                var fromdate = $(this).find('input[name="from_date"]').val();
                var todate = $(this).find('input[name="to_date"]').val();
                if (fromdate.length != 0 || todate.length != 0) {
                    $('#datatable').dataTable().api().ajax.reload();
                }
                return false;
            });

            $('#formReset').click(function() {
                $('form#searchForm')[0].reset();
                $('form#searchForm').find('[name="from_date"]').datepicker().datepicker("setDate", new Date());
                $('form#searchForm').find('[name="to_date"]').datepicker().datepicker("setDate", null);
                $('form#searchForm').find('select').val(null).trigger('change')
                $('#formReset').button('loading');
                $('#datatable').dataTable().api().ajax.reload();
            });

            $(".navigation-menu a").each(function() {
                alert();
            });

            $('select').change(function(event) {
                var ele = $(this);
                if (ele.val() != '') {
                    $(this).closest('div.form-group').find('p.error').remove();
                }
            });

            $("#editForm").validate({
                rules: {
                    status: {
                        required: true,
                    },
                    txnid: {
                        required: true,
                    },
                    payid: {
                        required: true,
                    },
                    refno: {
                        required: true,
                    }
                },
                messages: {
                    name: {
                        required: "Please select status",
                    },
                    txnid: {
                        required: "Please enter txn id",
                    },
                    payid: {
                        required: "Please enter payid",
                    },
                    refno: {
                        required: "Please enter ref no",
                    }
                },
                errorElement: "p",
                errorPlacement: function(error, element) {
                    if (element.prop("tagName").toLowerCase() === "select") {
                        error.insertAfter(element.closest(".form-group").find(".select2"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function() {
                    var form = $('#editForm');
                    var id = form.find('[name="id"]').val();
                    form.ajaxSubmit({
                        dataType: 'json',
                        beforeSubmit: function() {
                            form.find('button[type="submit"]').button('loading');
                        },
                        success: function(data) {
                            if (data.status == "success") {
                                form.find('button[type="submit"]').button('reset');
                                notify("Task Successfully Completed", 'success');
                                $('#datatable').dataTable().api().ajax.reload();
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

            setTimeout(function() {
                sessionOut();
            }, "{{$mydata['sessionOut']}}");

            $(".modal").on('hidden.bs.modal', function() {
                if ($(this).find('form').length) {
                    $(this).find('form')[0].reset();
                }

                if ($(this).find('.select').length) {
                    $(this).find('.select').val(null).trigger('change');
                }
            });

            $("#walletLoadForm").validate({
                rules: {
                    amount: {
                        required: true,
                    }
                },
                messages: {
                    amount: {
                        required: "Please enter amount",
                    },
                },
                errorElement: "p",
                errorPlacement: function(error, element) {
                    if (element.prop("tagName").toLowerCase() === "select") {
                        error.insertAfter(element.closest(".form-group").find(".select2"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function() {
                    var form = $('#walletLoadForm');
                    form.ajaxSubmit({
                        dataType: 'json',
                        beforeSubmit: function() {
                            form.find('button:submit').button('loading');
                        },
                        complete: function() {
                            form.find('button:submit').button('reset');
                        },
                        success: function(data) {
                            if (data.status) {
                                form[0].reset();
                                getbalance();
                                form.closest('.modal').modal('hide');
                                notify("Wallet successfully loaded", 'success');
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

            $("#notifyForm").validate({
                rules: {
                    amount: {
                        required: true,
                    }
                },
                messages: {
                    amount: {
                        required: "Please enter amount",
                    },
                },
                errorElement: "p",
                errorPlacement: function(error, element) {
                    if (element.prop("tagName").toLowerCase() === "select") {
                        error.insertAfter(element.closest(".form-group").find(".select2"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function() {
                    var form = $('#notifyForm');
                    form.ajaxSubmit({
                        dataType: 'json',
                        beforeSubmit: function() {
                            form.find('button:submit').button('loading');
                        },
                        complete: function() {
                            form.find('button:submit').button('reset');
                        },
                        success: function(data) {
                            if (data.status) {
                                form[0].reset();
                                getbalance();
                                form.closest('.modal').modal('hide');
                                notify("Send successfully", 'success');
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

            $("#complaintForm").validate({
                rules: {
                    subject: {
                        required: true,
                    },
                    description: {
                        required: true,
                    }
                },
                messages: {
                    subject: {
                        required: "Please select subject",
                    },
                    description: {
                        required: "Please enter your description",
                    },
                },
                errorElement: "p",
                errorPlacement: function(error, element) {
                    if (element.prop("tagName").toLowerCase() === "select") {
                        error.insertAfter(element.closest(".form-group").find(".select2"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                submitHandler: function() {
                    var form = $('#complaintForm');
                    form.ajaxSubmit({
                        dataType: 'json',
                        beforeSubmit: function() {
                            form.find('button:submit').button('loading');
                        },
                        complete: function() {
                            form.find('button:submit').button('reset');
                        },
                        success: function(data) {
                            if (data.status) {
                                form[0].reset();
                                form.closest('.modal').modal('hide');
                                notify("Complaint successfully submitted", 'success');
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
        });

        function getbalance() {
            $.ajax({
                url: "{{route('getbalance')}}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(result) {

                    $.each(result, function(index, value) {

                        $('.' + index).text(value);
                    });
                }
            });

            $.ajax({
                url: "{{url('mydata')}}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: 'json',
                success: function(data) {
                    $('.fundCount').text(data.fundrequest);
                    $('.aepsrequestfundCount').text(data.aepsfundrequest);
                    $('.member').text(data.member);
                    $('.aepspayoutfundCount').text(data.aepspayoutrequest);
                    $('.aepsfundCount').text(data.aepsfundrequest + data.aepspayoutrequest);
                }
            });
        }


        getbalance();



        @if(isset($table) && $table == "yes")

        function datatableSetup(urls, datas, onDraw = function() {}, ele = "#datatable", element = {}) {
            var options = {
                dom: '<"datatable-scroll"t><"datatable-footer"ip>',
                processing: true,
                serverSide: true,
                ordering: false,
                stateSave: true,
                columnDefs: [{
                    orderable: false,
                    width: '130px',
                    targets: [0]
                }],
                language: {
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': '&rarr;',
                        'previous': '&larr;'
                    }
                },
                drawCallback: function() {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
                },
                preDrawCallback: function() {
                    $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
                },
                ajax: {
                    url: urls,
                    type: "post",
                    data: function(d) {
                        d._token = $('meta[name="csrf-token"]').attr('content');
                        d.fromdate = $('#searchForm').find('[name="from_date"]').val();
                        d.todate = $('#searchForm').find('[name="to_date"]').val();
                        d.searchtext = $('#searchForm').find('[name="searchtext"]').val();
                        d.agent = $('#searchForm').find('[name="agent"]').val();
                        d.status = $('#searchForm').find('[name="status"]').val();
                        d.product = $('#searchForm').find('[name="product"]').val();
                    },
                    beforeSend: function() {},
                    complete: function() {
                        $('#searchForm').find('button:submit').button('reset');
                        $('#formReset').button('reset');
                    },
                    error: function(response) {}
                },
                columns: datas
            };

            $.each(element, function(index, val) {
                options[index] = val;
            });

            var DT = $(ele).DataTable(options).on('draw.dt', onDraw);

            return DT;
        }
        @endif

        // function notify(msg, type = "success", notitype = "popup", element = "none") {
        //     if (notitype == "popup") {
        //         let snackbar = new SnackBar;
        //         snackbar.make("message", [
        //             msg,
        //             null,
        //             "bottom",
        //             "right",
        //             "text-" + type
        //         ], 5000);
        //     } else {
        //         element.find('div.alert').remove();
        //         element.prepend(`<div class="alert bg-` + type + ` alert-styled-left">
        //             <button type="button" class="btn-close" data-dismiss="alert"><span></span><span class="sr-only">Close</span></button> ` + msg + `
        //         </div>`);

        //         setTimeout(function() {
        //             element.find('div.alert').remove();
        //         }, 5000);
        //     }
        // }

        function showError(errors, form = "withoutform") {
            if (form != "withoutform") {
                form.find('button[type="submit"]').button('reset');
                $('p.error').remove();
                $('div.alert').remove();
                if (errors.status == 422) {
                    $.each(errors.responseJSON.errors, function(index, value) {
                        form.find('[name="' + index + '"]').closest('div.form-group').append('<p class="error">' + value + '</span>');
                    });
                    form.find('p.error').first().closest('.form-group').find('input').focus();
                    setTimeout(function() {
                        form.find('p.error').remove();
                    }, 5000);
                } else if (errors.status == 400) {
                    if (errors.responseJSON.message) {
                        form.prepend(`<div class="alert bg-danger alert-styled-left">
                            <button type="button" class="btn-close" data-dismiss="alert"><span></span><span class="sr-only">Close</span></button>
                            <span class="text-semibold">Oops !</span> ` + errors.responseJSON.message + `
                        </div>`);
                    } else {
                        form.prepend(`<div class="alert bg-danger alert-styled-left">
                            <button type="button" class="btn-close" data-dismiss="alert"><span></span><span class="sr-only">Close</span></button>
                            <span class="text-semibold">Oops !</span> ` + errors.responseJSON.status + `
                        </div>`);
                    }

                    setTimeout(function() {
                        form.find('div.alert').remove();
                    }, 10000);
                } else {
                    notify(errors.statusText, 'warning');
                }
            } else {
                if (errors.responseJSON.message) {
                    notify(errors.responseJSON.message, 'warning');
                } else {
                    notify(errors.responseJSON.status, 'warning');
                }
            }
        }

        function sessionOut() {
            window.location.href = "{{route('logout')}}";
        }

        function status(id, type) {
            $.ajax({
                    url: `{{route('statementStatus')}}`,
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        swal({
                            title: 'Wait!',
                            text: 'Please wait, we are fetching transaction details',
                            onOpen: () => {
                                swal.showLoading()
                            },
                            allowOutsideClick: () => !swal.isLoading()
                        });
                    },
                    data: {
                        'id': id,
                        "type": type
                    }
                })
                .done(function(data) {
                    if (data.status == "success") {
                        if (data.refno) {
                            var refno = "Operator Refrence is " + data.refno
                        } else {
                            var refno = data.remark;
                        }
                        swal({
                            type: 'success',
                            title: data.status,
                            text: refno,
                            onClose: () => {
                                $('#datatable').dataTable().api().ajax.reload();
                            },
                        });
                    } else {
                        swal({
                            type: 'success',
                            title: data.status,
                            text: "Transaction status is " + data.status,
                            onClose: () => {
                                $('#datatable').dataTable().api().ajax.reload();
                            },
                        });
                    }
                })
                .fail(function(errors) {
                    swal.close();
                    showError(errors, "withoutform");
                });
        }

        function editReport(id, refno, txnid, payid, remark, status, actiontype) {
            $('#editModal').find('[name="id"]').val(id);
            $('#editModal').find('[name="status"]').val(status).trigger('change');
            $('#editModal').find('[name="refno"]').val(refno);
            $('#editModal').find('[name="txnid"]').val(txnid);
            if (actiontype == "billpay") {
                $('#editModal').find('[name="payid"]').closest('div.form-group').remove();
            } else {
                $('#editModal').find('[name="payid"]').val(payid);
            }
            $('#editModal').find('[name="remark"]').val(remark);
            $('#editModal').find('[name="actiontype"]').val(actiontype);
            $('#editModal').modal('show');
        }

        function complaint(id, product) {
            $('#complaintModal').find('[name="transaction_id"]').val(id);
            $('#complaintModal').find('[name="product"]').val(product);
            $('#complaintModal').modal('show');
        }

        function notify(text, status) {
            new Notify({
                status: status,
                title: null,
                text: text,
                effect: 'fade',
                customClass: null,
                customIcon: null,
                showIcon: true,
                showCloseButton: true,
                autoclose: true,
                autotimeout: 2000,
                gap: 20,
                distance: 15,
                type: 1,
                position: 'right top'
            })
        }
    </script>

</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">

            <!-- Menu -->
            @include('layouts.sidebar')
            <!-- End Menu -->

            <!-- Layout container -->
            <div class="layout-page">

                <!-- Topbar -->
                @include('layouts.topbar')
                <!-- End Topbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @include('layouts.pageheader')
                        @yield('content')
                    </div>
                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div class="footer-container d-flex align-items-center justify-content-between py-2 flex-md-row flex-column">
                                <div>
                                    ©
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by <a href="https://pixinvent.com" target="_blank" class="fw-semibold">Pixinvent</a>
                                </div>
                                <div>
                                    <a href="https://themeforest.net/licenses/standard" class="footer-link me-4" target="_blank">License</a>
                                    <a href="https://1.envato.market/pixinvent_portfolio" target="_blank" class="footer-link me-4">More Themes</a>

                                    <a href="https://demos.pixinvent.com/vuexy-html-admin-template/documentation/" target="_blank" class="footer-link me-4">Documentation</a>

                                    <a href="https://pixinvent.ticksy.com/" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
        @if (Myhelper::hasRole('admin'))
        <div class="modal fade" id="walletloadModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog  modal-simple modal-edit-user">
                <div class="modal-content p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-3">
                            <h3 class="mb-2">Load Wallet</h3>
                        </div>

                        <form id="walletLoadForm" action="{{route('fundtransaction')}}" method="post">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="form-group col-md-12 my-1">
                                        <label>Amount</label>
                                        <input type="text" class="form-control my-1" placeholder="Enter Amount" />
                                    </div>
                                    <div class="form-group col-md-12 my-1">
                                        <label>Remark</label>
                                        <textarea type="text" class="form-control my-1" placeholder="Enter Remark"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Report</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="editForm" action="{{route('statementUpdate')}}" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="id">
                                <input type="hidden" name="actiontype" value="">
                                {{ csrf_field() }}
                                <div class="form-group col-md-6">
                                    <label>Status</label>
                                    <select name="status" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="pending">Pending</option>
                                        <option value="success">Success</option>
                                        <option value="failed">Failed</option>
                                        <option value="reversed">Reversed</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Ref No</label>
                                    <input type="text" name="refno" class="form-control" placeholder="Enter Vle id" required="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Txn Id</label>
                                    <input type="text" name="txnid" class="form-control" placeholder="Enter Vle id" required="">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Pay Id</label>
                                    <input type="text" name="payid" class="form-control" placeholder="Enter Vle id" required="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-12">
                                    <label>Remark</label>
                                    <textarea rows="3" name="remark" class="form-control" placeholder="Enter Remark"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="complaintModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="complaintForm" action="{{route('complaintstore')}}" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="id" value="new">
                            <input type="hidden" name="product">
                            <input type="hidden" name="transaction_id">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Subject</label>
                                <select name="subject" class="form-control">
                                    <option value="">Select Subject</option>
                                    @foreach ($mydata['complaintsubject'] as $item)
                                    <option value="{{$item->id}}">{{$item->subject}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" cols="30" class="form-control" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Updating">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="fundRequestModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Wallet Fund Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="fundRequestForm" action="{{route('fundtransaction')}}" method="post">
                        <div class="modal-body">
                            @if(Auth::user()->bank != '' && Auth::user()->ifsc != '' && Auth::user()->account != '')
                            <table class="table table-bordered p-b-15" cellspacing="0" style="margin-bottom: 30px">
                                <thead>
                                    <tr>
                                        <th>Accoun</th>
                                        <th>Bank</th>
                                        <th>IFSC</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{Auth::user()->account}}</td>
                                        <td>{{Auth::user()->bank}}</td>
                                        <td>{{Auth::user()->ifsc}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            @endif

                            <table class="table table-bordered p-b-15" cellspacing="0" style="margin-bottom: 30px">
                                <tbody>
                                    <tr>
                                        <th>Settlement Charge</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Settlement Timing</th>
                                        <td>Bank</td>
                                    </tr>
                                </tbody>
                            </table>

                            <input type="hidden" name="user_id">
                            {{ csrf_field() }}
                            @if(Auth::user()->bank == '' && Auth::user()->ifsc == '' && Auth::user()->account == '')
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Account Number</label>
                                    <input type="text" class="form-control" name="account" placeholder="Enter Value" required="" value="{{Auth::user()->account}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>IFSC Code</label>
                                    <input type="text" class="form-control" name="ifsc" placeholder="Enter Value" required="" value="{{Auth::user()->ifsc}}">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Bank Name</label>
                                    <input type="text" class="form-control" name="bank" placeholder="Enter Value" required="" value="{{Auth::user()->bank}}">
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label>Wallet Type</label>
                                    <select name="type" class="form-control select" required>
                                        <option value="">Select Wallet</option>
                                        <option value="bank">Move To Bank</option>
                                        <option value="wallet">Move To Wallet</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Amount</label>
                                    <input type="number" class="form-control" name="amount" placeholder="Enter Value" required="">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label>T- PIN</label>
                                        <input type="password" name="pin" class="form-control" placeholder="Enter transaction pin" required="">
                                        <a href="{{url('profile/view?tab=pinChange')}}" target="_blank" class="text-primary pull-right">Generate or Forgot PIN??</a>
                                    </div>
                                </div>
                            </div>
                            <p class="text-danger">Note - If you want to change bank details, please send mail with account
                                details to update your bank details.</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit" data-loading-text="<i class='fa fa-spin fa-spinner'></i> Submitting">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <!-- / Layout wrapper -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('theme_1/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('theme_1/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('theme_1/assets/vendor/js/bootstrap.js')}}"></script>
    <script src="{{asset('')}}assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    <script src="{{asset('theme_1/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js')}}"></script>
    <script src="{{asset('theme_1/assets/vendor/libs/node-waves/node-waves.js')}}"></script>

    <script src="{{asset('theme_1/assets/vendor/libs/hammer/hammer.js')}}"></script>
    <script src="{{asset('theme_1/assets/vendor/libs/i18n/i18n.js')}}"></script>
    <script src="{{asset('theme_1/assets/vendor/libs/typeahead-js/typeahead.js')}}"></script>

    <script src="{{asset('theme_1/assets/vendor/js/menu.js')}}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{asset('theme_1/assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
    <script src="{{asset('theme_1/assets/vendor/libs/swiper/swiper.js')}}"></script>
    <script src="{{asset('theme_1/assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>

    <!-- Main JS -->
    <script src="{{asset('theme_1/assets/js/main.js')}}"></script>

    <script type="text/javascript" src="{{asset('')}}assets/js/core/jquery.validate.min.js"></script>
    <!-- Page JS -->
    <script src="{{asset('theme_1/assets/js/dashboards-analytics.js')}}"></script>
    @stack('script')
</body>

</html>
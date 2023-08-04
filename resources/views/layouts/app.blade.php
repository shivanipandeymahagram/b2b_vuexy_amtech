<!DOCTYPE html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed" dir="ltr" data-theme="theme-default" data-assets-path="{{asset('theme_1/assets')}}/" data-template="vertical-menu-template">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title')</title>

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

   
    @stack('script')

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


    </div>
    <!-- / Layout wrapper -->


    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{asset('theme_1/assets/vendor/libs/jquery/jquery.js')}}"></script>
    <script src="{{asset('theme_1/assets/vendor/libs/popper/popper.js')}}"></script>
    <script src="{{asset('theme_1/assets/vendor/js/bootstrap.js')}}"></script>
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

    <!-- Page JS -->
    <script src="{{asset('theme_1/assets/js/dashboards-analytics.js')}}"></script>
    <script src="{{asset('')}}assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>

    @yield('custom_Script')
</body>

</html>
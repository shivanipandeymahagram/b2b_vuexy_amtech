@extends('layouts.app')
@section('title', 'Certificate')
@section('pagetitle', 'Certificate')


@section('content')
<div class="row">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-lg-0">
        <div class="card">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4 ">
                <div class="card-title mb-3">
                    <h5 class="mb-0">
                        <span>Complaints</span>

                    </h5>
                </div>

            </div>

            <div class="card-table table-responsive p-2">
                <i class="ti ti-printer m-3 btn btn-primary p-2 rounded fs-3"></i>
                <table style="background-image:url('{{asset('theme_1/assets/bcg.jpg')}}');width:600px!important;height:424px;text-align: center;position: relative;">
                    <tr style="width: 100%">
                        <td colspan="2" style="position: relative;top:180px;width: 100%">ICICI BANK</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:0px 50px;position: relative;top: 80px;">This is a certify that the above mentioned company/person is our authorized
                            Business Correspondent Agent</td>
                    </tr>
                    <tr style="width: 100%">
                        <td style="position: relative;top:-7px;width:45%;text-align:center;padding-left:100px">30 Jun 2023</td>
                        <td style="position: relative;top:-7px; left:15px; width: 55%;text-align: left;">AMTech</td>
                    </tr>
                </table>

            </div>
        </div>

    </div>
</div>


@endsection
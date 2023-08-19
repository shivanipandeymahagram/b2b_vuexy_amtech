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
                <i class="ti ti-printer m-3 btn btn-primary p-2 rounded fs-3" id="print"></i>
                <table style="background-image:url('{{asset('assets/')}}/bcg.jpg');width:600px!important;height:424px;text-align: center;position: relative;">
                    <tr style="width: 100%">
                        <td colspan="2" style="position: relative;top:180px;width: 100%">{{Auth::user()->shopname}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding:0px 50px;position: relative;top: 80px;">This is a certify that the above mentioned company/person is our authorized
                            Business Correspondent Agent</td>
                    </tr>
                    <tr style="width: 100%">
                        <td style="position: relative;top: 12px;width:45%;text-align:center;padding-left:100px">{{\Carbon\Carbon::createFromFormat('d M y - h:i A', Auth::user()->created_at)->format('d M Y')}}</td>
                        <td style="position: relative;top: 11px;left: 31px;width: 55%;text-align: left;">{{Auth::user()->company->companyname}}</td>
                    </tr>
                </table>

            </div>
        </div>

    </div>
</div>
@endsection

@push('script')
<script type="text/javascript">
    $(document).ready(function() {
        $('#print').click(function() {
            openWin();
        });
    });
</script>
<script type="text/javascript">
    function openWin() {
        var body = $('#printable').html();
        var myWindow = window.open('', '', 'width=800,height=600');

        myWindow.document.write(body);

        myWindow.document.close();
        myWindow.focus();
        myWindow.print();
        myWindow.close();

    }
</script>
@endpush
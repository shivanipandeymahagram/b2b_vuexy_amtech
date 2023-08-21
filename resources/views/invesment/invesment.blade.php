@extends('layouts.app')
@section('title', 'Investment List')
@section('pagetitle', 'Investment List')


@section('content')
<style>
    .bg-image {

        background-repeat: no-repeat;
        background-size: 100% 100%;
        margin-top: 5px;
        padding-left: 2px;
        color: rgb(39, 39, 133);
        box-shadow: 0px 0px 1px silver;
    }

    @media only screen and (max-width: 600px) {
        .bg-image {
            width: 30rem;
        }
    }
</style>

<div class="row mt-4">
    <div class="col-12 col-xl-12 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <p id="errormessage" style="color:red; font-weight:bold;margin-left:10px; margin-top:10px"> </p>
        <div class="row">
            <div class="card">
                @foreach($invesment as $val)

                <div class="col-md-4">
                    @if(isset($val->banner))
                    <div class="bg-image pt-4 pb-1 fs-5 px-4" style="background-image: url('../public/banner/{{$val->banner->slides}}')">
                        @else
                        <div class="bg-image pt-4 pb-1 fs-5 px-4" style="background-image: url('../public/bg.jpg')">
                            @endif
                            <?php $sdate = date_create($val->start_date);
                            $edate = date_create($val->end_date);
                            $mdate = date_create($val->maturity_at); ?>
                            <p><b>From </b>: {{date_format($sdate,"d M Y h:i A")}}</p>
                            <p><b>To</b> : {{date_format($edate,"d M Y h:i A")}}</p>
                            <p><b>Mature Amount</b> : {{$val->mature_amount}}</p>
                            <p><b>Mature At</b> : {{date_format($mdate,"d M y")}}</p>
                            <p><b>Amount </b>: {{$val->amount}}</p>
                            @if(isset($val->txnStatus))
                            <button class="btn btn-success text-white btn-lg mt-1 investcls-{{$val->id}}" type="button">INVESTED</button>
                            @else
                            <button class="btn btn-warning text-white btn-lg mt-1 investcls-{{$val->id}}" onclick="buyInves('{{$val->id}}')" type="button">INVEST</button>
                            @endif

                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>

@endsection

@push('script')
<script>
    function buyInves(id) {
        $.ajax({
                url: '{{route("investNow")}}',
                type: 'post',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    "investment_id": id
                },
                beforeSend: function() {
                    swal({
                        title: 'Wait!',
                        text: 'Please wait, we are status change',
                        onOpen: () => {
                            swal.showLoading()
                        },
                        allowOutsideClick: () => !swal.isLoading()
                    });
                }
            })
            .success(function(data) {
                swal.close();
                console.log(id);
                if (data.errors != undefined) {
                    $('#errormessage').text(data.errors[0]);
                }
                $('.investcls-' + id).removeAttr('onclick');

                $('.investcls-' + id).removeClass('btn-warning').addClass('btn-success');
                $('.investcls-' + id).text('Invested');
                // notify("Banner status changed successfully", 'success');
            })
            .fail(function(error) {
                swal.close();

                notify('Somthing went wrong', 'warning');
            });
    }
</script>
@endpush
@extends('layouts.app')
@section('title', "Coming Soon")
@section('pagetitle',  "Coming Soon")

@section('content')
<style>

.container .btn {
  position: absolute;
  top: 75%;
  left: 35%;
  transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  background-color: blue;
  color: white;
  font-size: 16px;
  padding: 12px 24px;
  border: none;
  cursor: pointer;
  border-radius: 30px;
  text-align: center;
}

.container .btn:hover {
  background-color: black;
}
</style>
    <div class="container" style="max-width: fit-content !important;">
        <img src="{{asset('assets/images/commingsoon.png')}}" class="img-responsive" style="width: 100%;border-radius: 30px; ">
        <a href="{{route('home')}}"><button type="button" class="btn"><strong>GO To Dashboard</strong></button></a>
    </div>
@endsection

@push('style')
@endpush

@push('script')
@endpush
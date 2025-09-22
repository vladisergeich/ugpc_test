@extends('layouts.portal')

@section('content')
<div class="main-body">
  <div class="page-wrapper">
    <ordersplan-component
    :route_maps='@json($route_maps)' 
    :operations='@json($operations)' 
    urldailyplangetdata="{{ route('ugpc.dailyplan.getdata') }}"
    :work_shift_codes='@json($work_shift_codes)' 
    :users='@json($users)' 
    urlupdateorder="{{ route('ugpc.engravingorders.updateengravingtime') }}"
    urladdcomment="{{ route('ugpc.dailyplan.addcomment') }}"
    >
    </ordersplan-component>     
  </div>
</div>

@endsection,
@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <engravingmachine-component 
    :secondary_operations='@json($secondary_operations)' 
    :work_shift_code='@json($work_shift_code)'
    :operator='@json($operator)'
    urlgetshaftconsump="{{route('ugpc.engravingmachine.getshaftconsump')}}"
    urlstartsecondaryoperation="{{route('ugpc.engravingmachine.startsecondaryoperation')}}"
    urlstartoperation="{{route('ugpc.engravingmachine.startoperation')}}"
    urlgetinfomachine="{{route('ugpc.engravingmachine.getinfomachine')}}"
    urldeleteoperation="{{route('ugpc.engravingmachine.deleteoperation')}}"
    urlcloseoperation="{{route('ugpc.engravingmachine.closeoperation')}}"
    urlbrakshaft="{{route('ugpc.engravingmachine.brakshaft')}}"
    urldeleteshaftconsump="{{route('ugpc.engravingmachine.deleteshaftconsump')}}"
    >
    </engravingmachine-component>     
  </div>
</div>

@endsection,

@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <testprintmachine-component 
    :secondary_operations='@json($secondary_operations)' 
    :work_shift_code='@json($work_shift_code)'
    :operator='@json($operator)'
    urlgetshaftconsump="{{route('ugpc.testprintmachine.getshaftconsump')}}"
    urlstartsecondaryoperation="{{route('ugpc.testprintmachine.startsecondaryoperation')}}"
    urlstartoperation="{{route('ugpc.testprintmachine.startoperation')}}"
    urlgetinfomachine="{{route('ugpc.testprintmachine.getinfomachine')}}"
    urldeleteoperation="{{route('ugpc.testprintmachine.deleteoperation')}}"
    urlcloseoperation="{{route('ugpc.testprintmachine.closeoperation')}}"
    urlbrakshaft="{{route('ugpc.testprintmachine.brakshaft')}}"
    urldeleteshaftconsump="{{route('ugpc.testprintmachine.deleteshaftconsump')}}"
    >
    </testprintmachine-component>     
  </div>
</div>

@endsection,


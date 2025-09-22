@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <lathegrindingmachinecard-component 
    :secondary_operations='@json($secondary_operations)' 
    :work_shift_code='@json($work_shift_code)'
    :operator='@json($operator)'
    urlgetshaftconsump="{{route('ugpc.lathegrindingmachine.getshaftconsump')}}"
    urlstartsecondaryoperation="{{route('ugpc.lathegrindingmachine.startsecondaryoperation')}}"
    urlstartoperation="{{route('ugpc.lathegrindingmachine.startoperation')}}"
    urlgetinfomachine="{{route('ugpc.lathegrindingmachine.getinfomachine')}}"
    urldeleteoperation="{{route('ugpc.lathegrindingmachine.deleteoperation')}}"
    urlcloseoperation="{{route('ugpc.lathegrindingmachine.closeoperation')}}"
    urlbrakshaft="{{route('ugpc.lathegrindingmachine.brakshaft')}}"
    urldeleteshaftconsump="{{route('ugpc.lathegrindingmachine.deleteshaftconsump')}}"
    urladdsteps="{{route('ugpc.lathegrindingmachine.addsteps')}}"
    >
    </lathegrindingmachinecard-component>     
  </div>
</div>

@endsection,

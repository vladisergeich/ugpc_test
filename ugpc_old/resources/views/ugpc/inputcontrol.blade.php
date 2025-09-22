@extends('layouts.portal')

@section('content')
@routes(['ugpc.inputcontrol.acceptedorder'])
<div class="main-body">
  <div class="page-wrapper">
    <inputcontrol-component
    :engraving_orders='@json($engraving_orders)' 
    :work_shift_code='@json($work_shift_code)' 
    :operator='@json($operator)' 
    :operations='@json($operations)'
    >
    </inputcontrol-component>     
  </div>
</div>

@endsection,
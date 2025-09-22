@routes(['ugpc.getwarehouseshafts','ugpc.defectshaft',
'ugpc.getconsumpshaft','ugpc.deleteconsumpshaft','ugpc.getoperationsmachine',
'ugpc.getsecondoperationsmachine','ugpc.startoperation','ugpc.getcurrentoperation',
'ugpc.deleteoperation','ugpc.closeoperation','ugpc.consumpshaft','ugpc.addprecopperplating','ugpc.skipdefect'])
@extends('layouts.portal')

@section('content')
<div class="main-body">
  <div class="page-wrapper">
    <machine-component
    :machines='@json($machines)' 
    :work_shift_code='@json($work_shift_code)' 
    :operator='@json($operator)' 
    :engraving_heads='@json($engraving_heads)' 
    >
    </machine-component>     
  </div>
</div>

@endsection,
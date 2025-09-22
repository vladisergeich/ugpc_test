@extends('layouts.portal')

@section('content')
<div class="main-body">
  <div class="page-wrapper">
    <electroplatingcenter-component
    :operations='@json($operations)' 
    :work_shift_code='@json($work_shift_code)' 
    :machines='@json($machines)' 
    :secondary_operation='@json($secondary_operation)' 
    urlpostsecondaryoperation="{{route('ugpc.electroplating.postsecondaryoperation')}}"
    >
    </electroplatingcenter-component>     
  </div>
</div>

@endsection,
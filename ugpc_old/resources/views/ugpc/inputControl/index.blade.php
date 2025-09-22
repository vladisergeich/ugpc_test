@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <div class="row">

      <div class="col-md-12">
            
        <inputcontrollist-component 
          :shafts='@json($shafts)' 
          :input_control_shafts='@json($input_control_shafts)'
          :secondary_operations='@json($secondary_operations)'
          urlprintlabel="{{route('ugpc.inputcontrol.createinputcontrolcard')}}"
          urlstartoperation="{{route('ugpc.inputcontrol.startoperation')}}"
          urlinputcontrolcard="{{route('ugpc.inputcontrol.updateinputcontrolcard')}}"
        >
        </inputcontrollist-component>  

      </div>
 
    </div>
    </div>
  </div>
</div>

@endsection,

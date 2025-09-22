@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <div class="row">

      <div class="col-md-12 okvid-card">
            
        <okvidcard-component 
          :customers='@json($customers)' 
          :mountig_parameters='@json($mountig_parameters)' 
          :macroorder='@json($macroorder)' 
          urlokvid="{{route('ugpc.getokvid')}}" 
          urladdokvid="{{route('ugpc.addokvid')}}" 
          urlupdateokvid="{{route('ugpc.bdgp.update.okvid')}}"
          urladdsection="{{route('ugpc.bdgp.addsection')}}"
        >
        </okvidcard-component>  

      </div>
 
    </div>
    </div>
  </div>
</div>

@endsection,

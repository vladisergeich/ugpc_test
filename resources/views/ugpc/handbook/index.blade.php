@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <div class="row">
      <div class="col-md-12">
            
            <handbook-component 
              :customers='@json($customers)' 
              urlproductinfo="{{route('ugpc.getproductinfo')}}" 
              urlgetshaftsorders="{{route('ugpc.getshaftsorders')}}" 
              :formats='@json($formats)'
              :shaft_resources='@json($shaft_resources)'
              :shafts='@json($shafts)'
              urlgp="{{route('ugpc.gethandbookdatagp')}}" 
              urlval="{{route('ugpc.gethandbookdataval')}}"
              urlchangedateorders="{{ route('ugpc.handbook.changedateorders') }}"
            >
            </handbook-component>  

      </div>
      
    </div>
    </div>
  </div>
</div>

@endsection,







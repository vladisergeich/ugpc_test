@extends('layouts.portal')

@section('content')
<div class="main-body">
  <div class="page-wrapper">
    <engravingorders-component
    :engraving_orders='@json($engraving_orders)' 
    :engraving_orders_ended='@json($engraving_orders_ended)' 
    >
    </engravingorders-component>     
  </div>
</div>

@endsection,
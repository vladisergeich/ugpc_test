@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <div class="row">

      <div class="col-md-12">
            
        <register-component
        :customers='@json($customers)'
        :product_type='@json($product_type)'
        :profiles='@json($profiles)'
        >
        </register-component>

      </div>
 
    </div>
    </div>
  </div>
</div>

@endsection,

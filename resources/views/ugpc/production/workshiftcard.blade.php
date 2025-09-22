@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <div class="row">
      <div class="col-md-12">
        <workshiftcard-component :shaftorders='@json($shaftorders)'></workshiftcard-component> 
      </div>
    </div>
    </div>
  </div>
</div>

@endsection,







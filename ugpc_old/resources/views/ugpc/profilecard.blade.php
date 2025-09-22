@extends('layouts.portal')

@section('content')
@routes(['ugpc.profileregistry.addrow','ugpc.profileregistry.saveprofile','ugpc.profileregistry.copyprofile','ugpc.profileregistry.senddatatonav'])
<div class="main-body">
  <div class="page-wrapper">
    <profilecard-component
        :profile='@json($profile)'
    >
    </profilecard-component>     
  </div>
</div>

@endsection,
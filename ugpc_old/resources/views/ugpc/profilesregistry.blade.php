@extends('layouts.portal')

@section('content')
@routes(['ugpc.profileregistry.getprofiles','ugpc.profileregistry.addnewprofile'])
<div class="main-body">
    <div class="page-wrapper">

        <profileregistry-component
          :vendors='@json($vendors)' 
          :user='@json($user)'
          :type='@json($type)'
        >
        </profileregistry-component>  
    </div>
</div>

@endsection,

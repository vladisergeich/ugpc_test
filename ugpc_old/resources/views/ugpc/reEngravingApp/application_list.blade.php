@extends('layouts.portal')

@section('content')
<div class="page-wrapper">
    <reengravingapps-component
        :applications='@json($applications)'
        :orders='@json($orders)'
        :customers='@json($customers)'
    >
    </reengravingapps-component>  
</div>



@endsection,

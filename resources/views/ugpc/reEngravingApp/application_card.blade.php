@extends('layouts.portal')

@section('content')
@routes(['ugpc.reengravingapps.approvestage','ugpc.reengravingapps.changeorder','ugpc.reengravingapps.translationstage'])
<div class="page-wrapper">
    <reengravingapp-component
        :application='@json($application)'
        :message='@json($message)'
    >
    </reengravingapp-component>  
</div>


@endsection,

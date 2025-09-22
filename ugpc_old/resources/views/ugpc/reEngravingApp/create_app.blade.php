@extends('layouts.portal')

@section('content')
@routes(['ugpc.reengravingapps.createapp.search','ugpc.reengravingapps.postapp'])
<div class="page-wrapper">
    <createappblock-component
    >
    </createappblock-component>  
</div>


@endsection,

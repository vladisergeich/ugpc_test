@extends('layouts.portal')

@routes(['ugpc.sendrequestshaft.addrequest'])
@section('content')

<div class="main-body">
    <div class="page-wrapper">
        <transfer-page
        :transfers='@json($shaftrequests)' 
        >
        </statistic-page>
    </div>
</div>

@endsection

<style>
.request_link {
    color: white;
}

</style>



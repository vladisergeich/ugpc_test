@extends('layouts.portal')

@section('content')

<div class="main-body">
    <unloadingorders-component
        :unloading_orders='@json($unloading_orders)'
        :engravers='@json($engravers)'
        :conditions='@json($conditions)'
        :movement_statuses='@json($movement_statuses)'
        :movement_novizna='@json($movement_novizna)'
        :customers='@json($customers)'
        urlsavemovementdata="{{ route('ugpc.movementorders.savemovementdata') }}"
        urldistributeorders="{{ route('ugpc.movementorders.distributeorders') }}"
    >
    </unloadingorders-component>
</div>

@endsection

<style>

</style>



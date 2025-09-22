@extends('layouts.portal')

@section('content')

<div class="main-body">
    <electroplating-component
        :operations='@json($operations)'
        :secondary_operations='@json($secondary_operations)'
        :shafts_for_electroplating='@json($shafts_for_electroplating)'
        :shafts_on_electroplating='@json($shafts_on_electroplating)'
        :machines='@json($machines)'
        urlstartsecondaryoperation="{{route('ugpc.electroplating.startsecondaryoperation')}}"
    >
    </electroplating-component>
</div>

@endsection

<style>

</style>



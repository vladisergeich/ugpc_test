@extends('layouts.portal')

@section('content')

<div class="main-body">
    <planning-component
        :orders_timing='@json($orders_timing)'
        :orders_in_okvid='@json($orders_in_okvid)'
        :order_prepress_external='@json($order_prepress_external)'
        :orders_in_grav_external='@json($orders_in_grav_external)'
        :orders_grav_external='@json($orders_grav_external)'
        :orders_approved_arr='@json($orders_approved_arr)'
        :shafts_excel='@json($shafts_excel)'
        urlokvidfulfilled="{{ route('ugpc.planning.okvidfulfilled') }}"
    >
    </planning-component>
</div>

@endsection

<style>

</style>



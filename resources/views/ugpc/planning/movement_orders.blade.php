@extends('layouts.portal')

@section('content')
@routes(['ugpc.executionplan.saveplan'])
<div class="main-body">
    <movementorders-component
        :movement_orders='@json($movement_orders)'
        :engravers='@json($engravers)'
        :conditions='@json($conditions)'
        :movement_statuses='@json($movement_statuses)'
        :movement_novizna='@json($movement_novizna)'
        :execution_plan='@json($execution_plan)'
        urlsavemovementdataprioruty="{{ route('ugpc.movementorders.savemovementdataprioruty') }}"
        urlsavemovementdatarow="{{ route('ugpc.movementorders.savemovementdatarow') }}"
        :downtimes='@json($downtimes)'
        :customers='@json($customers)'
        urladddowntime="{{ route('ugpc.movementorders.adddowntime') }}"
        urlpostmovementorders="{{ route('ugpc.movementorders.postmovementorders') }}"
        urldeleterow="{{ route('ugpc.movementorders.deleterow') }}"
        urlbreakrow="{{ route('ugpc.movementorders.breakrow') }}"
        urlbackbreakrow="{{ route('ugpc.movementorders.backbreakrow') }}"
        urlcancelchanges="{{ route('ugpc.movementorders.cancelchanges') }}"
        urldailydistribution="{{ route('ugpc.movementorders.dailydistribution') }}"
        urldeletedowntime="{{ route('ugpc.movementorders.deletedowntime') }}"
    >
    </movementorders-component>
</div>

@endsection

<style>

</style>



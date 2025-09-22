@extends('layouts.portal')

@section('content')

<div class="main-body">
    <executionplan-component
        :execution_plan='@json($execution_plan)'
        urlsaveplan="{{ route('ugpc.executionplan.saveplan') }}"
    >
    </executionplan-component>
</div>

@endsection

<style>

</style>



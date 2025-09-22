@extends('layouts.portal')

@routes(['ugpc.productionmanager.getPlan'])
@section('content')

<div class="main-body">
    <production-manager-page></production-manager-page>
</div>

@endsection


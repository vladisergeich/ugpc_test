@extends('layouts.portal')

@routes(['ugpc.statistic.getData','ugpc.statistic.getWorkCenters','ugpc.statistic.getOperatorData','ugpc.statistic.getShiftData','ugpc.statistic.getOperations'])
@section('content')

<div class="main-body">
    <div class="page-wrapper">
        <statistic-page></statistic-page>
    </div>
</div>

@endsection


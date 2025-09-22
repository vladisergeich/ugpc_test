@extends('layouts.portal')

@section('content')


    <dailyplan-component
    :daily_plan_shafts='@json($daily_plan_shafts)'
    :operations='@json($operations)'
    :work_shift_codes='@json($work_shift_codes)'
    urlgetworkshiftcode="{{ route('ugpc.dailyplan.getworkshiftcode') }}"
    >
    </dailyplan-component>  


@endsection,

@extends('layouts.portal')

@section('content')


    <applicationlist-component
    :applications='@json($applications)'
    :formats='@json($formats)'
    :vendors='@json($vendors)'
    :customers='@json($customers)'
    urladdapp="{{route('ugpc.bdgp.appsteel.addapplication')}}"
    urlgetshaftslist="{{route('ugpc.bdgp.appsteel.getshaftslist')}}"
    urldeleteallshafts="{{route('ugpc.bdgp.appsteel.deleteallshafts')}}"
    urladdshafts="{{route('ugpc.bdgp.appsteel.addshafts')}}"
    urldeleteshaft="{{route('ugpc.bdgp.appsteel.deleteshaft')}}"
    urlsubmitapp="{{route('ugpc.bdgp.appsteel.submitapp')}}"
    urluploadfiles="{{route('ugpc.bdgp.appsteel.uploadfiles')}}"
    urldeletefiles="{{route('ugpc.bdgp.appsteel.deletefiles')}}"
    urlpostapp="{{route('ugpc.bdgp.appsteel.postapp')}}"
    >
    </applicationlist-component>  


@endsection,

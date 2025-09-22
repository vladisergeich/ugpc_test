@extends('layouts.portal')


@section('content')

<div class="main-body">
  <div id="app">
    <routemaplist-component 
      :routemapslist='@json($routemapslist)' 
      :routemapslistwork='@json($routemapslistwork)' 
      :routemapslistnotwork='@json($routemapslistnotwork)' 
      urlsubmit="{{ route('ugpc.routemap.updateroutemap') }}"
      urlpostroutemap="{{ route('ugpc.routemap.postroutemap') }}"
      urlcreatetestroutemap="{{ route('ugpc.routemap.createtestroutemap') }}"
      urladdtestmap="{{ route('ugpc.routemap.addtestmap') }}"
      urldeletemap="{{ route('ugpc.routemap.deletemap') }}"
      urladdnewroutemap="{{ route('ugpc.routemap.addnewroutemap') }}"
      urlreplaceshaft="{{ route('ugpc.routemap.replaceshaft') }}"
      urlskipbrakshaft="{{ route('ugpc.routemap.skipbrakshaft') }}"
    >
    </routemaplist-component>  
  </div>
</div>

@endsection,

<script>
 
</script>
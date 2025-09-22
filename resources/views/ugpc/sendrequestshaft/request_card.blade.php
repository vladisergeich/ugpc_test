@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <div class="row">

      <div class="col-md-12 okvid-card">
            
        <sendrequestshaft-component 
          :shafts='@json($shafts)' 
          :shaftrequest='@json($shaftrequest)' 
          :shaftrequestcomposition='@json($shaftrequestcomposition)' 
          urlrequest="{{route('ugpc.sendrequestshaft.getrequest')}}"
          :designers='@json($designers)' 
          urladdrequestorder="{{route('ugpc.sendrequestshaft.addrequestorder')}}"
          urldeleterequestorder="{{route('ugpc.sendrequestshaft.deleterequestorder')}}"
          :vendors='@json($vendors)' 
          urlgetokvid="{{route('ugpc.sendrequestshaft.getokvid')}}"
          urlsendpdf="{{route('ugpc.sendrequestshaft.sendpdf')}}"
          urlgetpdf="{{route('ugpc.sendrequestshaft.getpdf')}}"
          urladdrequest="{{route('ugpc.sendrequestshaft.addrequest')}}"
          urlsubmitrequestcomposition="{{route('ugpc.sendrequestshaft.submitrequestcomposition')}}"
          urlsubmitrequest="{{route('ugpc.sendrequestshaft.submitrequest')}}"
          urlpostshaftrequest="{{route('ugpc.sendrequestshaft.postshaftrequest')}}"
          urlconfirmrequest="{{route('ugpc.sendrequestshaft.confirmrequest')}}"
          urlupdaterequest="{{route('ugpc.sendrequestshaft.updaterequest')}}"
          urlupdateshaft="{{route('ugpc.sendrequestshaft.updateshaft')}}"
        >
          
        </sendrequestshaft-component>  

      </div>
 
    </div>
    </div>
  </div>
</div>

@endsection,


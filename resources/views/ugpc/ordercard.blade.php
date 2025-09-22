@extends('layouts.portal')

@section('content')
@routes(['ugpc.alterationstage','ugpc.replaceshaft'])
<div class="main-body">
  <div class="page-wrapper">
    <ordercard-component
    :engraving_order='@json($engraving_order)'
    urlupdateorder="{{ route('ugpc.engravingorders.updateorder') }}"
    >
    </ordercard-component>     
  </div>
</div>

@endsection,
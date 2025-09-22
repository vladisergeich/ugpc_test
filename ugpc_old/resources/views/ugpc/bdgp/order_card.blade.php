@extends('layouts.portal')

@routes(['ugpc.bdgp.autoapproval'])
@section('content')

<div class="main-body">
    <div class="row">
      <div class="col-md-12">
          <div data-toggle="collapse" data-target="#collapse-41" aria-expanded="false" aria-controls="#collapse-41" class="card-header header-with-icon" style="cursor: pointer;">
            Реестр
          </div> 
          <div id="collapse-41" class="collapse">
            <div class="card-body py-2">
              <handbook-component 
              :customers='@json($customers)' 
              :shaftorders='@json($shaftorders)'  
              :shafts_excel='@json($shafts_excel)'  
              :formats='@json($formats)'
              :vendors='@json($vendors)' 
              :shaft_resources='@json($shaft_resources)'
              urlgp="{{route('ugpc.gethandbookdatagp')}}" 
              urlval="{{route('ugpc.gethandbookdataval')}}"
              urlgetproductinfo="{{route('ugpc.getproductinfo')}}" 
              urlgetshaftinfo="{{route('ugpc.getshaftinfo')}}" 
              urladdshaft="{{route('ugpc.newshaft')}}" 
              urlsubmitshaftnote="{{route('ugpc.submitshaftnote')}}" 
              urladdformat="{{route('ugpc.newformat')}}" 
              urladdcustomer="{{route('ugpc.newcustomer')}}"
              urlchangedateorders="{{route('ugpc.handbook.changedateorders')}}"
              ></handbook-component>  
            </div>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
          <div data-toggle="collapse" data-target="#macro_param" aria-expanded="true" aria-controls="#macro_param" class="card-header header-with-icon" style="cursor: pointer;">
            Параметры Макрозаказа
          </div> 
          <div id="macro_param" class="collapse show">
            <div class="card-body py-2">
              <okvidcard-component 
                :customers='@json($customers)' 
                :mountig_parameters='@json($mountig_parameters)' 
                :macroorder='@json($macroorder)' 
                urlokvid="{{route('ugpc.getokvid')}}" 
                urladdokvid="{{route('ugpc.addokvid')}}" 
                urlupdateokvid="{{route('ugpc.bdgp.update.okvid')}}"
              >
              </okvidcard-component>  
            </div>
          </div>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
          <orderparametr-component 
          :user='@json($user)' 
          :orders='@json($orders)' 
          :osz='@json($osz)'  
          :designers='@json($designers)' 
          :formats='@json($formats)' 
          :profile='@json($profile)' 
          :vendors='@json($vendors)' 
          :orderstatus='@json($orderstatus)' 
          :orderstate='@json($orderstate)' 
          :materials='@json($materials)'
          url="{{ route('ugpc.bdgp.update') }}"
          urldouble="{{ route('ugpc.bdgp.double') }}"
          :producttypes='@json($producttypes)'
          urlstreamxml="{{ route('ugpc.bdgp.createstreamxml') }}" 
          :layoutconstructor='@json($layoutconstructor)' 
          urlshaftpdf="{{ route('ugpc.bdgp.invoices.download') }}" 
          urladdstream="{{ route('ugpc.addstream') }}"
          urllayout="{{ route('ugpc.getlayout') }}"
          urldeletestream="{{ route('ugpc.deletestream') }}"
          urlsubmitstream="{{ route('ugpc.submitstream') }}"
          urlcreateroutemap="{{ route('ugpc.createroutemap') }}"
          urlshaftsxml="{{ route('ugpc.bdgp.createshaftsxml') }}"
          urlchecknavparam="{{ route('ugpc.checknavparam') }}"
          urlscpreset="{{ route('ugpc.scpreset') }}"
          urlrmpreset="{{ route('ugpc.rmpreset') }}"
          urlnextokvid="{{ route('ugpc.nextokvid') }}"
          :shaftordersallokvid='@json($shaftordersallokvid)'
          :shafts_resource='@json($shafts_resource)'
          :last_request_order='@json($last_request_order)'

            :ordergp='@json($ordergp)' 
            :orders='@json($orders)'
            urldeletegp="{{ route('ugpc.deletegp') }}" 
            urlgetstreams="{{ route('ugpc.getstreams') }}"
            urladdgp="{{ route('ugpc.addgp') }}"

            :shaftresourses='@json($shaftresourses)' 
            :orders='@json($orders)' 
            :shaftorders='@json($shaftorders)' 
            :shafts='@json($shafts)' 
            :colors='@json($colors)'
            urlgetshafts="{{ route('ugpc.getshafts') }}"
            urlsubmitshaft="{{ route('ugpc.submitshaft') }}"
            urldeleteshaft="{{ route('ugpc.deleteshaft') }}"
            urlgetshaft="{{ route('ugpc.getshafts') }}"
            urladdshaft="{{ route('ugpc.addshaft') }}"
            urlshaftresource="{{ route('ugpc.shaftresource') }}"
            urlshafttransfer="{{ route('ugpc.shafttransfer') }}"
            urladdresource="{{ route('ugpc.addshaftresource') }}"
            urlgetshaftresource="{{ route('ugpc.getshaftresource') }}"
            urlsubmitresource="{{ route('ugpc.submitresource') }}"
            urldeleteresource="{{ route('ugpc.deleteshaftresource') }}"
            urladdsection="{{route('ugpc.bdgp.addsection')}}"
            urlsearchorder="{{route('ugpc.bdgp.searchorder')}}"
            urlsearchgp="{{route('ugpc.bdgp.searchgp')}}"
            urlsearchshaft="{{route('ugpc.bdgp.searchshaft')}}"
            urlfreeshaft="{{ route('ugpc.freeshaft') }}"
            urlbackshaft="{{ route('ugpc.backshaft') }}"
            urlcopyshaftparametrs="{{ route('ugpc.copyshaftparametrs') }}"
        >
        </orderparametr-component> 
      </div> 

    </div>
</div>

@endsection,

<script>
 
</script>

<style>

.form-check_materials {
  display: flex;
  flex-direction: column;

}

.form-check_tech {
  display: flex;
  flex-direction: column;
}

.order-card {
  margin-top: 10px;
}

.okvid-card {
  margin-top: 10px;
}

.navbar  {
  display: none !important;
}
</style>



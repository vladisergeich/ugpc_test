@extends('layouts.portal')

@section('content')

<div class="main-body">
  <div class="page-wrapper">
    <div class="row">
      <div class="col-lg-6">
        <button  class="btn btn-primary btn-sm"><a class="okvid_link" href="{{route('ugpc.okvidcard')}}">Создать ОКВИД</a></button>
      </div>
    </div>
    <div class="row">
      <div class="card">
        <div class="card-block">
          <div class="dt-responsive table-responsive">
            <div id="zero-configuration_wrapper" class="dataTables_wrapper dt-bootstrap4">
              <div class="row">
                <div class="col-xs-12 col-sm-12">
                  <table id="zero-configuration" class="display table nowrap table-striped table-hover dataTable" role="grid" aria-describedby="zero-configuration_info">
                    <thead>
                      <tr role="row">
                        <th class="sorting" tabindex="0" aria-controls="zero-configuration" rowspan="1" colspan="1" style="width: 30%;">
                          № ОКВИД
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="zero-configuration" rowspan="1" colspan="1" style="width: 30%;">
                          Описание
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="zero-configuration" rowspan="1" colspan="1" style="width: 30%;">
                          Дата создания
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="zero-configuration" rowspan="1" colspan="1" style="width: 30%;">
                          Клиент
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="zero-configuration" rowspan="1" colspan="1" style="width: 30%;">
                          Примечание
                        </th>
                        <th class="sorting" tabindex="0" aria-controls="zero-configuration" rowspan="1" colspan="1" style="width: 30%;">
                          
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($orders as $order)
                        <tr role="row"> 
                          <td class="dataTables_empty">{{$order->macro_id}}</td>
                          <td>{{$order->description}}</td>
                          <td>{{$order->create_date}}</td>
                          <td>{{$order->customer}}</td>
                          <td>{{$order->note}}</td>
                          <td><button  class="btn btn-primary btn-sm"><a class="okvid_link" href="{{route('ugpc.bdgp.ordercard',$order)}}">Открыть</a></button></td>
                      @endforeach
                    </tbody>
                    <tfoot></tfoot>
                  </table>
                </div>
              </div>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

<style>

.card {
  
  width: 100%;
}

.okvid_link {
  color: white;
}

.order-card {
  margin-top: 10px;
}

.okvid-card {
  margin-top: 10px;
}
</style>



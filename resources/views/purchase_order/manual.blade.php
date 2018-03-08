@extends('layouts.custom')

@section('content')

<!-- Page content -->
<section class="content">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-9">
            <h4>Retail Order Template</h4>
            
          </div>
          <div class="col-xs-3">

          </div>
        </div>
      </div>
      <div class="panel-body manual" style="margin: 30px 0px;">
        <div class="row purchase_menu" style="margin-bottom: 20px;">
            <ul class="purchase_order_style navbar-nav" >
                <li class="active">
                    <a>Manual P.O.</a>
                </li>

                <li class="">
                    <a href="{{route('purchase_order.create_automate') }}">Auto-generate P.O.</a>
                </li>
                <li class="last_item"></li>
            </ul>
        </div>

        <div class="row purchase_header_form">
          <form class="form-inline" action="/action_page.php">
            <div id="city-list" class="form-group">
              <label>City</label>
              <select class="form-control" style="width: 300px;" name="" id="">
                <option value=""></option>
                @foreach($cities as $city)
                  <option value="{{$city->City_ID}}">{{$city->City}}</option>
                @endforeach
              </select>
            </div>
            <div class="checkbox">
              <label><input type="checkbox" id="all_cities_checkbox"> All Cities</label>
            </div>
          </form>
        </div>
        <div class="row purchase_choose">
          <div class="col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4>Branch</h4>
              </div>

              <div class="panel-body first">
                <div>
                  <ul id="selectable">

                  </ul>

                </div>

              </div>
            </div>

            <form action="">
              <div class="form-group">
                <label class="checkbox-inline">
                  <input type="checkbox" value="">NetExpress
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" value="">Sequel
                </label>
                <label class="checkbox-inline">
                  <input type="checkbox" value="">iSing
                </label>
              </div>
              <div class="form-group">
                <label>Date Range</label>
                
              <div class="row">
                <div class="col-md-6">
                  <label for="">From</label>
                  <input type="date" name="" id="" class="datepicker" >
                </div>

                <div class="col-md-6">
                  <label for="">From</label>
                  <input type="date" name="" id="" class="datepicker">
                </div>
              </div>

              </div>
              <div class="form-group">
                <div class="col-md-3">
                  <label class="">Multiplier</label>
                </div>
                <div class="col-md-9">
                  <input class="form-control" type="text">
                </div>
              </div>
            </form>
          </div>

          <div class="col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4>Product Line</h4>
              </div>

              <div class="panel-body">
                <ul>
                  @foreach($prodlines as $prodline)
                    <li class="ui-widget-content">
                      <label class="label-control"><input type="checkbox" value="{{$prodline->ProdLine_ID}}">{{ $prodline->Product }}</label>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4>Item Code</h4>
              </div>

              <div class="panel-body">
              </div>
            </div>
          </div>

        </div>

      </div>

      <div class="panel-footer">
        <div class="row">
        <div class="pull-right">
          <button class="btn btn-success">Generate PO</button>
        </div>

        </div>

      </div>
    
    </div>
  </div>
</div>

</section>

@endsection

@extends('layouts.app')
@section('header-scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.0/chosen.min.css" rel="stylesheet" >
    <style>
        .panel-footer {
            background-color: #FFF;
        }

        .form-horizontal {
            font-size: 0.8em;
        }

        .itemActive, .itemPrintStub, .itemTrackIventory{
            position: absolute;
            right: -8px;
            top: -2px;
            width: 18px;
            height: 18px;

        }

        .panel-body {
            padding: 15px !important;
        }

        .modal {
            z-index: 10001 !important;;
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
                <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                  <div id="sidebar_menu" class="sidebar-nav">
                    <ul></ul>
                  </div>
                </div>

                <!-- Page content -->
                <div id="page-content-togle-sidebar-sec">
                    @if(Session::has('success'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('success') !!}</em></div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('error') !!}</em></div>
                    @endif
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Edit Inventory</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                            Update Item
                                        </div>
                                        <div class="col-xs-6 text-right">

                                        </div>
                                    </div>

                                </div>
                                <form class="form-horizontal" action="{{ route('inventory.update', $inventory->item_id) }}" METHOD="post">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemCode">Item Code:</label>
                                                    <div class="col-md-8">
                                                        <input id="itemCode" name="itemCode" type="text" value="{{ $inventory->ItemCode }}" class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="barcodeNum">Barcode number:</label>
                                                    <div class="col-md-8">
                                                        <input id="barcodeNum" name="barcodeNum" type="text" value="{{ $inventory->barcode }}" class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemType">Type:</label>
                                                    <div class="col-md-8">
                                                        <select class="form-control" id="itemType" name="itemType">
                                                            <option  disabled>Select Type</option>
                                                            @foreach($invTypes as $invType)
                                                                <option value="{{ $invType->inv_type }}" {{ $inventory->Type == $invType->inv_type
                                                     ? 'selected' : ''}}>{!! $invType->type_desc !!}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemBrand">Brand:</label>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-8" style="margin-left: -16px;">
                                                                <select class="form-control" id="itemBrand" name="itemBrand">
                                                                    <option selected disabled>Select Brand</option>
                                                                    @foreach($brands as $brand)
                                                                        <option value="{{ $brand->Brand_ID }}" {{ $inventory->Brand_ID == $brand->Brand_ID
                                                     ? 'selected' : ''}}>{!! $brand->Brand !!}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <a href="#" data-toggle="modal" data-target="#addNewBrand" class="addingOther">Add Brand</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemProduct">Product:</label>
                                                    <div class="col-md-8">
                                                        <div class="row">
                                                            <div class="col-md-8" style="margin-left: -16px;">
                                                                <select class="form-control" name="itemProduct" id="itemProduct">
                                                                    <option value="">Select Product</option>
                                                                    @foreach($products as $product)
                                                                        <option value="{!! $product->ProdLine_ID !!}" {{ $product->ProdLine_ID == $inventory->Prod_Line
                                                     ? 'selected' : ''}}>{!! $product->Product !!}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <a href="#" data-toggle="modal" data-target="#addNewProductLine" class="addingOther">Add Product</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemDescription">Description:</label>
                                                    <div class="col-md-8">
                                            <textarea class="form-control" rows="3" id="itemDescription"
                                                      name="itemDescription" value="{{ $inventory->Description }}">{{ $inventory->Description }}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemUnit">Unit:</label>
                                                    <div class="col-md-6">
                                                        <input id="itemUnit" name="itemUnit" value="{{ $inventory->Unit }}" type="text" class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemPackageQuantity">Package Qty:</label>
                                                    <div class="col-md-6">
                                                        <input id="itemPackageQuantity" name="itemPackageQuantity" value="{{ $inventory->Packaging }}" type="text" class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemThresholdQty">Threshold Qty:</label>
                                                    <div class="col-md-6">
                                                        <input id="itemThresholdQty" name="itemThresholdQty" value="{{ $inventory->Threshold }}"  type="text" class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemMultipDays">Multiplyer (days):</label>
                                                    <div class="col-md-6">
                                                        <input id="itemMultipDays" name="itemMultipDays" type="text" value="{{ $inventory->Multiplier }}" class="form-control input-md">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-md-4 control-label" for="itemMinLevel">Min Level:</label>
                                                    <div class="col-md-6">
                                                        <input id="itemMinLevel" name="itemMinLevel" type="text" value="{{ $inventory->Min_Level }}" class="form-control input-md">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-gorup">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <input type="checkbox" @if($inventory->Active == 1) checked @endif name="itemActive" class="itemActive"/>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <label class="pull-left" for="itemActive">Active</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-gorup" style="margin-top: 15px">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <input type="checkbox" name="itemPrintStub" @if($inventory->Print_This == 1) checked @endif class="itemPrintStub"/>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <label class="pull-left" for="itemPrintStub">Print to Stub</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-gorup" style="margin-top: 15px">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <input type="checkbox" name="itemTrackIventory" @if($inventory->TrackThis == 1) checked @endif class="itemTrackIventory"/>
                                                        </div>
                                                        <div class="col-md-10">
                                                            <label class="pull-left" for="itemTrackIventory">Track Inventory</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-footer">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <a href="{{ url('/inventory') }}" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply"></i>&nbspBack</a>
                                            </div>
                                            <div class="col-sm-6">
                                                {{ method_field('PUT') }}
                                                {!! csrf_field() !!}
                                                <button type="submit" class="btn btn-success pull-right">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal add new brand -->
        <div id="addNewProductLine" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">Add New Product Line</h5>
                    </div>
                    <form class="form-horizontal" action="{{ url('/productlines') }}" METHOD="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="prodcutLineName">Product Line</label>
                                <div class="col-md-8">
                                    <input id="prodcutLineName" name="prodcutLineName" type="text" class="form-control input-md" required="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</button>
                                </div>
                                <div class="col-sm-6">
                                    {!! csrf_field() !!}
                                    <button type="submit" class="btn btn-primary pull-right">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end modal for creating new product line -->
        <!-- Modal add new brand -->
        <div id="addNewBrand" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">Add New Brand</h5>
                    </div>
                    <form class="form-horizontal" action="{{ url('/brands') }}" METHOD="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="brandName">Brand Name</label>
                                <div class="col-md-8">
                                    <input id="brandName" name="brandName" type="text" class="form-control input-md" required="">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row">
                                <div class="col-sm-6">
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply"></i>&nbspBack</button>
                                </div>
                                <div class="col-sm-6">
                                    {!! csrf_field() !!}
                                    <button type="submit" class="btn btn-primary pull-right">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- end modal for creting new brand -->
    </div>
@endsection
@section('footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.0/chosen.jquery.min.js"></script>
    <script>
        (function($){
            $('#itemProduct, #itemBrand, #itemType').chosen();
        })(jQuery);
    </script>
@endsection
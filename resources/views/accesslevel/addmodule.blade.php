@extends('layouts.app')

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
        <div class="col-md-12">
        <h3 class="text-center">Manage Modules</h3>
            <div class="panel panel-default">
                <div class="panel-heading">{{isset($detail_edit_module->module_id) ? "Edit " : "Add " }} Module</div>
                <div class="panel-body">
                    <form class="form-horizontal form" role="form" method="POST" action="" id ="moduleform">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('corp_name') ? ' has-error' : '' }}">
                            <label for="corp_nam" class="col-md-4 control-label">Corporation Name</label>
                            <div class="col-md-6">
                                <select class="form-control required" id="corp_name" name="corp_id">
                                    <option value="">Choose Corporation Name</option>
                                        @foreach ($corporation as $corp) 
                                            <option {{ (isset($detail_edit_module->corp_id) && ($detail_edit_module->corp_id == $corp ->corp_id)) ? "selected" : "" }} value="{{ $corp ->corp_id }}">{{ $corp->corp_name }}</option>
                                          
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('module_name') ? ' has-error' : '' }}">
                            <label for="module" class="col-md-4 control-label">Module</label>
                            <div class="col-md-6">
                                <input id="module_name" type="text" class="form-control required" name="module_name"  value="{{isset($detail_edit_module->description) ? $detail_edit_module->description : "" }}" autofocus>

                                @if ($errors->has('module_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('module_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                          <div class="col-md-6">
                              <a type="button" class="btn btn-default" href="{{ URL('list_module') }}">
                              Back
                              </a>
                          </div>
                          <div class="col-md-6">
                              <button type="submit" class="btn btn-primary pull-right save_button">
                                    {{isset($detail_edit_module->module_id) ? "Save " : "Create " }} 
                                </button>
                          </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script>
$(function(){
    $("#moduleform").validate();   
});
</script>
@endsection

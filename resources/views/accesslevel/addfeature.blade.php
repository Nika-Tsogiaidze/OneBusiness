@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Feature</div>
                <div class="panel-body">
                    <form class="form-horizontal form" role="form" method="POST" action="" id ="featureform">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('module_name') ? ' has-error' : '' }}">
                            <label for="module_nam" class="col-md-4 control-label">Module Description</label>
                            <div class="col-md-6"> 
                                <select class="form-control required" id="module_name" name="module_id">
                                    <option value="">Choose Module</option>
                                        @foreach ($module as $modul) 
                                            <option {{ (isset($detail_edit_feature->module_id) && ($detail_edit_feature->module_id == $modul ->module_id)) ? "selected" : "" }} value="{{ $modul ->module_id }}">{{ $modul->description }}</option>
                                          
                                        @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('feature_name') ? ' has-error' : '' }}">
                            <label for="feature" class="col-md-4 control-label">Feature</label>
                            <div class="col-md-6">
                                <input id="feature_name" type="text" class="form-control required" name="feature_name"  value="{{isset($detail_edit_feature->feature) ? $detail_edit_feature->feature : "" }}" autofocus>
                                @if ($errors->has('feature_name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('feature_name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $("#featureform").validate();   
});
</script>
@endsection


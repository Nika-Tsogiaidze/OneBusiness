@extends('layouts.app')

@section('content')
<style>
#branch_name {margin-right: 15px;}
.user_temp {margin-left: 30px;}
input.area_user {margin-right: 6px;}
.branch_assign{padding: 0px !important;}
.combine_branch {padding-left: 27px;}
.save_button{margin-right: 15px;}
.label-superAdmin{margin: 10px;}
.grp-brnch {float: left;margin-bottom: 5px;padding: 0px !important;}
.modal-backdrop {background: none;}

</style>

<div class="container-fluid">
	<input type="hidden" />
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
                @if(Session::has('alert-class'))
                    <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                @elseif(Session::has('flash_message'))
                    <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                @endif
                <div class="col-md-12 col-xs-12">
                    <h3 class="text-center">Manage Users</h3>
                    <div class="panel panel-default">
                        <?php
                            if(!empty($detail_edit_sysuser->uname)){
                                $username = $detail_edit_sysuser->uname;
                            }else{
                                $username = $detail_edit_sysuser->UserName;
                            } 
                        ?>
                        <div class="panel-heading">USER ACCESS PROFILE&nbsp;:&nbsp;{{ $username }}</div>
                        <div class="panel-body">
                            <form class="form-horizontal form-group" role="form" method="POST" id ="userform">
                                {{ csrf_field() }}
                                <input type="hidden" name="userid" id="userid" value="{{isset($detail_edit_sysuser->UserID) ? $detail_edit_sysuser->UserID : '' }}">

                                <div class="form-group{{ $errors->has('user_name') ? ' has-error' : '' }}">
                                    <label for="user_nam" class="col-md-12">ACCESS RIGHTS TEMPLATE</label>
                                    <div class="col-md-6 user_temp">
                                        <select class="form-control required template_name" id="temp_name" name="temp_id">
                                            <option value="">Select a Template</option>
                                                @foreach ($template as $temp) 
                                                    <option value="{{ $temp ->template_id }}" is-admin="{{ $temp ->is_super_admin }}"{{ (isset($detail_edit_sysuser->rights_template_id) && ($detail_edit_sysuser->rights_template_id == $temp ->template_id)) ? "selected" : "" }}>{{ $temp->description }} </option> 
                                                @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-5 label-superAdmin"></div>
                                </div>
                                <div class="form-group{{ $errors->has('active_group') ? ' has-error' : '' }}">
                                    <label for="active_grp" class="col-md-12">BRANCH ASSIGNMENT</label>
                                    <div class="col-md-12 user_temp">
                                    <div class="branch_assign col-md-1">Area Type:</div>
                                        <div class="col-md-2">
                                            <label class="mt-radio">
                                                <input class="area_type area_user" id="areatype" type="radio" name="area_type" value="PR" {{ (isset($detail_edit_sysuser->Area_type) && ($detail_edit_sysuser->Area_type == "PR")) ? "checked" : ''  }}
                                                >Province
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="mt-radio">
                                                <input class="area_type area_user" id="areatype" type="radio" name="area_type" value="CT" {{ (isset($detail_edit_sysuser->Area_type) && ($detail_edit_sysuser->Area_type == "CT")) ? "checked" : ''  }} >
                                                City
                                            </label>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="mt-radio">
                                                <input class="area_type area_user" id="areatype" type="radio" name="area_type" value="BR" {{ (isset($detail_edit_sysuser->Area_type) && ($detail_edit_sysuser->Area_type == "BR")) ? "checked" : ''  }}>
                                                Branch
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class ="row" class="branch_assignment" id="branch_assignment">
                                    
                                </div>
                                <div class="form-group ">
                                <?php 
                                    $grp_idss = isset($group_ids) ? implode(",", $group_ids) : "";
                                ?>
                                <input type="hidden" id="slctd_grp_ids" value="{{ $grp_idss }}">
                                    <label for="user_nam" class="col-md-12 label_remittance" style="display:none">REMITTANCE GROUP</label>
                                    <div class="col-md-12 user_temp grp_append">
                                    </div>
                                </div>
                               
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <a type="button" class="btn btn-default" href="{{ URL('list_user') }}">
                                        Back
                                        </a>
                                    </div>
                                    <div class="col-md-6">
                                        <button type="submit" class="btn btn-primary pull-right save_button">
                                        {{isset($detail_edit_sysuser->UserID) ? "Save " : "Create " }}
                                        </button>
                                    </div>
                                </div>
                                <div id ="append_areatype"></div>
                                <div id ="appendall_group"></div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ URL('/js/add-user.js') }}"></script>

@endsection


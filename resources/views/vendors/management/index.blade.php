@extends('layouts.app')
@section('header-scripts')
    <style>
        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }
        .dataTables_scroll
        {
            overflow-x: auto;
            overflow-y: auto;
        }

        th.dt-center, td.dt-center { text-align: center; }

        .panel-body {
            padding: 15px !important;
        }

        a.disabled {
            pointer-events: none;
            cursor: default;
            color: transparent;
        }
        .modal {
            z-index: 10001 !important;;
        }

        #example_ddl > select {
            margin: 2px 0 2px 0;
            width: 176px;
            height: 30px;
        }

        input[type="checkbox"]{
            height: 18px;
            width: 18px;
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
                <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                    <ul id="sidebar_menu" class="sidebar-nav">
                        <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu<span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
                    </ul>
                    <div class="sidebar-nav" id="sidebar">
                        <div id="treeview_json"></div>
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
                        <h3 class="text-center">Vendor Management</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="#" data-toggle="modal" data-target="#addNewAccount" class="pull-right {{--@if(!\Auth::user()->checkAccessById(23, "A")) disabled @endif--}}" >Add Account</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Branch</th>
                                            <th>Account Number</th>
                                            <th>Description</th>
                                            <th>Cycle(days)</th>
                                            <th>Offset</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($vendors as $vendormgm)
                                            <tr>
                                                <td>{{ $vendormgm->VendorName }}</td>
                                                <td>{{ $vendormgm->acct_num }}</td>
                                                <td>{{ $vendormgm->description }}</td>
                                                <td>{{ $vendormgm->days_offset }}</td>
                                                <td>{{ $vendormgm->firstday_offset }}</td>
                                                <td>{{ $vendormgm->active }}</td>
                                                <td>
                                                    <a href="#" name="edit" class="btn btn-primary btn-sm edit  @if(!\Auth::user()->checkAccessById(29, "E")) disabled @endif">
                                                        <i class="glyphicon glyphicon-pencil"></i><span style="display: none;"></span>
                                                    </a>
                                                    <a href="#" name="delete" class="btn btn-danger btn-sm delete @if(!\Auth::user()->checkAccessById(29, "D")) disabled @endif">
                                                        <i class="glyphicon glyphicon-trash"></i><span style="display: none;">{{ $vendormgm->acct_id }}</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal add new account -->
    <div id="addNewAccount" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add Vendor Account</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/vendor-management') }}" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                    <label class="col-md-3 control-label" for="branchName">Branch:</label>
                                    <div class="col-md-7">
                                        <select name="branchName" class="form-control input-md branchName" id="">
                                            <option value="">Select Branch:</option>
                                             @foreach($branches as $branch)
                                                <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12 pull-left" style="margin-left: -80px;">
                                    <input type="checkbox" name="mainStatus" class="pull-left mainStatus" name="" id="">
                                    <label for="mainStatus" style="margin-top: 2px; margin-left: 1px">Main</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="vendorAccountNumber">Account number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="vendorAccountNumber" name="vendorAccountNumber" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="description">Description:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="description" name="description" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="cycleDays">Cycle(days):</label>
                            <div class="col-md-3 col-xs-10">
                                <input id="cycleDays" name="cycleDays" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="offsetDays">Offset:</label>
                            <div class="col-md-3 col-xs-10">
                                <input id="offsetDays" name="offsetDays" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="activeAccount">Active:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="" name="activeAccount" type="checkbox" class="input-md">
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
                                <input type="hidden" name="suppId" value="{{ $vendors[0]->supp_id }}">
                                <button type="submit" class="btn btn-success pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for adding new account -->


    <!-- Modal edit account -->
    <div id="editAccount" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Vendor Account</h5>
                </div>
                <form class="form-horizontal" action="" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-10 col-xs-12 bankCodeRw" style="margin-left: 15px">
                                    <label class="col-md-3 control-label" for="editBranchName">Branch:</label>
                                    <div class="col-md-7">
                                        <select name="editBranchName" class="form-control input-md editBranchName" id="">
                                            <option value="">Select Branch:</option>
                                            @foreach($branches as $branch)
                                                <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-xs-12 pull-left" style="margin-left: -80px;">
                                    <input type="checkbox" name="editMainStatus" class="pull-left editMainStatus" name="" id="">
                                    <label for="editMainStatus" style="margin-top: 2px; margin-left: 1px">Main</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="editVendorAccountNumber">Account number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="editVendorAccountNumber" name="editVendorAccountNumber" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="editDescription">Description:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="editDescription" name="editDescription" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="editCycleDays">Cycle(days):</label>
                            <div class="col-md-3 col-xs-10">
                                <input id="editCycleDays" name="editCycleDays" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="offsetDays">Offset:</label>
                            <div class="col-md-3 col-xs-10">
                                <input id="editOffsetDays" name="editOffsetDays" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group acctNumRw">
                            <label class="col-md-3 col-xs-12 control-label" for="editActiveAccount">Active:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="" name="editActiveAccount" type="checkbox" class="input-md">
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
                                {{ method_field('PUT') }}
                                <input type="hidden" name="suppId" value="{{ $vendors[0]->supp_id }}">
                                <button type="submit" class="btn btn-success pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for editing account -->

    <!-- Modal delete item from inventory -->
    <div class="modal fade" id="confirm-delete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <form action="" method="POST" >
                    <div class="modal-body">
                        <p class="text-center">You are about to delete one track, this procedure is irreversible.</p>
                        <p class="text-center">Do you want to proceed deleting <span style="font-weight: bold" class="brandToDelete"></span>-
                            <span style="font-weight:bold" class="descriptionOfBrand"></span> ?</p>
                        <p class="debug-url"></p>
                    </div>

                    <div class="modal-footer">
                        <input style="display: none" class="serviceId" >
                        {!! csrf_field() !!}
                        {{ method_field('Delete') }}
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-ok" class="deleteItem">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end Modal -->

@endsection

@section('footer-scripts')
    <script>
        (function($){
            $('#myTable').DataTable({
                initComplete: function () {
                    this.api().columns(5).every( function () {
                        var column = this;
                        var select = $('<select><option value="">All</option></select>')
                            .appendTo( '#example_ddl' )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        select.append( '<option value="1">Active</option>' )
                        select.append( '<option value="0">Inactive</option>' )

                    } );
                },
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row'<'col-sm-12'<'#example_ddl.pull-left'>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    { "width": "5%", "targets": 0},
                    { "orderable": false, "width": "9%", "targets": 5 },
                    {"className": "dt-center", "targets": 5}
                ]
            });
            $('.dataTable').wrap('<div class="dataTables_scroll" />');

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(1)').text();
                var description  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .brandToDelete').text(itemCode);
                $('#confirm-delete .descriptionOfBrand').text(description);
                $('#confirm-delete form').attr('action', '/OneBusiness/vendor-management/'+id);
                $('#confirm-delete').modal("show");
            });

            $(document).on('click', '.mainStatus', function () {
                if($('.mainStatus').is(':checked')){
                    $('.branchName').attr('disabled', true);
                }else{
                    $('.branchName').attr('disabled', false);
                }
            });

            $(document).on('click', '.editMainStatus', function () {
                if($('.editMainStatus').is(':checked')){
                    $('.editBranchName').attr('disabled', true);
                }else{
                    $('.editBranchName').attr('disabled', false);
                }
            });

            $(document).on('click', '.edit', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();

                $.ajax({
                    type: "POST",
                    url: "/vendor-management/get-account-for-vendor",
                    data: { id : id },
                    success: function (data) {
                        if(data.nx_branch == -1){
                            $('.editMainStatus').attr("checked", true);
                            $('.editBranchName').attr("disabled", true);
                        }else{
                            $('.editBranchName').val(data.nx_branch);
                        }

                        $('#editVendorAccountNumber').val(data.acct_num);
                        $('input[name="editDescription"]').val(data.description);
                        $('input[name="editCycleDays"]').val(data.days_offset);
                        $('input[name="editOffsetDays"]').val(data.firstday_offset);

                        if(data.active){
                            $('input[name="editActiveAccount').attr("checked", true);
                        }
                        $('#editAccount form').attr('action', '/OneBusiness/vendor-management/'+id);
                        $('#editAccount').modal("show");
                    }
                })
            })

        })(jQuery);
    </script>
@endsection
@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">ROLE TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Master</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Role</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Role Table</h5>
                                    <span></span>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                            <li><i class="fa fa-window-maximize full-card"></i></li>
                                            <li><i class="fa fa-minus minimize-card"></i></li>
                                            <li><i class="fa fa-refresh reload-card"></i></li>
                                            <li><i class="fa fa-trash close-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    @if(isAccess('create', $MenuID))
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#modal_default" onClick="return_value_edit_role(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                    @endif
                                    <div class="dt-responsive table-responsive">
                                        <table id="searchTable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Divisi</th>
                                                    <th width="11%">Action</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Role Input</h5>
                                    <span></span>
                                    <div class="card-header-right">
                                        <ul class="list-unstyled card-option">
                                            <li><i class="fa fa fa-wrench open-card-option"></i></li>
                                            <li><i class="fa fa-window-maximize full-card"></i></li>
                                            <li><i class="fa fa-minus minimize-card"></i></li>
                                            <li><i class="fa fa-refresh reload-card"></i></li>
                                            <li><i class="fa fa-trash close-card"></i></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <form action="/master/role-superuser/update" method="post" enctype="multipart/form-data" id="roleForm">
                                        @csrf
                                            <input type="hidden" name="role_id_update_role" id="role_id_update_role">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Role Name</label>
                                                <div class="col-sm-9">
                                                    <input type="text" name="role_name_update_role" id="role_name_update_role" class="form-control">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="table-responsive">
                                                    <table class="table table-sm table-styling">
                                                        <thead>
                                                            <tr>
                                                                <th width="75%">Menu</th>
                                                                <th width="5%">Access</th>
                                                                <th width="5%">Read</th>
                                                                <th width="5%">Create</th>
                                                                <th width="5%">Update</th>
                                                                <th width="5%">Delete</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php ($i = 0)
                                                            @foreach($menuList as $ls)
                                                            <tr>
                                                                @if($ls->parent == 1)
                                                                    <td colspan="6"><b>{{ $ls->menu_name }}</b></td>
                                                                @else
                                                                    <td> @if($ls->parent_menu == "")
                                                                            {{ $ls->menu_name }}
                                                                        @else
                                                                            &emsp;&bull; {{ $ls->menu_name }}
                                                                            @endif
                                                                        <input type="hidden" name="menu_id[{{ $i }}]" value="{{ $ls->menu_id }}">

                                                                    </td>
                                                                    <td><input type="checkbox" name="access[{{ $i }}]" id="access_{{ $ls->menu_id }}" value="1" class="js-small" onchange="accessAble('{{ $ls->menu_id }}')" /></td>
                                                                    <td><input type="checkbox" name="read[{{ $i }}]" id="read_{{ $ls->menu_id }}" value="1" class="js-small" /></td>
                                                                    <td><input type="checkbox" name="create[{{ $i }}]" id="create_{{ $ls->menu_id }}" value="1" class="js-small" /></td>
                                                                    <td><input type="checkbox" name="update[{{ $i }}]" id="update_{{ $ls->menu_id }}" value="1" class="js-small" /></td>
                                                                    <td><input type="checkbox" name="delete[{{ $i }}]" id="delete_{{ $ls->menu_id }}" value="1" class="js-small" /></td>
                                                                    @php ($i++)
                                                                @endif
                                                            </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-3"></label>
                                                <div class="col-sm-9" style="text-align:right;">
                                                    <button type="button" class="btn btn-default waves-effect" onclick="cancelRoleForm()">Cancel</button>
                                                    @if(isAccess('create', $MenuID) || isAccess('update', $MenuID))
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#roleForm')">Save</button>
                                                    @endif
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
    </div>
</div>
<div id="styleSelector"></div>
<div class="modal fade" id="modal_default"  role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="modal_default" >Add Role</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/master/role-superuser/add" method="post" enctype="multipart/form-data" id="addRoleForm">
                @csrf
                <input type="hidden" name="role_id_add" id="role_id_add">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Role Name <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="role_name" id="role_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Store <span class="text-danger"> *</span></label>
                        <div class="col-sm-8">
                            <select name="store_id_add" id="store_id_add" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($storeList as $ls)
                                    <option value="{{ $ls->store_id }}">{{ $ls->store_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#addRoleForm')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadSwitcher();
        loadData();
    } );

    function loadSwitcher(){
        var elem = Array.prototype.slice.call(document.querySelectorAll('.js-small'));
        elem.forEach(function(html) {
            var switchery = new Switchery(html, { color: '#4099ff', jackColor: '#fff', size: 'small' });
        });
    }

    function loadData(){

        $('#searchTable').DataTable().destroy();
        $('#searchTable').DataTable({
            "bPaginate": true,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": false,
            "bAutoWidth": false,
            "ajax": '/master/role-superuser/load',
            "aoColumns": [
                { "data": "role_name" },
                {
                    "mData": "role_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#modal_default' onClick='return_value_edit_role(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-table'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete role "+ row.role_name +" ??' data-url='/master/role-superuser/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }
            ]
        });
        $('#searchTable_wrapper .row').first().find('div:first').remove();
        $('.float-label').text("Add Divisi")
    }

    function accessAble(id){
        if($("#access_"+id).is(":checked")){
            ($("#read_"+id).is(":checked") == false)?$("#read_"+id).click():"";
            ($("#create_"+id).is(":checked") == false)?$("#create_"+id).click():"";
            ($("#update_"+id).is(":checked") == false)?$("#update_"+id).click():"";
            ($("#delete_"+id).is(":checked") == false)?$("#delete_"+id).click():"";
        } else {
            ($("#read_"+id).is(":checked") == true)?$("#read_"+id).click():"";
            ($("#create_"+id).is(":checked") == true)?$("#create_"+id).click():"";
            ($("#update_"+id).is(":checked") == true)?$("#update_"+id).click():"";
            ($("#delete_"+id).is(":checked") == true)?$("#delete_"+id).click():"";
        }
    }
    function return_value_edit_role(e, data){
        console.log(data);
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#addRoleForm").trigger("reset");
            $("#addRoleForm").attr("action", "/master/add-role-superuser/update");
            $("#role_id_add").val(data.role_id);
            $("#role_name").val(data.role_name);
            $("#store_id_add").val(data.store_id);
            $('#store_id_add').trigger('change');
        } else {
            $("#addRoleForm").trigger("reset");
            $("#addRoleForm").attr("action", "/master/add-role-superuser/add");
        }
    }
    function return_value(e, data){
        console.log(data);
        $("#roleForm").trigger("reset");
        $("#roleForm").attr("action", "/master/role-superuser/update");
        $("#role_id_update_role").val(data.role_id);
        $("#role_name_update_role").val(data.role_name);
        data.menu_role.forEach(function(menu) {
            if(menu.access == 1) $("#access_"+menu.menu_id).prop("checked", true);
            if(menu.read == 1) $("#read_"+menu.menu_id).prop("checked", true);
            if(menu.create == 1) $("#create_"+menu.menu_id).prop("checked", true);
            if(menu.update == 1) $("#update_"+menu.menu_id).prop("checked", true);
            if(menu.delete == 1) $("#delete_"+menu.menu_id).prop("checked", true);
        })
        $(".switchery-small").remove();
        loadSwitcher();
    }

    function saveInit(form){
        saveData(form, function() {
            loadData();
            $("#roleForm").attr("action", "/master/role-superuser/update");
            $(".switchery-small").remove();
            loadSwitcher();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }

    function cancelRoleForm(){
        $('#roleForm').trigger('reset');
        resetCheckbox();
    }

    function resetCheckbox(){
        var ls = {!! json_encode($menuList->toArray()) !!};
        ls.forEach(function(menu) {
                $("#access_"+menu.menu_id).prop("checked", false);
                $("#read_"+menu.menu_id).prop("checked", false);
                $("#create_"+menu.menu_id).prop("checked", false);
                $("#update_"+menu.menu_id).prop("checked", false);
                $("#delete_"+menu.menu_id).prop("checked", false);
            })
        $(".switchery-small").remove();
        loadSwitcher();
    }

</script>
@endsection

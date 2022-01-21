@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">USER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Users</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Page-header end -->

    {{-- @php $MenuID = "01" @endphp --}}
    <div class="pcoded-inner-content">
        <div class="main-body">
            <div class="page-wrapper">
                <div class="page-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Users</h5>
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
                                        <button class="btn btn-info btn-md waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" btn="add" onClick="return_value(this, '')">
                                            <i class="icofont icofont-plus-circle"></i> <br/>Tambah
                                        </button>
                                    @endif
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No.</th>
                                                    <th>Name</th>
                                                    {{-- <th>Divisi</th>
                                                    <th>Location</th> --}}
                                                    <th width="11%">Action</th>
                                                </tr>
                                            </thead>

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
</div>
<div id="styleSelector"></div>
<div class="modal fade" id="large-Modal" role="dialog">    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >User</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="userForm">
                @csrf
                <input type="hidden" name="user_id" id="user_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Divisi <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="divisi" id="divisi" class="js-example-basic-single col-sm-12" required>
                                @foreach($divisiList as $ls)
                                    <option value="{{ $ls->divisi_id }}">{{ $ls->divisi_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Location <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="location" id="location" class="js-example-basic-single col-sm-12" required>
                                @foreach($locList as $ls)
                                    <option value="{{ $ls->location_id }}">{{ $ls->location_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email <span class="text-danger"> *</span></label>
                        <div class="col-sm-4">
                            <input type="text" name="email" id="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Phone</label>
                        <div class="col-sm-10">
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Username <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="username" id="username" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Password <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Role <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="role" id="role" class="js-example-basic-single col-sm-12" required>
                                @foreach($roleList as $ls)
                                    <option value="{{ $ls->role_id }}">{{ $ls->role_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#userForm')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
    } );

    function loadData(){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "ajax": '/users/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "employee.employee_name" },
                // { "data": "employee.divisi.divisi_name" },
                // { "data": "employee.location.location_name" },
                {
                    "mData": "user_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete User "+ row.employee.employee_name +" ??' data-url='/users/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }
            ]
        });
    }


    function return_value(e, data){
        var btn = $(e).attr("btn");

        if (btn == "edit"){
            $("#userForm").attr("action", "/users/update");
            $("#defaultModalLabel").text("Edit User")
            $("#user_id").val(data.user_id);
            $("#name").val(data.employee.employee_name);
            $("#phone").val(data.employee.phone);
            $("#email").val(data.employee.email);
            $("#username").val(data.username);
            $("#divisi").val(data.employee.divisi_id);
            $('#divisi').trigger('change');
            $("#location").val(data.employee.location_id);
            $('#location').trigger('change');
            $("#role").val(data.role);
            $('#role').trigger('change');


        } else {
            $("#userForm").trigger("reset");
            $("#userForm").attr("action", "/users/add");
            $("#defaultModalLabel").text("Add User")
        }

    }

    function saveInit(form){
        saveData(form, function() {
            loadData();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }


</script>
@endsection

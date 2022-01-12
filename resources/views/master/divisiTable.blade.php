@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"> DIVISI & PART TYPE TABLE</h5>
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
                        <li class="breadcrumb-item"><a href="#!">Divisi & Part Type</a>
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
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Divisi & Part Type</h5>
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
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="card-block">
                                            @if(isAccess('create', $MenuID))
                                                <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#modal_default" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                            @endif
                                            <div class="form-group row">
                                                <label class="col-sm-4 col-form-label">Part Type <span class="text-danger"> </span></label>
                                                <div class="col-sm-8">
                                                    <select name="part_type_id_filter" id="part_type_id_filter" onchange="loadData()" class="js-example-basic-single col-sm-12" required>
                                                        <option value="all">All</option>
                                                        @foreach($partTypeList as $ls)
                                                            <option value="{{ $ls->part_type_id }}">{{ $ls->code.'. '.$ls->part_type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="dt-responsive table-responsive">
                                                <table id="searchTable" class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th width="11%">Action</th>
                                                            <th>Code</th>
                                                            <th>Divisi</th>
                                                            <th>Part Type</th>
                                                            <th>Status</th>

                                                        </tr>
                                                    </thead>

                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card-block">
                                            <div id="table-data">
                                                @if(isAccess('create', $MenuID))
                                                    <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" onClick="return_value_type(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                                @endif
                                            </div>
                                            <div id="input-data">
                                                <div class="card-block row">
                                                    <form action="" method="post" enctype="multipart/form-data" id="typeForm">
                                                        @csrf
                                                        <input type="hidden" name="part_type_id" id="part_type_id">
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Type Code <span class="text-danger"> *</span> </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="code" id="code" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label class="col-sm-4 col-form-label">Type Name <span class="text-danger"> *</span> </label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="part_type" id="part_type" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <button type="button" class="btn btn-default waves-effect" id="close-input" onClick="closeInput()">Close</button>
                                                            <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInitType('#typeForm', '#close-input')">Save</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>

                                            <div class="dt-responsive table-responsive">
                                                <table id="searchTableType" class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th>Code</th>
                                                            <th>Part Type</th>
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
    </div>
</div>
<div id="styleSelector"></div>

<!--MODAL CUSTOMER STATUS-->
<div class="modal fade" id="modal_default"  role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="modal_default" >Add Divisi</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="divisiForm">
                @csrf
                <input type="hidden" name="divisi_id" id="divisi_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Divisi Code <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="divisi_code" id="divisi_code" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Divisi Name <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="divisi_name" id="divisi_name" class="form-control" required>
                            <span id="divisi_name_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Divisi Produksi</label>
                        <div class="col-sm-8">
                            <div class="checkbox-color checkbox-primary">
                                <input name="is_production"  id="is_production" type="hidden">
                                <input name="is_productionChk" id="is_productionChk" onchange="productionChange()" type="checkbox" checked="">
                                <label for="is_productionChk">
                                    divisi produksi
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row" id="part_type_option">
                        <label class="col-sm-4 col-form-label">Part Type <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <select name="part_type_id_divisi" id="part_type_id_divisi" class="js-example-basic-single">
                                <option value="" disabled selected>--Select--</option>
                                @foreach($partTypeList as $ls)
                                    <option value="{{ $ls->part_type_id }}">{{ $ls->code.'. '.$ls->part_type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#divisiForm', '#closeModal')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!---END MODAL CUSTOMER STATUS--->
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        $("#table-data").show();
        $("#input-data").hide();
        loadData();
        loadDataPartType();
    } );

    function loadData(){
        var partType = $('#part_type_id_filter').val();
        $('#searchTable').DataTable().destroy();
        $('#searchTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/divisi/load/'+partType,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "divisi_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#modal_default' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete divisi "+ row.divisi_name +" ??' data-url='/master/divisi/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "divisi_code" },
                { "data": "divisi_name" },
                { "data": "divisi_name" },
                {  "mRender": function (data, type, row, num) {
                        return row.is_production === 1 ? "<span class='btn-danger btn-sm' > -produksi- </span>" : "<span class='btn-primary btn-sm' > -backoffice- </span>";
                    }
                },
            ]
        });
    }

    function loadDataPartType(){
        $('#searchTableType').DataTable().destroy();
        $('#searchTableType').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/part-type/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "code" },
                { "data": "part_type" },
                {
                    "mData": "part_type_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value_type(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete part type "+ row.part_type +" ??' data-url='/master/part-type/delete/" + data + "' onClick='deleteInitType(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
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
            $("#divisiForm").attr("action", "/master/divisi/update");
            $("#defaultModalLabel").text("Edit Divisi")
            $("#divisi_id").val(data.divisi_id);
            $("#divisi_code").val(data.divisi_code);
            $("#divisi_name").val(data.divisi_name);
            $("#part_type_id_divisi").val(data.part_type_id);
            $("#is_productionChk").prop('checked', data.is_production === 1 ? true : false);
            productionChange();

        } else {
            $("#divisiForm").trigger("reset");
            $("#is_productionChk").prop('checked', false);
            $("#divisiForm").attr("action", "/master/divisi/add");
            $("#defaultModalLabel").text("Add Divisi")
            productionChange();
        }

        $("#part_type_id_divisi").trigger('change');
    }

    function return_value_type(e, data){
        $("#table-data").hide();
        $("#input-data").show();

        var btn = $(e).attr("btn");
        if (btn == "edit"){

            $("#typeForm").attr("action", "/master/part-type/update");
            $("#part_type_id").val(data.part_type_id);
            $("#code").val(data.code);
            $("#part_type").val(data.part_type);

        } else {
            $("#typeForm").trigger("reset");
            $("#typeForm").attr("action", "/master/part-type/add");
        }

    }

    function closeInput(){
        $("#table-data").show();
        $("#input-data").hide();
    }

    function saveInit(form, modalId){
        if(validateForm()){
            $("#is_production").val($("#is_productionChk").prop("checked") ? 1 : 0);
            saveDataModal(form, modalId, function() {
                loadData();
            });
        }
    }
    function saveInitType(form, modalId){
        saveDataModal(form, modalId, function() {
            loadDataPartType();
        });
    }

    function validateForm(){
        var is_error = 0;
        is_error += validateRequired('#divisi_name', '#divisi_name_error');

        return is_error === 0 ? true : false;
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }

    function deleteInitType(e){
        deleteConfirm(e, function() {
            loadDataPartType();
        });
    }

    function productionChange(){
        if($("#is_productionChk").prop("checked")){
            $("#part_type_option").show();
        }else{
            $("#part_type_option").hide();
        }
    }

</script>
@endsection

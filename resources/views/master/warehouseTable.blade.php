@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">WAREHOUSE TABLE</h5>
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
                        <li class="breadcrumb-item"><a href="#!">WAREHOUSE</a>
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
                                    <h5>Warehouse</h5>
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
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                    @endif
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="3%">No.</th>
                                                    <th>Warehouse</th>
                                                    <th>Desc</th>
                                                    <th>Plant</th>
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
<div class="modal fade" id="large-Modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Warehouse</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="warehouseForm">
                @csrf
                <input type="hidden" name="warehouse_id" id="warehouse_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Warehouse Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="warehouse_name" id="warehouse_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Description <span class="text-danger"> *</span></label>
                        <div class="col-sm-9">
                            <input type="text" name="description" id="description" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Plant <span class="text-danger"> *</span></label>
                        <div class="col-sm-9">
                            <select name="plant_id" id="plant_id" class="js-example-basic-single col-sm-12" required>
                                <option value="" disabled selected>--Select--</option>
                                @foreach($plantList as $ls)
                                    <option value="{{ $ls->plant_id }}">{{ $ls->plant_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#warehouseForm')">Save</button>
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
        $('#newTables').DataTable({
            "ajax": '/master/warehouse/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "warehouse_name" },
                { "data": "description" },
                { "data": "plant.plant_name" },
                {
                    "mData": "warehouse_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete warehouse "+ row.warehouse_name +" ??' data-url='/master/warehouse/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
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
            $("#warehouseForm").attr("action", "/master/warehouse/update");
            $("#defaultModalLabel").text("Edit Warehouse")
            $("#warehouse_id").val(data.warehouse_id);
            $("#warehouse_name").val(data.warehouse_name);
            $("#description").val(data.description);
            $("#plant_id").val(data.plant_id);
            $("#plant_id").trigger("change");
        } else {
            $("#warehouseForm").trigger("reset");
            $("#plant_id").trigger("change");
            $("#warehouseForm").attr("action", "/master/warehouse/add");
            $("#defaultModalLabel").text("Add Warehouse")
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

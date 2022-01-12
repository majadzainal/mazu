@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">UNIT TABLE</h5>
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
                        <li class="breadcrumb-item"><a href="#!">Unit</a>
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
                                    <h5>Unit</h5>
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
                                        <form action="/master/unit/add" enctype="multipart/form-data" id="unitForm" class="form-material right-icon-control">
                                            @csrf
                                            <input type="hidden" id="unit_id" name="unit_id">
                                            <div class="form-group form-primary">
                                                <input type="text" id="unit_name" name="unit_name" class="form-control" required="">
                                                <span class="form-bar"></span>
                                                <label class="float-label"><b>Add Unit . . </b></label>
                                            </div>
                                            <div class="form-icon ">
                                                <button type="button" class="btn btn-primary btn-round waves-effect waves-light" onClick="saveInit('#unitForm')"><i class="icofont icofont-save"></i>&nbsp;Save</button>
                                            </div>
                                        </form>
                                    @endif
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No.</th>
                                                    <th>Unit</th>
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
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
    } );

    $('#unitForm').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
            e.preventDefault();
            return false;
        }
    });

    function loadData(){
        $('#newTables').DataTable().destroy();
        $('#newTables').DataTable({
            "ajax": '/master/unit/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "unit_name" },
                {
                    "mData": "unit_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete unit "+ row.unit_name +" ??' data-url='/master/unit/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }
            ]
        });

        $('.float-label').text("Add Unit")
    }


    function return_value(e, data){
        $('.float-label').text("Edit Unit")
        $("#unit_id").val(data.unit_id);
        $("#unit_name").val(data.unit_name);
        $("#unit_name").focus();
        document.documentElement.scrollTop = 0;
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

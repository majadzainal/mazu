@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">MACHINE TABLE</h5>
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
                        <li class="breadcrumb-item"><a href="#!">Machine</a>
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
                                    <h5>Machine Table</h5>
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
                                <div class="tab-content card-block">
                                    @if(isAccess('create', $MenuID))
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                    @endif
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="3%">No.</th>
                                                    <th>Divisi</th>
                                                    <th>Code</th>
                                                    <th>Brand</th>
                                                    <th>Spec</th>
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
<!---MODAL process MACHINE-->
<div class="modal fade" id="large-Modal"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Plant</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="processMachineForm">
                @csrf
                <input type="hidden" name="pmachine_id" id="pmachine_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Divisi <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <select name="divisi_id" id="divisi_id" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($divisiList as $ls)
                                    <option value="{{ $ls->divisi_id }}">{{ $ls->divisi_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Plant <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <select name="plant_id" id="plant_id" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($planList as $ls)
                                    <option value="{{ $ls->plant_id }}">{{ $ls->plant_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Code <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="code" id="code" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Brand</label>
                        <div class="col-sm-10">
                            <input type="text" name="brand" id="brand" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Line</label>
                        <div class="col-sm-10">
                            <input type="text" name="line" id="line" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Spec <span class="text-danger"> *</span></label>
                        <div class="col-sm-3">
                            <input type="number" name="spec_volume" id="spec_volume" class="form-control" required>
                        </div>
                        <div class="col-sm-5">
                            <select name="spec_unit" id="spec_unit" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($unitList as $ls)
                                    <option value="{{ $ls->unit_id }}">{{ $ls->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Cycle Time</label>
                        <div class="col-sm-10">
                            <input type="text" name="cycle_time" id="cycle_time" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">UOS</label>
                        <div class="col-sm-10">
                            <input type="text" name="uos" id="uos" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#processMachineForm', '#closeModal')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!---END MODAL process MACHINE-->
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
        $("#inputprocessPrice").hide();
    } );


    function loadData(){
        $('#newTables').DataTable().destroy();
        $('#newTables').DataTable({
            "ajax": '/master/process-machine/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "divisi.divisi_name" },
                { "data": "code" },
                { "data": "brand" },
                {  "mRender": function (data, type, row, num) {
                        return row.spec_volume+" "+row.unit.unit_name;
                    }
                },
                {
                    "mData": "pmachine_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete process machine "+ row.brand +" ??' data-url='/master/process-machine/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
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
            $("#processMachineForm").attr("action", "/master/process-machine/update");
            $("#defaultModalLabel").text("Edit process Machine")
            $("#pmachine_id").val(data.pmachine_id);
            $("#divisi_id").val(data.divisi_id);
            $('#divisi_id').trigger('change');
            $("#plant_id").val(data.plant_id);
            $('#plant_id').trigger('change');
            $("#code").val(data.code);
            $("#brand").val(data.brand);
            $("#line").val(data.line);
            $("#spec_volume").val(data.spec_volume);
            $("#spec_unit").val(data.spec_unit);
            $('#spec_unit').trigger('change');
            $("#cycle_time").val(data.cycle_time);
            $("#uos").val(data.uos);

        } else {
            $("#processMachineForm").trigger("reset");
            $("#processMachineForm").attr("action", "/master/process-machine/add");
            $("#defaultModalLabel").text("Add process Machine")
            loadSelect2();
        }

    }

    function saveInit(form, closeModalId){
        saveDataModal(form, closeModalId, function() {
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

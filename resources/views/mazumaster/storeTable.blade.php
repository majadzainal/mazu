@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">STORE TABLE</h5>
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
                        <li class="breadcrumb-item"><a href="#!">STORE</a>
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
                                    <h5>STORE</h5>
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
                                    <div class="col-md-12">
                                        <div class="card-block">
                                            @if(isAccess('create', $MenuID))
                                                <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#modal_default" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                            @endif
                                            <div class="dt-responsive table-responsive">
                                                <table id="searchTable" class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th width="11%">Action</th>
                                                            <th>Store Name</th>
                                                            <th>Address</th>
                                                            <th>No Telephone</th>
                                                            <th>Fax</th>
                                                            <th>Email</th>
                                                            <th>NPWP</th>
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
                <h4 class="modal-title" id="modal_default" >Add Store</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/master/store/add" method="post" enctype="multipart/form-data" id="storeForm">
                @csrf
                <input type="hidden" name="store_id" id="store_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Store Name <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="store_name" id="store_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Description <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="description" id="description" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Telephone</label>
                        <div class="col-sm-8">
                            <input type="number" name="store_telephone" id="store_telephone" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">No. Fax</label>
                        <div class="col-sm-8">
                            <input type="number" name="store_fax" id="store_fax" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Email <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="store_email" id="store_email" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Address <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="store_address" id="store_address" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">NPWP <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="npwp" id="npwp" class="form-control" required>
                        </div>
                    </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#storeForm', '#closeModal')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!---END MODAL CUSTOMER STATUS--->
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
    } );

    function loadData(){
        $('#searchTable').DataTable().destroy();
        $('#searchTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/store/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "store_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#modal_default' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete store "+ row.store_name +" ??' data-url='/master/store/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "store_name" },
                { "data": "store_address" },
                { "data": "store_telephone" },
                { "data": "store_fax" },
                { "data": "store_email" },
                { "data": "npwp" },
            ]
        });
    }

    function return_value(e, data){

        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#storeForm").attr("action", "/master/store/update");
            $("#defaultModalLabel").text("Edit Store")
            $("#store_id").val(data.store_id);
            $("#store_name").val(data.store_name);
            $("#description").val(data.description);
            $("#store_telephone").val(data.store_telephone);
            $("#store_fax").val(data.store_fax);
            $("#store_email").val(data.store_email);
            $("#store_address").val(data.store_address);
            $("#npwp").val(data.npwp);

        } else {
            $("#storeForm").trigger("reset");
            $("#storeForm").attr("action", "/master/store/add");
            $("#defaultModalLabel").text("Add Store")
        }
    }


    function saveInit(form, modalId){
        saveDataModal(form, modalId, function() {
            loadData();
        });
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
</script>
@endsection

@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">PAID TYPE TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Master</a></li>
                        <li class="breadcrumb-item"><a href="#!">Paid Type</a></li>
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
                                    <h5>Paid Type</h5>
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
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" btn="add" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add</button>
                                    @endif
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th width="11%">Action</th>
                                                    <th>Paid Type</th>
                                                    <th>Account Name</th>
                                                    <th>Account Number</th>
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
<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Paid Type</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="outletForm">
                @csrf
                <input type="hidden" name="paid_type_id" id="paid_type_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Paid Type <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="type_name" id="type_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Account Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="account_name" id="account_name" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Account Number <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="account_number" id="account_number" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Piutang</label>
                        <div class="col-sm-10">
                            <div class="checkbox-color checkbox-primary">
                                <input name="is_credit"  id="is_credit" type="hidden" required>
                                <input name="is_creditCHK" id="is_creditCHK" type="checkbox">
                                <label for="is_creditCHK">
                                    Piutang
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tampilkan Di Invoice</label>
                        <div class="col-sm-10">
                            <div class="checkbox-color checkbox-primary">
                                <input name="is_invoice_bank"  id="is_invoice_bank" type="hidden" required>
                                <input name="is_invoice_bankCHK" id="is_invoice_bankCHK" type="checkbox">
                                <label for="is_invoice_bankCHK">
                                    Tampilkan
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#outletForm', '#closeModal')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
        loadSelect2();

    } );

    function loadSelect2(){
        $(".js-example-placeholder").select2({
            placeholder: "--select--"
        });
    }

    function loadData(){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/master/paid-type/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "paid_type_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete paid type "+ row.type_name +" ??' data-url='/master/paid-type/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "type_name" },
                { "data": "account_name" },
                { "data": "account_number" },
            ]
        });
    }

    function return_value(e, data){
        var btn = $(e).attr("btn");

        if (btn == "edit"){
            $("#outletForm").attr("action", "/master/paid-type/update");
            $("#defaultModalLabel").text("Edit Paid Type")
            $("#paid_type_id").val(data.paid_type_id);
            $("#type_name").val(data.type_name);
            $("#account_name").val(data.account_name);
            $("#account_number").val(data.account_number);
            $("#is_creditCHK").prop('checked', data.is_credit === 1 ? true : false);
            $("#is_invoice_bankCHK").prop('checked', data.is_invoice_bank === 1 ? true : false);
            loadSelect2()

        } else {
            $("#outletForm").trigger("reset");
            $("#outletForm").attr("action", "/master/paid-type/add");
            $("#defaultModalLabel").text("Add Paid Type");
            loadSelect2();
        }

    }

    function saveInit(form, modalId){
        $("#is_credit").val($("#is_creditCHK").prop("checked") ? 1 : 0);
        $("#is_invoice_bank").val($("#is_invoice_bankCHK").prop("checked") ? 1 : 0);
        saveDataModal(form, modalId, function() {
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

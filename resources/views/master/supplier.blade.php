@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">SUPPLIER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Master</a></li>
                        <li class="breadcrumb-item"><a href="#!">Suppliers</a></li>
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
                                    <h5>Suppliers</h5>
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
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <button class="btn btn-primary btm-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" btn="add" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Tambah</button>
                                        </div>
                                        <div class="col-sm-10 row">
                                            <div class="col-sm-6 input-group input-group-button">
                                                <input type="file" class="form-control" name="import_excel_supplier" id="import_excel_supplier" accept=".xls, .xlsx" placeholder="Choose File.xlsx">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" onClick="readImportExcel('#import_excel_supplier')" data-toggle="modal" data-target="#large-ModalImport" type="button">Upload Excel</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <a href="/master/supplier/template-download" class="btn btn-success">Download Template</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-radio">
                                                <div class="radio radio-inline">
                                                    <label>
                                                        <input type="radio" onClick="activeClick()" name="is_active_supplier" value="1" checked="checked">
                                                        <i class="helper"></i>Active Supplier
                                                    </label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <label>
                                                        <input type="radio" onClick="activeClick()" name="is_active_supplier" value="0" >
                                                        <i class="helper"></i>Non Active Supplier
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No.</th>
                                                    <th>Supplier Code</th>
                                                    <th>Supplier Name</th>
                                                    <th>Telephone</th>
                                                    <th>Email</th>
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
<div class="modal fade" id="large-Modal"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Supplier</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="supplierForm">
                @csrf
                <input type="hidden" name="supplier_id" id="supplier_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Supplier Code</label>
                        <div class="col-sm-3">
                            <input type="text" readonly name="supplier_code" id="supplier_code" class="form-control">
                            <span id="supplier_code_error" class="text-danger error-text"></span>
                        </div>
                        <label class="col-sm-5 col-form-label">Auto generate after save</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Supplier Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-2">
                            <select name="business_entity" id="business_entity" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                    @foreach (getBusinessEntity() as $ls)
                                        <option value="{{ $ls['business_identity'] }}">{{ $ls['business_identity'] }}</option>
                                    @endforeach
                            </select>
                            {{-- <input type="text" name="business_entity" id="business_entity" placeholder="PT" class="form-control" required> --}}
                            <span id="business_entity_error" class="text-danger error-text"></span>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" name="supplier_name" id="supplier_name" class="form-control" required>
                            <span id="supplier_name_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    {{-- <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Divisi <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <select name="divisi_id" id="divisi_id" class="js-example-basic-single col-sm-12" required>
                                @foreach($divisiList as $ls)
                                    <option value="{{ $ls->divisi_id }}">{{ $ls->divisi_name }}</option>
                                @endforeach
                            </select>
                            <span id="divisi_id_error" class="text-danger error-text"></span>
                        </div>
                    </div> --}}
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Phone <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="number" name="supplier_telephone" id="supplier_telephone" class="form-control"
                            oninput="validateMaxLength(this)" maxlength="13" required>
                            <span id="supplier_telephone_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Fax <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="number" name="supplier_fax" id="supplier_fax" class="form-control"
                            oninput="validateMaxLength(this)" maxlength="13" required>
                            <span id="supplier_fax_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="email" name="supplier_email" id="supplier_email" class="form-control"
                            onchange="validateEmail('#supplier_email', '#supplier_email_error')" required>
                            <span id="supplier_email_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <textarea name="supplier_address" id="supplier_address" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tempo Bayar (Hari) <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="number" name="pay_time" id="pay_time" class="form-control"
                            oninput="validateMaxLength(this)" maxlength="3" required>
                            <span id="pay_time_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bank <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="bank" id="bank" class="form-control" required>
                            <span id="bank_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Bank Account Number <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="bank_account_number" id="bank_account_number" class="form-control"
                            oninput="validateBankAcoount(this)" required>
                            <span id="bank_account_number_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">PPN <span class="text-danger"> </span></label>
                        <div class="col-sm-6">
                            <div class="checkbox-color checkbox-primary">
                                <input name="is_ppn"  id="is_ppn" type="hidden">
                                <input name="is_ppnChk" id="is_ppnChk" type="checkbox" >
                                <label for="is_ppnChk">
                                    With PPN
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">NPWP <span class="text-danger"> </span></label>
                        <div class="col-sm-6">
                            <input type="text" name="npwp" id="npwp" class="form-control"
                            oninput="validateNPWP(this)">
                            <span id="npwp_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12"><hr></div>
                        <div class="col-sm-12"><strong>Add Contact PIC</strong></div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="pic_name" id="pic_name" class="form-control">
                            <span id="pic_name_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">PIC Divisi <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <select name="pic_type_id" id="pic_type_id" class="js-example-basic-single col-sm-12">
                                @foreach($picTypeList as $ls)
                                    <option value="{{ $ls->pic_type_id }}">{{ $ls->pic_type_name }}</option>
                                @endforeach
                            </select>
                            <span id="pic_type_id_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Phone <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="number" name="pic_telephone" id="pic_telephone" class="form-control"
                            oninput="validateMaxLength(this)" maxlength="13">
                            <span id="pic_telephone_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="pic_email" id="pic_email" class="form-control"
                            onchange="validateEmail('#pic_email', '#pic_email_error')" >
                            <span id="pic_email_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-6">
                            <input type="hidden" name="picList" id="picList" class="form-control">
                            <button type="button" class="btn btn-primary waves-effect waves-light" name="btnAddContact" id="btnAddContact" onClick="btnAddContactClick()">add contact</button>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-12">
                            <div class="dt-responsive table-responsive">
                                <table id="tableContact" class="table table-striped table-bordered nowrap">
                                    <thead>
                                        <tr>
                                            <th>Divisi ID</th>
                                            <th>Divisi</th>
                                            <th>Name</th>
                                            <th>Telephone</th>
                                            <th>Email</th>
                                            <th width="11%">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="contentTableContact">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#supplierForm')">Save</button>
                    <button type="button" class="btn btn-warning waves-effect waves-light" id="activate_supplier" onClick='activate(this)'>Activate Supplier</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--MODAL IMPORT EXCEL--->
<div class="modal fade" id="large-ModalImport"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Import Supplier</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="dt-responsive table-responsive">
                            <table id="tableImport" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Action</th>
                                        <th>Business Entity</th>
                                        <th>Supplier Name</th>
                                        <th>Telephone</th>
                                        <th>Fax</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Pay Time</th>
                                        <th>Bank</th>
                                        <th>Bank Account Number</th>
                                        <th>PPN</th>
                                        <th>NPWP</th>
                                        <th>Sales Name</th>
                                        <th>Sales Telephone</th>
                                        <th>Sales Email</th>
                                        <th>Prod. Name</th>
                                        <th>Prod. Telephone</th>
                                        <th>Prod. Email</th>
                                        <th>Delivery Name</th>
                                        <th>Delivery Telephone</th>
                                        <th>Delivery Email</th>
                                        <th>Finance Name</th>
                                        <th>Finance Telephone</th>
                                        <th>Finance Email</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyImport">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="closeModalImport" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                <form action="/master/supplier/import" method="post" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <input type="hidden" name="importList" id="importList" class="form-control">
                    <button type="button" onClick="saveImport('#importForm')" class="btn btn-primary waves-effect waves-light" >Import</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--- CLOSE MODAL IMPORT EXCEL--->
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
    });

    function activeClick(){
        loadData();
    }

    function loadData(){
        var is_active = $('input[name="is_active_supplier"]:checked').val();
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/supplier/load/'+is_active,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "supplier_code" },
                {  "mRender": function (data, type, row, num) {
                        return row.business_entity+". "+row.supplier_name;
                    }
                },
                { "data": "supplier_telephone" },
                { "data": "supplier_email" },
                {
                    "mData": "supplier_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete supplier "+ row.business_entity +". "+ row.supplier_name +" ??' data-url='/master/supplier/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }
            ]
        });
    }

    function return_value(e, data){
        $("#activate_supplier").hide();

        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#supplierForm").attr("action", "/master/supplier/update");
            $("#defaultModalLabel").text("Edit Supplier")
            $("#supplier_id").val(data.supplier_id);
            $("#supplier_code").val(data.supplier_code);
            $("#business_entity").val(data.business_entity);
            $("#business_entity").trigger('change');
            $("#supplier_name").val(data.supplier_name);
            $("#supplier_telephone").val(data.supplier_telephone);
            $("#supplier_fax").val(data.supplier_fax);
            $("#supplier_email").val(data.supplier_email);
            $("#supplier_address").val(data.supplier_address);
            $("#pay_time").val(data.pay_time);
            $("#bank").val(data.bank);
            $("#bank_account_number").val(data.bank_account_number);
            $("#is_ppnChk").prop('checked', data.is_ppn === 1 ? true : false);
            $("#npwp").val(data.npwp);

            $('.error-text').text('');
            resetPicForm();
            clearTable();
            loadPIC(data.supplier_id);
            if(!data.is_active){
                $("#activate_supplier").show();
                $("#activate_supplier").attr("data-url", "/master/supplier/activate/"+data.supplier_id);
                $("#activate_supplier").attr("data-confirm", "Are you sure|want to activate supplier "+ data.business_entity +". "+ data.supplier_name +" ??");
            }



        } else {
            clearTable();
            $('.error-text').text('');
            $("#supplierForm").trigger("reset");
            $("#supplierForm").attr("action", "/master/supplier/add");
            $("#defaultModalLabel").text("Add Supplier")
        }

    }

    function saveInit(form){
        getDataContact();
        $("#is_ppn").val($("#is_ppnChk").prop("checked") ? 1 : 0);
        saveData(form, function() {
            loadData();
        });
    }

    // function validateForm(){
    //     var is_error = 0;
    //     is_error += validateRequired('#npwp', '#npwp_error');
    //     is_error += validateRequired('#bank_account_number', '#bank_account_number_error');
    //     is_error += validateRequired('#bank', '#bank_error');
    //     is_error += validateRequired('#pay_time', '#pay_time_error');
    //     is_error += validateEmail('#supplier_email', '#supplier_email_error_error');
    //     is_error += validateRequired('#supplier_fax', '#supplier_fax_error');
    //     is_error += validateRequired('#supplier_telephone', '#supplier_telephone_error');
    //     is_error += validateRequired('#supplier_name', '#supplier_name_error');
    //     is_error += validateRequired('#business_entity', '#business_entity_error');

    //     return is_error === 0 ? true : false;
    // }


    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }

    function activate(e) {
        deleteConfirm(e, function() {
            loadData();
            $("#closeModal").click();
        })
    }

    function loadPIC(supplier_id){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "GET",
            url: '/master/pic/load/supplier_id/'+supplier_id,
            success: function(res) {
                fillDataToTable(res.data);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    }

    function fillDataToTable(dataList){
        dataList.forEach(function(data) {
            addRowsContact(data);
        });
    }



    function btnAddContactClick(){
        if(validateContact()){
            let pic_type_id = $('#pic_type_id').val();
            var pic_type_name = $("#pic_type_id option[value='"+pic_type_id+"']").text()
            let pic_name = $('#pic_name').val();
            let pic_telephone = $('#pic_telephone').val();
            let pic_email = $('#pic_email').val();
            let data = {
                'pic_type_id': pic_type_id,
                'pic_type_name': pic_type_name,
                'pic_name': pic_name,
                'pic_telephone': pic_telephone,
                'pic_email': pic_email,
            };

            addRowsContact(data);
            resetPicForm();
        }
    }

    function validateContact(){
        var is_error = 0;
        is_error += validateRequired('#pic_email', '#pic_email_error');
        is_error += validateEmail('#pic_email', '#pic_email_error');
        is_error += validateRequired('#pic_telephone', '#pic_telephone_error');
        is_error += validateRequired('#pic_type_id', '#pic_type_id_error');
        is_error += validateRequired('#pic_name', '#pic_name_error');
        return is_error === 0 ? true : false;
    }

    function addRowsContact(data){
        var tr = document.createElement('tr');;
        var col1 = tr.appendChild(document.createElement('td'));
        var col2 = tr.appendChild(document.createElement('td'));
        var col3 = tr.appendChild(document.createElement('td'));
        var col4 = tr.appendChild(document.createElement('td'));
        var col5 = tr.appendChild(document.createElement('td'));
        var col6 = tr.appendChild(document.createElement('td'));

        col1.innerHTML = data.pic_type_id;
        col2.innerHTML = data.pic_type_name;
        col3.innerHTML = data.pic_name;
        col4.innerHTML = data.pic_telephone;
        col5.innerHTML = data.pic_email;
        var button = "";
        @if(isAccess('update', $MenuID))
            button += "<button type='button' class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value_contact(this)' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
        @endif
        @if(isAccess('delete', $MenuID))
            button += "<button type='button' class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete pic "+ data.pic_name +" ("+ data.pic_type_name +") ??' onClick='deleteInitContact(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
        @endif

        col6.innerHTML = button;

        document.getElementById("contentTableContact").appendChild(tr);
    }

    function resetPicForm(){
        $('#pic_type_id').val(1).change();
        $("#pic_name").val('');
        $("#pic_telephone").val('');
        $("#pic_email").val('');

        $("#btnAddContact").text('Add Contact');
    }


    function return_value_contact(e){
        var currentRow=$(e).closest("tr");
        var col1=currentRow.find("td:eq(0)").text();

        var valOption = parseInt(currentRow.find("td:eq(0)").text());

        $('#pic_type_id').val(valOption).change();
        $("#pic_name").val(currentRow.find("td:eq(2)").text());
        $("#pic_telephone").val(currentRow.find("td:eq(3)").text());
        $("#pic_email").val(currentRow.find("td:eq(4)").text());

        const element = document.getElementById('pic_type_id')
        element.focus({
            preventScroll: true
        });

        $("#btnAddContact").text('Update');
        justDeleteRow(e);

    }

    function clearTable(){
        var node = document.getElementById("contentTableContact");
            while (node.hasChildNodes()) {
            node.removeChild(node.lastChild);
        }
    }

    function getDataContact(){
        var picList = [];
        $('#tableContact tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            dataPic = {
                pic_type_id: this.cells[0].innerHTML,
                pic_type_name: this.cells[1].innerHTML,
                pic_name: this.cells[2].innerHTML,
                pic_telephone: this.cells[3].innerHTML,
                pic_email: this.cells[4].innerHTML,
            };
            picList.push(dataPic);
        });

        document.getElementById('picList').value = JSON.stringify(picList);
    }

    function deleteInitContact(e){
        doDeleteRow(e, function(){
            justDeleteRow(e);
        });
    }
    function justDeleteRow(e){
        var row = e.parentNode.parentNode;
        row.parentNode.removeChild(row); // remove the row
    }
    function doDeleteRow(e, callback){
        var text = $(e).attr("data-confirm").split('|');
        swal({
            title: text[0],
            text: text[1],
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true,
        },
        function(){
            callback();
        });
    }

    function saveImport(form){
        var data = getDataImport();
        document.getElementById('importList').value = JSON.stringify(data);
        saveData(form, function() {
            loadData();
            $("#closeModalImport").click();
        });
    }

    function getDataImport(){
        var dataList = [];
        $('#tableBodyImport tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            picList = [];
            picSales = {
                pic_type_id: 1,
                pic_name: this.cells[13].innerHTML,
                pic_telephone: this.cells[14].innerHTML,
                pic_email: this.cells[15].innerHTML,
            };
            picProduksi = {
                pic_type_id: 2,
                pic_name: this.cells[16].innerHTML,
                pic_telephone: this.cells[17].innerHTML,
                pic_email: this.cells[18].innerHTML,
            };
            picDelivery = {
                pic_type_id: 3,
                pic_name: this.cells[19].innerHTML,
                pic_telephone: this.cells[20].innerHTML,
                pic_email: this.cells[21].innerHTML,
            };
            picFinance = {
                pic_type_id: 4,
                pic_name: this.cells[22].innerHTML,
                pic_telephone: this.cells[23].innerHTML,
                pic_email: this.cells[24].innerHTML,
            };

            if(picSales.pic_name != "" && picSales.pic_telephone != ""){picList.push(picSales);}
            if(picProduksi.pic_name != "" && picProduksi.pic_telephone != ""){picList.push(picProduksi);}
            if(picDelivery.pic_name != "" && picDelivery.pic_telephone != ""){picList.push(picDelivery);}
            if(picFinance.pic_name != "" && picFinance.pic_telephone != ""){picList.push(picFinance);}


            dataSupp = {
                business_entity: this.cells[2].innerHTML,
                supplier_name: this.cells[3].innerHTML,
                supplier_telephone: this.cells[4].innerHTML,
                supplier_fax: this.cells[5].innerHTML,
                supplier_email: this.cells[6].innerHTML,
                supplier_address: this.cells[7].innerHTML,
                pay_time: this.cells[8].innerHTML,
                bank: this.cells[9].innerHTML,
                bank_account_number: this.cells[10].innerHTML,
                is_ppn: this.cells[11].innerHTML,
                npwp: this.cells[12].innerHTML,
                picList: picList,
            };
            dataList.push(dataSupp);
        });
        console.log(dataList);
        return dataList;
    }

    function readImportExcel(inputFileId){
        // var jobs = {!! json_encode($divisiList) !!};
        clearTable('tableBodyImport');

        let fileImported = $('#import_excel_supplier')[0].files[0];
        if(fileImported){
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(fileImported);
            fileReader.onload = (event)=>{
                let data = event.target.result;
                let workbook = XLSX.read(data,{type:"binary", cellDates:true});
                workbook.SheetNames.forEach(sheet => {
                    rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
                    displayImportData(rowObject);
                })
            }
        }

        $('#import_excel_supplier').val('');
    }

    function displayImportData(dataList){
        var no = 1;
        dataList.forEach(function(data) {
            if(data.business_entity !== undefined && data.supplier_name !== undefined)
            {
                addRowsImportTable(data, no);
                no++;
            }
        });
    }

    function addRowsImportTable(data, no){
        // console.log(data.business_entity);
        // var pay_time = null;
        // if(data.pay_time.toJSON().slice(0, 10)){
        //     var date = data.pay_time.toJSON().slice(0, 10);
        //     pay_time = date.slice(0, 4) + '-'
        //                 + date.slice(5, 7) + '-'
        //                 + date.slice(8, 10);
        // }


        var button = "";
        @if(isAccess('delete', $MenuID))
            button += "<button type='button' class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete supplier "+ data.supplier_name +" ("+ data.supplier_code +") ??' onClick='deleteInitContact(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
        @endif

        var tr = document.createElement('tr');
        tr.appendChild(document.createElement('td')).innerHTML = no;
        tr.appendChild(document.createElement('td')).innerHTML = button;
        tr.appendChild(document.createElement('td')).innerHTML = data.business_entity ? data.business_entity : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.supplier_name ? data.supplier_name : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.supplier_telephone ? data.supplier_telephone : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.supplier_fax ? data.supplier_fax : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.supplier_email ? data.supplier_email : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.supplier_address ? data.supplier_address : '';
        tr.appendChild(document.createElement('td')).innerHTML = parseInt(data.pay_time) ? parseInt(data.pay_time)  : '0';
        tr.appendChild(document.createElement('td')).innerHTML = data.bank ? data.bank : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.bank_account_number ? data.bank_account_number : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.ppn ? data.ppn : '0';
        tr.appendChild(document.createElement('td')).innerHTML = data.npwp ? data.npwp : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.sales_pic_name ? data.sales_pic_name : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.sales_pic_telephone ? data.sales_pic_telephone : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.sales_pic_email ? data.sales_pic_email : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.produksi_pic_name ? data.produksi_pic_name : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.produksi_pic_telephone ? data.produksi_pic_telephone : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.produksi_pic_email ? data.produksi_pic_email : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.delivery_pic_name ? data.delivery_pic_name : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.delivery_pic_telephone ? data.delivery_pic_telephone : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.delivery_pic_email ? data.delivery_pic_email : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.finance_pic_name ? data.finance_pic_name : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.finance_pic_telephone ? data.finance_pic_telephone : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.finance_pic_email ? data.finance_pic_email : '';

        document.getElementById("tableBodyImport").appendChild(tr);
    }

</script>
@endsection

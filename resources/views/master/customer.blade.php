@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">CUSTOMER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Master</a></li>
                        <li class="breadcrumb-item"><a href="#!">Customers</a></li>
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
                                    <h5>Customers</h5>
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
                                                    <input type="file" class="form-control" name="import_excel_customer" id="import_excel_customer" accept=".xls, .xlsx" placeholder="Choose File.xlsx">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary" onClick="readImportExcel('#import_excel_customer')" data-toggle="modal" data-target="#large-ModalImport" type="button">Upload Excel</button>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <a href="/master/customer/template-download" class="btn btn-success">Download Template</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-radio">
                                                <div class="radio radio-inline">
                                                    <label>
                                                        <input type="radio" onClick="activeClick()" name="is_active_customer" value="1" checked="checked">
                                                        <i class="helper"></i>Active Customer
                                                    </label>
                                                </div>
                                                <div class="radio radio-inline">
                                                    <label>
                                                        <input type="radio" onClick="activeClick()" name="is_active_customer" value="0" >
                                                        <i class="helper"></i>Non Active Customer
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
                                                    <th>Customer Name</th>
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
<div class="modal fade" id="large-Modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="customerForm">
                @csrf
                <input type="hidden" name="customer_id" id="customer_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Customer Code</label>
                        <div class="col-sm-3">
                            <input type="text" readonly name="customer_code" id="customer_code" class="form-control">
                        </div>
                        <label class="col-sm-5 col-form-label">Auto generate after save</label>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Customer Name <span class="text-danger"> *</span></label>
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
                            <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                            <span id="customer_name_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Phone <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="number" name="customer_telephone" id="customer_telephone"  class="form-control"
                            oninput="validateMaxLength(this)" maxlength="13" required>
                            <span id="customer_telephone_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Fax <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="number" name="customer_fax" id="customer_fax" class="form-control"
                            oninput="validateMaxLength(this)" maxlength="13" required>
                            <span id="customer_fax_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="email" name="customer_email" onchange="validateEmail('#customer_email', '#customer_email_error')" id="customer_email" class="form-control" required>
                            <span id="customer_email_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <textarea name="customer_address" id="customer_address" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Billing Address</label>
                        <div class="col-sm-10">
                            <textarea name="billing_address" id="billing_address" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Delivery Address</label>
                        <div class="col-sm-10">
                            <textarea name="delivery_address" id="delivery_address" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Tempo Bayar (Hari) <span class="text-danger"> *</span></label>
                        <div class="col-sm-4">
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
                        <label class="col-sm-2 col-form-label">NPWP <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="npwp" id="npwp" class="form-control"
                            oninput="validateNPWP(this)" required>
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
                            <span id="pic_name_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Phone <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="pic_telephone" id="pic_telephone" class="form-control">
                            <span id="pic_telephone_error" class="text-danger error-text"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="pic_email" id="pic_email" onchange="validateEmail('#pic_email', '#pic_email_error')" class="form-control">
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
                    <button type="button" class="btn btn-primary waves-effect waves-light" id="addCustomer" onClick="saveInit('#customerForm')">Save</button>
                    <button type="button" class="btn btn-warning waves-effect waves-light" id="activate_customer" onClick='activate(this)'>Activate Customer</button>
                </div>


            </form>
        </div>
    </div>
</div>


<!--MODAL IMPORT EXCEL--->
<div class="modal fade" id="large-ModalImport" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Import Customer</h4>
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
                                        <th>Customer Name</th>
                                        <th>Telephone</th>
                                        <th>Fax</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Biilling Address</th>
                                        <th>Delivery Address</th>
                                        <th>Pay Time</th>
                                        <th>Bank</th>
                                        <th>Bank Account Number</th>
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
                <form action="/master/customer/import" method="post" enctype="multipart/form-data" id="importForm">
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
        var is_active = $('input[name="is_active_customer"]:checked').val();
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/customer/load/'+is_active,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.business_entity+". "+row.customer_name;
                    }
                },
                { "data": "customer_telephone" },
                { "data": "customer_email" },
                {
                    "mData": "customer_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete customer "+ row.customer_name +" ??' data-url='/master/customer/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }
            ]
        });
    }

    function return_value(e, data){
        $("#activate_customer").hide();

        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#customerForm").attr("action", "/master/customer/update");
            $("#defaultModalLabel").text("Edit Customer")
            $("#customer_id").val(data.customer_id);
            $("#customer_code").val(data.customer_code);
            $("#business_entity").val(data.business_entity);
            $("#business_entity").trigger('change');
            $("#customer_name").val(data.customer_name);
            $("#customer_telephone").val(data.customer_telephone);
            $("#customer_fax").val(data.customer_fax);
            $("#customer_email").val(data.customer_email);
            $("#customer_address").val(data.customer_address);
            $("#billing_address").val(data.billing_address);
            $("#delivery_address").val(data.delivery_address);
            $("#pay_time").val(data.pay_time);
            $("#bank").val(data.bank);
            $("#bank_account_number").val(data.bank_account_number);
            $("#npwp").val(data.npwp);

            $('.error-text').text('');
            resetPicForm();
            clearTable('contentTableContact');
            loadPIC(data.customer_id);

            if(!data.is_active){
                $("#activate_customer").show();
                $("#activate_customer").attr("data-url", "/master/customer/activate/"+data.customer_id);
                $("#activate_customer").attr("data-confirm", "Are you sure|want to activate customer "+ data.business_entity +". "+ data.customer_name +" ??");
            }

        } else {
            $('.error-text').text('');
            clearTable('contentTableContact');
            $("#customerForm").trigger("reset");
            $("#customerForm").attr("action", "/master/customer/add");
            $("#defaultModalLabel").text("Add Customer")
        }

    }

    function saveInit(form){
        getDataContact();
        saveData(form, function() {
            loadData();
        });
    }
    function validateForm(){
        var is_error = 0;
        is_error += validateRequired('#npwp', '#npwp_error');
        is_error += validateRequired('#bank_account_number', '#bank_account_number_error');
        is_error += validateRequired('#bank', '#bank_error');
        is_error += validateRequired('#pay_time', '#pay_time_error');
        is_error += validateEmail('#customer_email', '#customer_email_error_error');
        is_error += validateRequired('#customer_fax', '#customer_fax_error');
        is_error += validateRequired('#customer_telephone', '#customer_telephone_error');
        is_error += validateRequired('#customer_name', '#customer_name_error');
        is_error += validateRequired('#business_entity', '#business_entity_error');

        return is_error === 0 ? true : false;
    }


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

    function loadPIC(customer_id){
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $.ajax({
            type: "GET",
            url: '/master/pic/load/customer_id/'+customer_id,
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
                pic_name: this.cells[14].innerHTML,
                pic_telephone: this.cells[15].innerHTML,
                pic_email: this.cells[16].innerHTML,
            };
            picProduksi = {
                pic_type_id: 2,
                pic_name: this.cells[17].innerHTML,
                pic_telephone: this.cells[18].innerHTML,
                pic_email: this.cells[19].innerHTML,
            };
            picDelivery = {
                pic_type_id: 3,
                pic_name: this.cells[20].innerHTML,
                pic_telephone: this.cells[21].innerHTML,
                pic_email: this.cells[22].innerHTML,
            };
            picFinance = {
                pic_type_id: 4,
                pic_name: this.cells[23].innerHTML,
                pic_telephone: this.cells[24].innerHTML,
                pic_email: this.cells[25].innerHTML,
            };

            if(picSales.pic_name != "" && picSales.pic_telephone != ""){picList.push(picSales);}
            if(picProduksi.pic_name != "" && picProduksi.pic_telephone != ""){picList.push(picProduksi);}
            if(picDelivery.pic_name != "" && picDelivery.pic_telephone != ""){picList.push(picDelivery);}
            if(picFinance.pic_name != "" && picFinance.pic_telephone != ""){picList.push(picFinance);}


            dataCust = {
                business_entity: this.cells[2].innerHTML,
                customer_name: this.cells[3].innerHTML,
                customer_telephone: this.cells[4].innerHTML,
                customer_fax: this.cells[5].innerHTML,
                customer_email: this.cells[6].innerHTML,
                customer_address: this.cells[7].innerHTML,
                billing_address: this.cells[8].innerHTML,
                delivery_address: this.cells[9].innerHTML,
                pay_time: this.cells[10].innerHTML,
                bank: this.cells[11].innerHTML,
                bank_account_number: this.cells[12].innerHTML,
                npwp: this.cells[13].innerHTML,
                picList: picList,
            };
            dataList.push(dataCust);
        });

        return dataList;
    }

    function readImportExcel(inputFileId){
        clearTable('tableBodyImport');

        let fileImported = $('#import_excel_customer')[0].files[0];
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

        $('#import_excel_customer').val('');
    }

    function displayImportData(dataList){
        var no = 1;
        dataList.forEach(function(data) {
            if(data.business_entity !== undefined && data.customer_name !== undefined){
                addRowsImportTable(data, no);
            }
            no++;
        });
    }

    function addRowsImportTable(data, no){
        var pay_time = parseInt(data.pay_time) ? parseInt(data.pay_time) : 0;

        var button = "";
        @if(isAccess('delete', $MenuID))
            button += "<button type='button' class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete customer "+ data.customer_name +" ("+ data.customer_code +") ??' onClick='deleteInitContact(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
        @endif

        var tr = document.createElement('tr');
        var td = document.createElement('td');
        tr.appendChild(document.createElement('td')).innerHTML = no;
        tr.appendChild(document.createElement('td')).innerHTML = button;
        tr.appendChild(document.createElement('td')).innerHTML = data.business_entity ? data.business_entity : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.customer_name ? data.customer_name : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.customer_telephone ? data.customer_telephone : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.customer_fax ? data.customer_fax : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.customer_email ? data.customer_email : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.customer_address ? data.customer_address : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.billing_address ? data.billing_address : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.delivery_address ? data.delivery_address : '';
        tr.appendChild(document.createElement('td')).innerHTML = pay_time;
        tr.appendChild(document.createElement('td')).innerHTML = data.bank ? data.bank : '';
        tr.appendChild(document.createElement('td')).innerHTML = data.bank_account_number ? data.bank_account_number : '';
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

    function clearTable(idTable){
        var node = document.getElementById(idTable);
            while (node.hasChildNodes()) {
            node.removeChild(node.lastChild);
        }
    }

</script>
@endsection

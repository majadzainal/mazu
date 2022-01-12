@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">BILL OF MATERIAL TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Part</a></li>
                        <li class="breadcrumb-item"><a href="#!">Bill Of Material</a></li>
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
                        <div class="col-sm-12" id="table">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Bill of Material</h5>
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
                                            <label class="col-sm-2 col-form-label">Import</label>
                                            <div class="col-sm-5 input-group input-group-button">
                                                <input type="file" class="form-control" name="import_bill_material" id="import_bill_material" accept=".xls, .xlsx" placeholder="Choose File .xlsx">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" onClick="readImportExcels('#import_bill_material')" data-toggle="modal" data-target="#large-ModalImport" type="button">Upload Excel</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <a href="/bill-material/template-download" class="btn btn-success">Download Template</a>
                                            </div>
                                        </div>
                                        <hr>
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" btn="add" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add</button>
                                    @endif
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th width="25%">Customer</th>
                                                    <th width="25%">Part Name</th>
                                                    <th width="15%">Part Number</th>
                                                    <th width="15%">Date Input</th>
                                                    <th width="12%">Status</th>
                                                    <th width="11%">Action</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" id="input">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Bill of Material</h5>
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
                                    <form action="/bill-material/add" method="post" enctype="multipart/form-data" id="partForm">
                                        @csrf
                                        <input type="hidden" name="bill_material_id" id="bill_material_id">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Customer <span class="text-danger"> *</span></label>
                                            <div class="col-sm-10">
                                                <select name="customer_id" id="customer_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($customerList as $ls)
                                                        <option value="{{ $ls->customer_id }}">{{ $ls->business_entity.". ".$ls->customer_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Part Name <span class="text-danger"> *</span></label>
                                            <div class="col-sm-10">
                                                <select name="part_customer_id" id="part_customer_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status <span class="text-danger"> *</span></label>
                                            <div class="col-sm-10">
                                                <select name="status_id" id="status_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach(getStatus() as $ls)
                                                        <option value="{{ $ls['status_id'] }}">{{ $ls['status_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Date Input <span class="text-danger"> *</span></label>
                                            <div class="col-sm-5">
                                                <input type="date" name="date_input" id="date_input" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12"><button type="button" onClick="addMaterial('')" class="btn btn-info btn-round btn-sm waves-effect">Add Material</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th width="30%">Material <span class="text-danger"> *</span></th>
                                                        <th width="15%">Amount Usage <span class="text-danger"> *</span></th>
                                                        <th width="15%">Unit</th>
                                                        <th>Price</th>
                                                        <th>Cost</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyMaterial">
                                                    <tr>
                                                        <td>
                                                            <select name="part_id[]" class="part_id select-style col-sm-12" onChange="partChange(this)" required>
                                                                <option value="">--Select--</option>
                                                                @foreach($partList as $ls)
                                                                    @if($ls->part_supplier_id != "")
                                                                        <option value="{{ $ls->part_supplier_id }}">{{ $ls->part_name." - ".$ls->part_number."+Supplier : ".$ls->supplier->business_entity.". ".$ls->supplier->supplier_name."+Divisi : ".$ls->divisi->divisi_name }}</option>
                                                                    @else
                                                                        <option value="{{ $ls->part_customer_id }}">{{ $ls->part_name." - ".$ls->part_number."+Customer : ".$ls->customer->business_entity.". ".$ls->customer->customer_name."+Divisi : ".$ls->divisi->divisi_name }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td><input type="number" name="amount_usage[]" id="amount_usage" class="amount_usage form-control" onchange="numberingFormat(this)" onInput="countCost(this)" step="0.00000001" required></td>
                                                        <td>
                                                            <input type="hidden" name="unit_id[]" class="unit_id">
                                                            <select name="unit" class="unit js-example-placeholder col-sm-12" disabled>
                                                                <option value="">--Select--</option>
                                                                @foreach($unitList as $ls)
                                                                    <option value="{{ $ls->unit_id }}">{{ $ls->unit_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="price[]" class="price form-control" readonly>
                                                        </td>
                                                        <td><input type="number" name="cost[]" class="cost form-control" readonly></td>
                                                        <td><button type="button" onClick="removeMaterial(this)" class="btn waves-effect waves-light btn-warning btn-icon">&nbsp;<i class='icofont icofont-trash'></i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-9"></label>
                                            <div class="col-sm-3" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#partForm')">Save</button>
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
<!--MODAL IMPORT EXCEL--->
<div class="modal fade" id="large-ModalImport" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Import Part Customer</h4>
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
                                        <th>Customer</th>
                                        <th>Part Name</th>
                                        <th>Status</th>
                                        <th>Date Input</th>
                                        <th>Material</th>
                                        <th>Usage</th>
                                        <th>Price</th>
                                        <th>Cost</th>
                                        <th style="display:none">Customer ID</th>
                                        <th style="display:none">Part ID</th>
                                        <th style="display:none">Material ID</th>
                                        <th style="display:none">Status ID</th>
                                        <th style="display:none">Unit ID</th>
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
                <form action="/bill-material/import" method="post" enctype="multipart/form-data" id="importForm">
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
        $("#input").hide();
        var arrStatus = {!! json_encode(getStatus()) !!};
    } );

    $('#customer_id').change(function(e) {
        var arrPartCustomer = {!! json_encode($partCustomerList) !!};
        var partCustomer = arrPartCustomer.filter(e => e.customer_id === $(this).val());
        var dataPart = [];

        partCustomer.forEach(function(part) {
            var part = {
                id: part.part_customer_id,
                text: part.part_name+" - "+part.part_number
            };
            dataPart.push(part);
        });

        $("#part_customer_id").html('<option value="">--select--</option>');
        $("#part_customer_id").select2('destroy');
        $("#part_customer_id").select2({
            data: dataPart,
            placeholder: "--select--"
        });
    });

    function loadData(){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "ajax": '/bill-material/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.customer.business_entity+". "+row.customer.customer_name;
                    }
                },
                { "data": "part_customer.part_name" },
                { "data": "part_customer.part_number" },
                { "data": "date_input" },
                {  "mRender": function (data, type, row, num) {
                        var arrStatus = {!! json_encode(getStatus()) !!};
                        var status = arrStatus.filter(e => e.status_id === row.status_id);
                        return status[0].status_name;
                    }
                },
                {
                    "mData": "bill_material_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.part_customer.part_name +" part??' data-url='/bill-material/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
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
            $("#partForm").attr("action", "/bill-material/update");
            $("#part_customer_id").change();
            $("#bill_material_id").val(data.bill_material_id);
            $("#customer_id").val(data.customer_id);
            $("#customer_id").trigger('change');
            $("#date_input").val(data.date_input);
            $("#status_id").val(data.status_id);
            $("#status_id").trigger('change');
            $("#bodyMaterial").html('');
            data.bom_item.forEach(function(item) {
                addMaterial(item);
            });
            $("#part_customer_id").val(data.part_customer_id);
            $("#part_customer_id").trigger('change');
        } else {
            $("#partForm").trigger("reset");
            $("#partForm").attr("action", "/bill-material/add");
            $(".js-example-placeholder").trigger('change');
            $("#bodyMaterial").html('');
            addMaterial('');
        }

        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;
    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form){
        var values = $("select[name='part_id[]']").map(function(){return $(this).val();}).get();
        if(values.length >= 1){
            saveData(form, function() {
                loadData();
                loadSelect2();
                $('#bodyMaterial').html('');
                addMaterial('');
                $("#input").hide();
                $("#table").show();
            });
        }else{
            swal({
                title: "Error Validate",
                text: "Please add material!",
                type: "warning",
                showCancelButton: false,
            });
        }
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }

    function displayImportData(dataList){

        var arrCustomer = {!! json_encode($customerList) !!};
        var arrPartCustomer = {!! json_encode($partCustomerList) !!};
        var arrPartSupplier = {!! json_encode($partList) !!};
        var arrStatus = {!! json_encode(getStatus()) !!};

        var no = 1;
        dataList.forEach(function(data) {

            var today = getDateNow();
            // var customer = arrCustomer.filter(e => e.customer_id === data.customer_id);
            // var partCustomer = arrPartCustomer.filter(e => e.part_customer_id === data.part_customer_id);
            // var partSupplier = "";
            // partSupplier = arrPartSupplier.filter(e => e.part_supplier_id === data.part_supplier_id);

            // if(parseInt(data.wip) === 1){
            //     partSupplier = arrPartSupplier.filter(e => e.part_customer_id === data.part_supplier_id);
            // }

            var customer = arrCustomer.filter(e => e.business_entity.toLowerCase()+". "+e.customer_name.toLowerCase() === data.customer.toLowerCase());
            var partCustomer = arrPartCustomer.filter(e => e.part_name.toLowerCase() === data.part_name.toLowerCase());
            var partSupplier = arrPartSupplier.filter(e => e.part_name.toLowerCase() === data.material.toLowerCase());

            if(customer != "" && partCustomer != "" && partSupplier != ""){

                var status = arrStatus.filter(e => e.status_name.toLowerCase() === data.status.toLowerCase());
                var materialPrice = partSupplier[0].part_price.filter(a => a.effective_date <= today);

                var date_input = "";
                if(data.date_input.toJSON().slice(0, 10)){
                    var date = data.date_input.toJSON().slice(0, 10);
                    date_input = date.slice(0, 4) + '-'
                                + date.slice(5, 7) + '-'
                                + date.slice(8, 10);
                }

                button = "<button type='button' class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete material "+ partSupplier[0].part_name +" ??' onClick='doDeleteImport(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";

                var part_id_import = "";
                part_id_import = parseInt(data.wip) === 1 ? partSupplier[0].part_customer_id : partSupplier[0].part_supplier_id;

                var tableBody = '';
                tableBody += '<tr>';
                tableBody += '<td>'+ no +'</td>';
                tableBody += '<td>'+ button +'</td>';
                tableBody += '<td>'+ customer[0].business_entity + '. ' + customer[0].customer_name +'</td>';
                tableBody += '<td>'+ partCustomer[0].part_name +'</td>';
                tableBody += '<td>'+ (status != "" ? status[0].status_name : "") +'</td>';
                tableBody += '<td>'+ date_input +'</td>';
                tableBody += '<td>'+ partSupplier[0].part_name +'</td>';
                tableBody += '<td>'+ (data.amount_usage ? data.amount_usage : "") +'</td>';
                tableBody += '<td>'+ (materialPrice != "" ? materialPrice[0].price : "") +'</td>';
                tableBody += '<td>'+ (parseInt(materialPrice[0].price) * parseFloat(data.amount_usage)) +'</td>';
                tableBody += '<td style="display:none">'+ customer[0].customer_id +'</td>';
                tableBody += '<td style="display:none">'+ partCustomer[0].part_customer_id +'</td>';
                tableBody += '<td style="display:none">'+ part_id_import +'</td>';
                tableBody += '<td style="display:none">'+ (status != "" ? status[0].status_id : "") +'</td>';
                tableBody += '<td style="display:none">'+ partSupplier[0].unit_id +'</td>';
                tableBody += '</tr>';
                $("#tableBodyImport").append(tableBody);

            }
            no++;
        });
    }

    function getDataImport(){
        var dataList = [];
        $('#tableBodyImport tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            data = {
                customer_id: this.cells[10].innerHTML,
                part_customer_id: this.cells[11].innerHTML,
                part_supplier_id: this.cells[12].innerHTML,
                date_input: this.cells[5].innerHTML,
                status_id: this.cells[13].innerHTML,
                amount_usage: this.cells[7].innerHTML,
                price: this.cells[8].innerHTML,
                cost: this.cells[9].innerHTML,
                part_supplier_name: this.cells[6].innerHTML,
                unit_id: this.cells[14].innerHTML,
            };
            dataList.push(data);
        });

        return dataList;
    }

    function saveImport(form){
        var data = getDataImport();
        document.getElementById('importList').value = JSON.stringify(data);
        saveData(form, function() {
            loadData();
            $("#closeModalImport").click();
        });
    }

    async function partChange(e){

        var today = getDateNow();

        //var arrPartSupplier = {!! json_encode($partSupplierList) !!};
        var arrPartList = {!! json_encode($partList) !!};
        var material = arrPartList.filter(a => a.part_supplier_id === $(e).val() || a.part_customer_id === $(e).val() );

        var price = await getPrice($(e).val());

        $(e).parent().parent().find(".price").val(price);
        $(e).parent().parent().find(".unit").val(material[0].unit_id);
        $(e).parent().parent().find(".unit").trigger('change');
        $(e).parent().parent().find(".unit_id").val(material[0].unit_id);

        countCost(e);

    }

    async function getPrice(partId){
        var price = 0;
        if(partId != ""){
            var today = getDateNow();
            var arrPartList = {!! json_encode($partList) !!};
            var material = arrPartList.filter(a => a.part_supplier_id === partId || a.part_customer_id === partId );

            if(material != ""){

                if (material[0].part_customer_id !== undefined){
                    var priceBom = await getData('/bill-material/getCostBOM/'+ material[0].part_customer_id);
                    var priceBop = await getData('/bill-process/getCostBOP/'+ material[0].part_customer_id);
                    price = priceBom + priceBop;

                } else {
                    var partPrice = material[0].part_price.filter(a => a.effective_date <= today);
                    partPrice = sortDescDateEffective(partPrice);
                    if(partPrice != "")
                    price = partPrice[0].price;
                }
            }
        }

        return price;
    }

    function countCost(e){
        var amount_usage = $(e).parent().parent().find(".amount_usage").val();
        var price = $(e).parent().parent().find(".price").val();

        var cost = parseInt(price) * parseFloat(amount_usage);
        $(e).parent().parent().find(".cost").val(Number(cost).toFixed(8));
    }

    function numberingFormat(e){
        var amount_usage = $(e).parent().parent().find(".amount_usage").val();
        $(e).parent().parent().find(".amount_usage").val(Number(amount_usage).toFixed(8));
    }

    async function addMaterial(data){
        var item_bom_id = data.item_bom_id ? data.item_bom_id : "";
        var part_id = data.part_id ? data.part_id : "";
        var amount_usage = data.amount_usage ? data.amount_usage : "";
        var unit_id = data.unit_id ? data.unit_id : "";
        var price = await getPrice(part_id);
        var cost = price * amount_usage;

        var material = '<tr>';
        material +='<td><input type="hidden" name="item_bom_id[]" value="'+item_bom_id+'">';
        material +=  '<select name="part_id[]" class="part_id select-style col-sm-12" onChange="partChange(this)" required>';
        material +=      '<option value="">--Select--</option>';
                            @foreach($partList as $ls)
                                @if($ls->part_supplier_id != "")
                                    var selected = (part_id == '{{ $ls->part_supplier_id }}')? "selected":"";
                                    material +='<option value="{{ $ls->part_supplier_id }}" '+selected+'>{{ $ls->part_name." - ".$ls->part_number."+Supplier : ".$ls->supplier->business_entity.". ".$ls->supplier->supplier_name."+Divisi : ".$ls->divisi->divisi_name }}</option>';
                                @else
                                    var selected = (part_id == '{{ $ls->part_customer_id }}')? "selected":"";
                                    material +='<option value="{{ $ls->part_customer_id }}" '+selected+'>{{ $ls->part_name." - ".$ls->part_number."+Supplier : ".$ls->customer->business_entity.". ".$ls->customer->customer_name."+Divisi : ".$ls->divisi->divisi_name }}</option>';
                                @endif
                            @endforeach
        material +=  '</select>';
        material +='</td>';
        material +='<td><input type="number" name="amount_usage[]" value="'+ Number(amount_usage).toFixed(8) +'" class="amount_usage form-control" onchange="numberingFormat(this)" onInput="countCost(this)" step="0.00000001" required></td>';
        material +='<td><input type="hidden" name="unit_id[]" class="unit_id" value="'+unit_id+'">';
        material +=  '<select name="unit" class="unit js-example-placeholder col-sm-12" disabled >';
        material +=      '<option value="">--Select--</option>';
                            @foreach($unitList as $ls)
                                var selected = (unit_id == '{{ $ls->unit_id }}')? "selected":"";
                                material += '<option value="{{ $ls->unit_id }}" '+selected+'>{{ $ls->unit_name }}</option>';
                            @endforeach
        material +=  '</select>';
        material +='</td>';
        material +='<td><input type="number" name="price[]" value="'+ price +'" class="price form-control" readonly></td>';
        material +='<td><input type="number" name="cost[]" value="'+ cost +'" class="cost form-control" readonly></td>';
        material +='<td><button type="button" onClick="removeMaterial(this)" class="btn waves-effect waves-light btn-warning btn-icon">&nbsp;<i class="icofont icofont-trash"></i></button></td>';
        material +='</tr>';

        $('#bodyMaterial').append(material);
        loadSelect2();

    }

    function removeMaterial(e){
        $(e).parent().parent().remove();
    }



</script>
@endsection

@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">PART CUSTOMER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Part</a></li>
                        <li class="breadcrumb-item"><a href="#!">Part Customer</a></li>
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
                                    <h5>Part Customer</h5>
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
                                                <input type="file" class="form-control" name="import_part" id="import_part" accept=".xls, .xlsx" placeholder="Choose File .xlsx">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" onClick="readImportExcels('#import_part')" data-toggle="modal" data-target="#large-ModalImport" type="button">Upload Excel</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <a href="/part-customer/template-download" class="btn btn-success">Download Template</a>
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
                                                    <th>Customer</th>
                                                    <th>Divisi</th>
                                                    <th>Part Name</th>
                                                    <th>Part Number</th>
                                                    <th>Add Date</th>
                                                    <th>Stock</th>
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
<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Part Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="partForm">
                @csrf
                <input type="hidden" name="part_customer_id" id="part_customer_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Customer <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="customer_id" id="customer_id" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($customerList as $ls)
                                    <option value="{{ $ls->customer_id }}">{{ $ls->business_entity.". ".$ls->customer_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Divisi <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="divisi_id" id="divisi_id" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($divisiList as $ls)
                                    <option value="{{ $ls->divisi_id }}">{{ $ls->divisi_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Part Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="part_name" id="part_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Part Number <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="part_number" id="part_number" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Add Date <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="date" name="add_date" id="add_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Production <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="plant_id" id="plant_id" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($plantList as $ls)
                                    <option value="{{ $ls->plant_id }}">{{ $ls->plant_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Stock</label>
                        <div class="col-sm-4">
                            <input type="text" readonly name="stock" id="stock" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Unit <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="unit_id" id="unit_id" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($unitList as $ls)
                                    <option value="{{ $ls->unit_id }}">{{ $ls->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Barang Setengah Jadi</label>
                        <div class="col-sm-10">
                            <div class="checkbox-color checkbox-primary">
                                <input name="is_supplier"  id="is_supplier" type="hidden">
                                <input name="is_supplierChk" id="is_supplierChk" type="checkbox" >
                                <label for="is_supplierChk">
                                    Work In Progress (WIP)
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Status <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="status" id="status" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($statusList as $ls)
                                    <option value="{{ $ls->status_id }}">{{ $ls->status }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12"><button type="button" onClick="addPrice('')" class="btn btn-info btn-round btn-sm waves-effect">Add Price</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Effective Date <span class="text-danger"> *</span></th>
                                    <th>Price <span class="text-danger"> *</span></th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="bodyPrice">
                                <tr>
                                    <td><input type="date" name="effective_date[]" id="effective_date_0" class="effective_date form-control" value="{{ date('Y-m-d') }}" required></td>
                                    <td><input type="number" name="price[]" id="price" class="form-control" required></td>
                                    <td><button type="button" onClick="removePrice(this)" class="btn waves-effect waves-light btn-warning btn-icon">&nbsp;<i class='icofont icofont-trash'></i></button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-12"><button type="button" onClick="addStock('')" class="btn btn-info btn-round btn-sm waves-effect">Add Stock</button>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Stock *</th>
                                    <th>Add Qty Stock <span class="text-danger"> *</span></th>
                                    <th width="50%">Warehouse *</th>
                                </tr>
                            </thead>
                            <tbody id="bodyStock">
                                <tr>
                                    <td><input type="number" name="stock_exist[]" id="stock_exist_0" class="stock_exist form-control" required></td>
                                    <td><input type="number" name="stock[]" id="stock_0" class="stock form-control" required></td>
                                    <td>
                                        <select name="warehouse_id[]" class="warehouse_id js-example-placeholder col-sm-12" required>
                                            <option value="">--Select--</option>
                                            @foreach($warehouseList as $ls)
                                                <option value="{{ $ls->warehouse_id }}">{{ $ls->warehouse_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#partForm')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
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
                                        <th>Divisi Name</th>
                                        <th>Part Name</th>
                                        <th>Part Number</th>
                                        <th>Production</th>
                                        <th>Add Date</th>
                                        <th>Unit</th>
                                        <th>WIP</th>
                                        <th>Status</th>
                                        <th>Price</th>
                                        <th>Effective Price</th>
                                        <th>Stock</th>
                                        <th>Warehouse</th>
                                        <th style="display:none">Customer ID</th>
                                        <th style="display:none">Divisi ID</th>
                                        <th style="display:none">Unit ID</th>
                                        <th style="display:none">Plant ID</th>
                                        <th style="display:none">Status ID</th>
                                        <th style="display:none">Warehouse ID</th>
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
                <form action="/part-customer/import" method="post" enctype="multipart/form-data" id="importForm">
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
        loadSelect2();

    } );

    $(document).on("change", ".effective_date", function(){
        var index = $(".effective_date").index(this);
        var tthis = $(this);
        $(".effective_date").each(function( i ) {
            if( i != index && tthis.val() == $(this).val()){
                swal("Info!", "Effective date is the same");
                tthis.val("");
            }
        });
    });
    $(document).on("change", ".warehouse_id", function(){
        var index = $(".warehouse_id").index(this);
        var tthis = $(this);
        $(".warehouse_id").each(function( i ) {
            if( i != index && tthis.val() == $(this).val()){
                swal("Info!", "Warehouse is the same");
                tthis.val("");
            }
        });
    });

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
            "ajax": '/part-customer/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "customer.customer_name" },
                { "data": "divisi.divisi_name" },
                { "data": "part_name" },
                { "data": "part_number" },
                { "data": "add_date" },
                { "data": "unit.unit_name" },
                {
                    "mData": "part_customer_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete Part "+ row.part_name +" ??' data-url='/part-customer/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
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
            $("#partForm").attr("action", "/part-customer/update");
            $("#defaultModalLabel").text("Edit Part Customer")
            $("#part_customer_id").val(data.part_customer_id);
            $("#customer_id").val(data.customer_id);
            $('#customer_id').trigger('change');
            $("#divisi_id").val(data.divisi_id);
            $('#divisi_id').trigger('change');
            $("#part_name").val(data.part_name);
            $("#part_number").val(data.part_number);
            $("#add_date").val(data.add_date);
            $("#plant_id").val(data.plant_id);
            $('#plant_id').trigger('change');
            $("#stock").val(data.stock);
            $("#unit_id").val(data.unit_id);
            $('#unit_id').trigger('change');
            $("#is_supplierChk").prop('checked', data.is_supplier === 1 ? true : false);
            $("#status").val(data.status);
            $('#status').trigger('change');
            $('#bodyPrice').html('');
            data.part_price.forEach(addPrice);
            $('#bodyStock').html('');
            data.stock_warehouse.forEach(addStock);

        } else {
            $("#partForm").trigger("reset");
            $("#partForm").attr("action", "/part-customer/add");
            $("#defaultModalLabel").text("Add Part Customer");
            $('#bodyPrice').html('');
            $('#bodyStock').html('');
            addPrice('');
            addStock('');
            loadSelect2();
        }

    }

    function saveInit(form){
        var values = $("input[name='effective_date[]']").map(function(){return $(this).val();}).get();
        if(values.length >= 1){
            $("#is_supplier").val($("#is_supplierChk").prop("checked") ? 1 : 0);
            saveData(form, function() {
                loadData();
                loadSelect2();
                $('#bodyPrice').html('');
                addPrice('');
            });
        }else{
            swal({
                title: "Error Validate",
                text: "Please input price!",
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
        var arrDivisi = {!! json_encode($divisiList) !!};
        var arrUnit = {!! json_encode($unitList) !!};
        var arrPlant = {!! json_encode($plantList) !!};
        var arrStatus = {!! json_encode($statusList) !!};
        var arrWarehouse = {!! json_encode($warehouseList) !!};

        var no = 1;
        dataList.forEach(function(data) {
            // var customer = arrCustomer.filter(e => e.customer_id === data.customer_id);
            var customer = arrCustomer.filter(e => e.business_entity.toLowerCase()+". "+e.customer_name.toLowerCase() === data.customer.toLowerCase() );

            if(customer != ""){

                // var divisi = arrDivisi.filter(e => e.divisi_id === data.divisi_id);
                // var unit = arrUnit.filter(e => e.unit_id === data.unit_id);
                // var plant = arrPlant.filter(e => e.plant_id === data.plant_id);
                // var status = arrStatus.filter(e => e.status_id === data.status_id);
                // var warehouse = arrWarehouse.filter(e => e.warehouse_id === data.warehouse_id);

                var divisi = arrDivisi.filter(e => e.divisi_name.toLowerCase() === data.divisi.toLowerCase() );
                var unit = arrUnit.filter(e => e.unit_name.toLowerCase() === data.unit.toLowerCase());
                var plant = arrPlant.filter(e => e.plant_name.toLowerCase() === data.production.toLowerCase());
                var status = arrStatus.filter(e => e.status.toLowerCase() === data.status.toLowerCase());
                var warehouse = arrWarehouse.filter(e => e.warehouse_name.toLowerCase() === data.warehouse.toLowerCase() );

                var add_date = "";
                if(data.add_date.toJSON().slice(0, 10)){
                    var date = data.add_date.toJSON().slice(0, 10);
                    add_date = date.slice(0, 4) + '-'
                                + date.slice(5, 7) + '-'
                                + date.slice(8, 10);
                }

                var effective_price = "";
                if(data.effective_price.toJSON().slice(0, 10)){
                    var dateTemp = data.effective_price.toJSON().slice(0, 10);
                    effective_price = dateTemp.slice(0, 4) + '-'
                                + dateTemp.slice(5, 7) + '-'
                                + dateTemp.slice(8, 10);
                }

                button = "<button type='button' class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete part "+ data.part_name +" ??' onClick='doDeleteImport(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";

                var tabel = '<tr>';
                tabel += '<td>'+ no +'</td>';
                tabel += '<td>'+ button +'</td>';
                tabel += '<td>'+ (data.customer ? data.customer : '') +'</td>';
                tabel += '<td>'+ (divisi != "" ? divisi[0].divisi_name : '') +'</td>';
                tabel += '<td>'+ (data.part_name ? data.part_name : '') +'</td>';
                tabel += '<td>'+ (data.part_number ? data.part_number : '') +'</td>';
                tabel += '<td>'+ (plant != "" ? data.production : '') +'</td>';
                tabel += '<td>'+ add_date +'</td>';
                tabel += '<td>'+ (unit != "" ? data.unit : '') +'</td>';
                tabel += '<td>'+ (data.wip ? data.wip : '') +'</td>';
                tabel += '<td>'+ (status != "" ? data.status : '') +'</td>';
                tabel += '<td>'+ data.price +'</td>';
                tabel += '<td>'+ effective_price +'</td>';
                tabel += '<td>'+ data.stock +'</td>';
                tabel += '<td>'+ (warehouse != "" ? warehouse[0].warehouse_name : '') +'</td>';
                tabel += '<td style="display:none">'+ customer[0].customer_id +'</td>';
                tabel += '<td style="display:none">'+ (divisi != "" ? divisi[0].divisi_id : '') +'</td>';
                tabel += '<td style="display:none">'+ (unit != "" ? unit[0].unit_id : '') +'</td>';
                tabel += '<td style="display:none">'+ (plant != "" ? plant[0].plant_id : '') +'</td>';
                tabel += '<td style="display:none">'+ (status != "" ? status[0].status_id : '') +'</td>';
                tabel += '<td style="display:none">'+ (warehouse != "" ? warehouse[0].warehouse_id : '') +'</td>';
                tabel += '</tr>';
                $('#tableBodyImport').append(tabel);

            }
            no++;
        });
    }

    function getDataImport(){
        var dataList = [];
        $('#tableBodyImport tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            data = {
                customer_id: this.cells[15].innerHTML,
                divisi_id: this.cells[16].innerHTML,
                part_name: this.cells[4].innerHTML,
                part_number: this.cells[5].innerHTML,
                add_date: this.cells[7].innerHTML,
                unit_id: this.cells[17].innerHTML,
                plant_id: this.cells[18].innerHTML,
                wip: this.cells[9].innerHTML,
                status_id: this.cells[19].innerHTML,
                price: this.cells[11].innerHTML,
                effective_date: this.cells[12].innerHTML,
                stock: this.cells[13].innerHTML,
                warehouse: this.cells[20].innerHTML
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

    function addPrice(data){
        var priceID = data.price_id ? data.price_id : "";
        var effective_date = data.effective_date ? data.effective_date : "";
        var price = data.price ? data.price : "";

        var addPrice = '<tr>';
            addPrice += '<td><input type="hidden" name="price_id[]" value="'+ priceID +'"><input type="date" name="effective_date[]" class="form-control effective_date" value="'+ effective_date +'" required></td>';
            addPrice += '<td><input type="number" name="price[]" class="form-control" value="'+ price +'" required></td>';
            addPrice += '<td><button type="button" onClick="removePrice(this)" class="btn waves-effect waves-light btn-warning btn-icon">&nbsp;<i class="icofont icofont-trash"></i></button></td>';
            addPrice += '</tr>';
        $('#bodyPrice').append(addPrice);

    }

    function removePrice(e){
        $(e).parent().parent().remove();
    }

    function addStock(data){
        var stockID = data.stock_id ? data.stock_id : "";
        var warehouseId = data.warehouse_id ? data.warehouse_id : "";
        var stock = data.stock ? data.stock : "";

        var addStock = '<tr>';
            addStock += '<td><input type="number" readonly name="stock_exist[]" value="'+ stock +'" id="stock_exist_0" class="stock_exist form-control" required></td>';
            addStock += '<td><input type="hidden" name="stock_id[]" value="'+ stockID +'"><input type="number" name="stock[]" class="form-control stock" value="0" required></td>';
            addStock += '<td><select name="warehouse_id[]" class="warehouse_id js-example-placeholder col-sm-12" required>';
            addStock +=         '<option value="">--Select--</option>';
                                @foreach($warehouseList as $ls)
                                    var selected = (warehouseId == '{{ $ls->warehouse_id }}')?"selected":"";
            addStock +=             '<option value="{{ $ls->warehouse_id }}" '+ selected +'>{{ $ls->warehouse_name }}</option>';
                                @endforeach
            addStock += '</select></td>';
            addStock += '</tr>';
        $('#bodyStock').append(addStock);
        loadSelect2();

    }

    function removeStock(e){
        $(e).parent().parent().remove();
    }

</script>
@endsection

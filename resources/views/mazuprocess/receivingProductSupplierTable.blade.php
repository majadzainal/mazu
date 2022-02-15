@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">RECEIVING PRODUCT SUPPLIER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Receiving Product Supplier</a></li>
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
                                    <h5>Receiving Product Supplier</h5>
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
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" btn="add" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add By Manually</button>
                                    @endif
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-form-label">Select periode for load data.</label>
                                        <label class="col-sm-2 col-form-label">Period <span class="text-danger"> *</span></label>
                                        <div class="col-sm-4">
                                            <input type="date" name="start_date" id="start_date" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" name="end_date" id="end_date" class="form-control">
                                        </div>
                                        <button class="col-sm-2 btn btn-primary btn-sm btn-round waves-effect waves-light" onClick="btnLoadDataClick()">Load Data</button>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th>Rec. Product Supplier Number</th>
                                                    <th>No. PO</th>
                                                    <th>No. DO Supplier</th>
                                                    <th>Delivered By</th>
                                                    <th>Received By</th>
                                                    <th>Date Receive</th>
                                                    <th>Action</th>
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
                                    <h5>Receiving Product Supplier Form</h5>
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
                                    <form action="/process/receiving-product/add" method="post" enctype="multipart/form-data" id="recProductForm">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Rec. Product Supplier Number</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="rec_number" id="rec_number" class="form-control" placeholder="auto generate after save" readonly>
                                            </div>
                                        </div>
                                        <input type="hidden" name="rec_prod_supplier_id" id="rec_prod_supplier_id">
                                        <input type="hidden" name="is_manually" id="is_manually">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">No PO<span class="text-danger"> *</span></label>
                                            <div class="col-sm-10" id="select_po_material_id" onchange="poidChange()">
                                                <select name="po_material_id" id="po_material_id" class="select-style col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($poList as $ls)
                                                        <option value="{{ $ls->po_material_id }}">{{ $ls->po_number." | ".$ls->supplier->supplier_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">No. DO Supplier <span class="text-danger"> *</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" name="do_number_supplier" id="do_number_supplier" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Delivered By <span class="text-danger"> *</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" name="delivered_by" id="delivered_by" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Received By <span class="text-danger"> *</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" name="received_by" id="received_by" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Date Received <span class="text-danger"> *</span></label>
                                            <div class="col-sm-5">
                                                <input type="date" name="date_receive" id="date_receive" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="table-responsive">
                                            <table id="receivingTable" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Material</th>
                                                        <th>Warehouse</th>
                                                        <th>Order</th>
                                                        <th>Received</th>
                                                        <th>Remain</th>
                                                        <th>Receive</th>
                                                        <th>Over</th>
                                                        <th>Detail</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyMaterial">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-8"></label>
                                            <div class="col-sm-4" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <button type="button" id="btn-save" class="save btn btn-primary waves-effect waves-light" onClick="saveInit('#recProductForm')">Save</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12" id="edit">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Receiving Part Supplier Form</h5>
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
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Rec. Part Number <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="recpart_number_edit" id="recpart_number_edit" class="form-control" placeholder="auto generate after save" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Period <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="text" name="month_periode_edit" readonly id="month_periode_edit" class="form-control" required>
                                        </div>
                                        <label class="col-form-label">Year <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="text" name="year_periode_edit" readonly id="year_periode_edit" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">No PO<span class="text-danger"> *</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="po_id_edit" readonly id="po_id_edit" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">No. DO Supplier <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="do_number_supplier_edit" readonly id="do_number_supplier_edit" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Delivered By <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="delivered_by_edit" id="delivered_by_edit" readonly class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Received By <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="received_by_edit" id="received_by_edit" readonly class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Date Received <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="date" name="date_receive_edit" id="date_receive_edit" readonly class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">Material</th>
                                                    <th rowspan="2">Warehouse</th>
                                                    <th colspan="5" class="text-center">Qty</th>
                                                    <th class="text-center">Detail</th>
                                                </tr>
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Received</th>
                                                    <th>Remain</th>
                                                    <th>Receive</th>
                                                    <th>Over</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodyMaterialEdit">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group row modal-footer">
                                        <label class="col-sm-8"></label>
                                        <div class="col-sm-4" style="text-align:right;">
                                            <button type="button" class="btn btn-default waves-effect closeFormEdit">Close</button>
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
@include('layouts.footerIn')
<script>
    var dataListPO = [];
    var poList = [];

    $(document).ready(function() {
        var date = new Date();
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

        $("#start_date").val(moment(firstDay).format('YYYY-MM-DD'));
        $("#end_date").val(moment(lastDay).format('YYYY-MM-DD'));

        btnLoadDataClick();

        $("#input").hide();
        $("#edit").hide();
    });

    async function btnLoadDataClick(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        loadData(start_date, end_date);
    }

    function loadData(start_date, end_date){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/receiving-product-supplier/load/'+start_date+'/'+end_date,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        console.log(row);
                        return num.row+1;
                    }
                },
                { "data": "rec_number" },
                { "data": "po_material.po_number" },
                { "data": "do_number_supplier" },
                { "data": "delivered_by" },
                { "data": "received_by" },
                { "data": "date_receive" },
                {
                    "mData": "rec_prod_supplier_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit' manually='0'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.po_material.po_number +" PO number??' data-url='/process/receiving-product-supplier/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }

            ]
        });
    }


    async function return_value(e, data){
        var btn = $(e).attr("btn");
        $("#table").hide();
        document.documentElement.scrollTop = 0;

        if (btn == "edit"){
            $("#edit").show();
            // $("#purchaseForm").attr("action", "/process/purchase-order/update");

            // await fillToForm(data);


        } else {
            $("#input").show();
                $("#recProductForm").trigger("reset");
                $("#recProductForm").attr("action", "/process/receiving-product-supplier/add");
                $(".js-example-placeholder").trigger('change');
        }

    }

    async function fillToForm(data){
        var arrMonth = {!! json_encode(getMonth()) !!};
        var month = arrMonth.find(e => e.month === data.po.month_periode);
        $("#recpart_number_edit").val(data.recpart_number);
        $("#month_periode_edit").val(month.month_name);
        $("#year_periode_edit").val(data.po.year_periode);
        $("#po_id_edit").val(data.po.po_number+' - '+data.po.supplier.supplier_name);
        $("#do_number_supplier_edit").val(data.do_number_supplier);
        $("#delivered_by_edit").val(data.delivered_by);
        $("#received_by_edit").val(data.received_by);
        $("#date_receive_edit").val(data.date_receive);

        $("#bodyMaterialEdit").html('');
        data.recpart_item.forEach(function(part) {
            addMaterialEdit(part)
        });

    }

    async function poidChange(){
        var po_id = $('#po_material_id').val();
        var poList = {!! json_encode($poList) !!};
        var dataPO = poList.find(a => a.po_material_id === po_id);
        $("#bodyMaterial").html('');
        dataPO.items.forEach(function(item) {
            addProduct(item)
        });
    }

    async function addProduct(item){
        var qtyOrder = item.qty_order;
        var qtyRecData = await getData('/process/receiving-product-supplier/get-total-receive/'+item.po_material_item_id);
        var qtyRec = qtyRecData.length >= 1 ? qtyRecData[0].qty_in : 0;
        var qtyRemain = qtyOrder - qtyRec;
        var product = '<tr>';
        product +='<td>';
        product +='<input type="hidden" name="po_material_item_id[]" class="po_material_item_id" value="'+item.po_material_item_id+'">';
        product +='<input type="hidden" name="product_supplier_id[]" class="product_supplier_id" value="'+item.product_supplier_id+'">';
        product +='<label class="col-form-label">'+item.product_supplier.product_name+'</label>';
        product +='</td>';
        product +='<td>';
        product +='<input type="hidden" name="warehouse_id[]" class="warehouse_id form-control" value="'+item.product_supplier.stock_warehouse.warehouse.warehouse_id+'">';
        product +='<label class="col-form-label">'+item.product_supplier.stock_warehouse.warehouse.warehouse_name+'</label>';
        product +='</td>';
        product +='<td>';
        product +='<input type="number" readonly name="qty[]" class="qty form-control" value="'+qtyOrder+'"></td>';
        product +='</td>';
        product +='<td>';
        product +='<input type="number" readonly name="qty_received[]" class="qty_received form-control" value="'+qtyRec+'"></td>';
        product +='</td>';
        product +='<td>';
        product +='<input type="number" readonly name="qty_remain[]" class="qty_remain form-control" value="'+qtyRemain+'"></td>';
        product +='</td>';
        product +='<td>';
        product +='<input type="number" name="qty_in[]" class="qty_in form-control" onChange="qtyChange(this)" value="0"></td>';
        product +='</td>';
        product +='<td>';
        product +='<input type="number" readonly name="qty_over[]" class="qty_over form-control" value="0"></td>';
        product +='</td>';
        product += '<td>';
        product += '<button type="button" class="btn waves-effect waves-light btn-info btn-icon" data-toggle="modal" data-target="#large-Modal" btn="add-label" onClick="bntDetailReceiving(this)"><i class="icofont icofont-edit"></i></button>'
        product +='</td>';
        product += '</tr>';

        $('#bodyMaterial').append(product);

        loadSelect2();
    }

    async function addMaterialEdit(item){
        var qtyOrder = item.po_item.order;
        var material = '<tr>';
        material +='<td>';
        material +='<label class="col-form-label">'+item.po_item.part_supplier.part_number+' - '+item.po_item.part_supplier.part_name+'</label>';
        material +='</td>';
        material +='<td>';
        material +='<input type="text" readonly name="warehouse[]" class="warehouse form-control" value="'+item.warehouse.warehouse_name+'"></td>';
        material += '<select name="warehouse_id[]" class="js-example-placeholder col-sm-12" required>';

        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty[]" class="qty form-control" value="'+qtyOrder+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_received[]" class="qty_received form-control" value="'+(parseInt(qtyOrder) - (parseInt(item.qty_remain) + parseInt(item.qty_in) ))+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_remain[]" class="qty_remain form-control" value="'+(parseInt(item.qty_remain) + parseInt(item.qty_in))+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" name="qty_in[]" class="qty_in form-control" onChange="qtyChange(this)" value="'+item.qty_in+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_over[]" class="qty_over form-control" value="'+item.qty_over+'"></td>';
        material +='</td>';
        material += '<td>';
        material += '<button type="button" class="btn waves-effect waves-light btn-info btn-icon" data-toggle="modal" data-target="#large-Modal" btn="edit-label" onClick="bntDetailReceiving(this)"><i class="icofont icofont-edit"></i></button>'
        material +='</td>';
        material += '</tr>';

        $('#bodyMaterialEdit').append(material);

        loadSelect2();
    }

    function qtyChange(e){
        var qtyIn = parseInt($(e).val());
        var qtyOrder = parseInt($(e).parent().parent().find(".qty").val());
        var qtyReceived = parseInt($(e).parent().parent().find(".qty_received").val());
        var countQty = (parseInt(qtyIn) + parseInt(qtyReceived) - parseInt(qtyOrder));
        var qtyOver = countQty > 0 ? countQty : 0;
        $(e).parent().parent().find(".qty_over").val(qtyOver);

    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })
    $('.closeFormEdit').click(function(e) {
        $("#edit").hide();
        $("#table").show();
    })

    function saveInit(form){
        if ($('input[name="po_material_item_id[]"]').length > 0){
            saveData(form, function() {
                btnLoadDataClick();
                loadSelect2();
                $('#bodyMaterial').html('');
                $("#input").hide();
                $("#table").show();
            });
        } else {
            swal('Error', 'Material items cannot be empty', 'error');
        }
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            btnLoadDataClick();
        });
    }

    function saveMaterial(){

        if (!$("#materialForm")[0].checkValidity()) {
            $("#materialForm")[0].reportValidity();
        } else {
            addMaterial();
        }
    }

    function return_material(e, material){
        $("#materialForm").trigger("reset");

        if($("#supplier_id").val() == "" || $("#month_periode").val() == "" || $("#year_periode").val() == ""){
            swal('Error', 'supplier and period cannot be empty', 'error');
        } else {
            $('#large-Modal').modal('show');
            if (material == ""){

            } else {
                material.forEach(function(part) {
                    $("#"+part.name).val(part.value);
                });
                $("#part_id").trigger('change');
            }
        }
    }



</script>
@endsection

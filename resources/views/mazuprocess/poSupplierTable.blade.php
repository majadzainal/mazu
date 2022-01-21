@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">PURCHASE ORDER SUPPLIER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Purchase Order Supplier</a></li>
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
                                    <h5>Purchase Order Supplier</h5>
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
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" btn="add" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add</button>
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
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        {{-- <table id="salesOrderTable" class="table table-striped table-bordered nowrap"> --}}
                                        <table id="poSupplierTable" class="table table-striped table-bordered nowrap dt-responsive width-100">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Action</th>
                                                    <th>Purchase Order No.</th>
                                                    <th>Supplier</th>
                                                    <th>PO. Date</th>
                                                    <th>Due Date</th>
                                                    <th>Total Price</th>
                                                    <th>Total Price After Discount</th>
                                                    <th>Description</th>
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
                                    <h5>Purchase Order Supplier</h5>
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
                                    <form action="/process/purchase-order-supplier/add" method="post" enctype="multipart/form-data" id="poSupplierForm">
                                        @csrf
                                        <input type="hidden" name="po_supplier_id" id="po_supplier_id">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Purchase Order Supplier Number </label>
                                            <div class="col-sm-5">
                                                <input type="input" readonly name="po_number" id="po_number" class="form-control">
                                            </div>
                                            <label class="col-sm-5 col-form-label">Auto generate after save as process</label>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Supplier <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="customer_id" id="customer_id" onchange="customerChange()" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($supplierList as $ls)
                                                        <option value="{{ $ls->supplier_id }}">{{ $ls->supplier_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PO. Date <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="date" name="po_date" id="po_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Due Date <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="date" name="due_date" id="due_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Description <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="description" id="description" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Description <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" onClick="addProduct()"><i class="icofont icofont-plus-circle"></i> Add Product</button>
                                            </div>
                                        </div>

                                        <div class="dt-responsive table-responsive">
                                            <table id="poItemTableList" width="100%" class="display table table-bordered table-striped nowrap">
                                                <thead>
                                                    <tr>
                                                        <th width="60%">Product Name</th>
                                                        <th width="40%">Qty Order</th>
                                                        <th>Unit</th>
                                                        <th>Product Price (Rp.)</th>
                                                        <th>Percent Discount (%)</th>
                                                        <th>Total Price (Rp.)</th>
                                                        <th>Total Discount (Rp.)</th>
                                                        <th>Total Price After Discount (Rp.)</th>
                                                        <th width="80%">Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyProduct">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <input type="hidden" name="is_process" id="is_process">
                                                <input type="hidden" name="is_draft" id="is_draft">
                                                <button type="button" id="btnCancelSO" class="btn btn-danger waves-effect waves-light" onClick="cancelInit('#btnCancelSO')">Batalkan Process</button>
                                                <button type="button" class="btn btn-secondary waves-effect waves-light" onClick="saveInit('#poSupplierForm', 0)">Save as draft</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#poSupplierForm', 1)">Save</button>
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
    @php
        $monthList = getMonth();
    @endphp
</div>
<div id="styleSelector"></div>
<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Select Product</h4>
                <button type="button" class="close" id="closeAddProduct" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="dt-responsive table-responsive">

                    {{-- <table id="soItemTableList" class="table table-striped table-bordered"> --}}
                    <table id="productTableList" width="100%" class="display table table-bordered table-striped nowrap">
                    {{-- <table id="soItemTableList" class="table table-striped table-bordered nowrap dt-responsive width-100"> --}}
                        <thead>
                            <tr>
                                <th width="2%">No</th>
                                <th width="30%">Foto</th>
                                <th>Product Code</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        var d = new Date();
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        // loadData(start_date, end_date);

        loadProduct();
        $("#input").hide();
    } );

    function loadData(start_date, end_date){
        $('#poSupplierTable').DataTable().destroy();
        var table = $('#poSupplierTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/purchase-order-supplier/load/'+start_date+'/'+end_date,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "po_supplier_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.po_number +"("+ row.supplier.supplier_name+") purchase order supplier??' data-url='/process/purchase-order-supplier/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "po_number" },
                {  "mRender": function (data, type, row, num) {
                        var supplierStatus = "";
                        supplierStatus += row.supplier.supplier_name;
                        supplierStatus += row.is_process === 1 ? "<span class='btn-success btn-sm' > -procesed- </span>" : "";
                        supplierStatus += row.is_draft === 1 ? "<span class='btn-secondary btn-sm' > -draft- </span>" : "";
                        supplierStatus += row.is_void === 1 ? "<span class='btn-danger btn-sm' > -void- </span>" : "";
                        return supplierStatus;
                    }
                },
                { "data": "po_date" },
                { "data": "due_date" },
                { "data": "total_price" },
                { "data": "total_price_after_disount" },
                { "data": "description" },
            ]
        });
    }

    function loadProduct(){
        $('#productTableList').DataTable().destroy();
        var table = $('#productTableList').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/master/purchase-order-supplier/load-roduct',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var productImage = '{{ asset ("/assets/files/assets/images/no-image.jpg") }}';
                        var img = "";
                        img += '<div class="row">';
                        img += '<img class="col-sm-12" src="'+productImage+'"/>';
                        img += '</div>';
                        return img;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var btnAdd = "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='addProductToPO("+ JSON.stringify(row) +")'>&nbsp;<i class='icofont icofont-plus'></i> </button>";
                        return btnAdd;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var productCode = row.category.category_code +"-"+row.product_code;
                        return productCode;
                    }
                },
                { "data": "product_name" },
                { "data": "product_description" },
            ]
        });
    }

    function addProductToPO(data){

        var addProduct = '<tr>';

            addProduct += '<td>';
            addProduct += '<input type="text" value="'+ data.product_name +'" readonly name="product_name[]"  class="product_name form-control" style="width:300px" required>';
            addProduct += '<input type="hidden" value="'+ data.product_id +'" readonly name="product_id[]"  class="product_id form-control" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" onChange="countCost(this)" value="0" name="qty_order[]"  class="qty_order form-control" style="width:150px; text-align:center;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += data.unit.unit_name;
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" onChange="countCost(this)" value="0" name="price[]"  class="price form-control" style="width:150px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" onChange="countCost(this)" value="0" name="percent_discount[]"  class="percent_discount form-control" style="width:150px; text-align:center;">';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly value="0" name="total_price[]"  class="total_price form-control" style="width:150px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly value="0" name="total_discount[]"  class="total_discount form-control" style="width:150px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly value="0" name="total_price_after_disount[]"  class="total_price_after_disount form-control" style="width:200px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="text" value="" name="description[]"  class="description form-control" style="width:500px">';
            addProduct += '</td>';

            addProduct += '</tr>';

        $("#bodyProduct").append(addProduct);

        $("#closeAddProduct").click();
    }

    function countCost(e){
        var qtyOrder = $(e).parent().parent().find(".qty_order").val();
        var price = $(e).parent().parent().find(".price").val();
        var percentDiscount = $(e).parent().parent().find(".percent_discount").val();
        var totalPrice = parseInt(qtyOrder) * parseInt(price);
        var totalDiscount = parseFloat(totalPrice) * (parseFloat(percentDiscount) / 100 );
        var totalPriceAfterDiscount = parseFloat(totalPrice) - parseFloat(totalDiscount);
        var partPrice = $(e).parent().parent().find(".price").val();
        $(e).parent().parent().find(".total_price").val(totalPrice);
        $(e).parent().parent().find(".total_discount").val(totalDiscount);
        $(e).parent().parent().find(".total_price_after_disount").val(totalPriceAfterDiscount);
    }

    async function return_value(e, data){
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            // $("#salesOrderForm").attr("action", "/process/sales-order/update");
            // $("#defaultModalLabel").text("Edit Sales Order Customer")
            // $("#load_part_customer").attr("disabled", true)
            // await getData('/process/sales-order/get/'+data.sales_order_id).then(function(result){
            //     fillToForm(result);
            // });
        } else {
            // var d = new Date();
            // var month = d.getMonth()+1;
            // var year = d.getFullYear();
            // $("#salesOrderForm").trigger("reset");
            // $("#load_part_customer").attr("disabled", false)
            // $("#customer_id").trigger('change');
            // $('#month_periode').val(month);
            // $('#year_periode').val(year);
            // $("#month_periode").trigger('change');
            // $("#year_periode").trigger('change');
            // $("#btnCancelSO").hide();
            // $("#salesOrderForm").attr("action", "/process/sales-order/add");
            // $("#defaultModalLabel").text("Add Sales Order Customer");
            // var table = $('#soItemTableList').DataTable();
            // table.clear().draw();

        }
        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;
    }

    function addProduct(){

    }

    // function fillToForm(data){
    //     $("#sales_order_id").val(data.sales_order_id);
    //     $("#so_number").val(data.so_number);
    //     $("#customer_id").val(data.customer_id);
    //     $("#customer_id").trigger('change');
    //     $("#month_periode").val(data.month_periode);
    //     $("#month_periode").trigger('change');
    //     $("#year_periode").val(data.year_periode);
    //     $("#year_periode").trigger('change');
    //     $("#so_date").val(data.so_date);
    //     $("#po_number_customer").val(data.po_number_customer);
    //     $("#po_date_customer").val(data.po_date_customer);
    //     $("#po_date_customer").val(data.po_date_customer);
    //     loadDataSoItem(data.customer_id, data.so_items);
    //     if(data.so_number){
    //         $("#btnCancelSO").show();
    //         $("#btnCancelSO").attr("data-confirm", "Are you sure|want to cancel sales order "+data.so_number+"?");
    //         $("#btnCancelSO").attr("data-url", "/process/sales-order/cancel/"+data.sales_order_id);

    //     }else{
    //         $("#btnCancelSO").hide();
    //     }
    // }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form, is_process){
        $("#is_process").val(is_process === 1 ? 1 : 0);
        $("#is_draft").val(is_process === 0 ? 1 : 0);
        saveDataModal(form, '.closeForm', function() {
            loadSelect2();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {

        });
    }

    // function cancelInit(e){
    //     deleteConfirm(e, function() {
    //         periodeChangeFilter();
    //         $('.closeForm').click();
    //     });
    // }
</script>
@endsection

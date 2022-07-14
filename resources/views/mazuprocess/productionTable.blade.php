@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">PRODUCTION TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Production</a></li>
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
                                    <h5>Production</h5>
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
                                        <button type="button" class="btn btn-primary btn-sm btn-round waves-effect waves-light" btn="add" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add</button>
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
                                        {{-- <table id="salesOrderTable" class="table table-striped table-bordered nowrap"> --}}
                                        <table id="poSupplierTable" class="table table-striped table-bordered nowrap dt-responsive width-100">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Action</th>
                                                    <th>Production No.</th>
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
                                    <h5>Production Supplier</h5>
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
                                    <form action="/process/production/add" method="post" enctype="multipart/form-data" id="productionForm">
                                        @csrf
                                        <input type="hidden" name="production_id" id="production_id">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Production Number </label>
                                            <div class="col-sm-5">
                                                <input type="input" readonly name="po_number" id="po_number" class="form-control">
                                            </div>
                                            <label class="col-sm-5 col-form-label">Auto generate after save as process</label>
                                        </div>
                                        {{-- <div class="form-group row" id="input_po_customer_id">
                                            <label class="col-sm-2 col-form-label">PO. Customer <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="text" readonly name="po_customer_id_edit" id="po_customer_id_edit" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row" id="select_po_customer_id">
                                            <label class="col-sm-2 col-form-label">PO. Customer <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="po_customer_id" id="po_customer_id" onchange="changePOCustomer()" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>

                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Supplier <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="supplier_id" id="supplier_id" class="js-example-placeholder col-sm-12" required>
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
                                                <input type="date" name="po_date" id="po_date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Due Date <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="date" name="due_date" id="due_date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Description</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="description" id="description" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-10">
                                                <button type="button" class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal"><i class="icofont icofont-plus-circle"></i> Add Product</button>
                                            </div>
                                        </div>
                                        <div class="dt-responsive table-responsive">
                                            <table id="productionItemTableList" width="100%" class="display table table-bordered table-striped nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
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
                                                <tbody id="bodyProduction">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Total </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp.</label>
                                                    </span>
                                                    <input type="text" readonly name="total_price-form" id="total_price-form" class="form-control currency text-right" placeholder="Total Price">
                                                    <input type="hidden" readonly name="total_price" id="total_price" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Discount </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">%</label>
                                                    </span>
                                                    <input type="text" value="0" name="percent_discount" id="percent_discount" oninput="inputDiscPercent()" class="form-control text-right" placeholder="Discount (%)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp.</label>
                                                    </span>
                                                    <input type="text" readonly value="0" readonly name="discount-form" id="discount-form" class="form-control currency text-right" placeholder="Discount (Rp.)">
                                                    <input type="hidden" readonly value="0" name="discount" id="discount" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Total After Discount </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" readonly name="total_price_after_discount-form" id="total_price_after_discount-form" class="form-control currency text-right" placeholder="Total After Discount">
                                                    <input type="hidden" readonly name="total_price_after_discount" id="total_price_after_discount" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-8 col-form-label text-right">
                                                <div class="checkbox-color checkbox-primary">
                                                    <input name="is_ppn"  id="is_ppn" type="hidden">
                                                    <input name="is_ppnCHK" id="is_ppnCHK" oninput="oninputPPN()" type="checkbox" >
                                                    <label for="is_ppnCHK">
                                                        <strong>PPN 11%</strong>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" readonly name="ppn-form" id="ppn-form" class="form-control currency text-right" placeholder="PPN 11%">
                                                    <input type="hidden" readonly name="ppn" id="ppn" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Grand Total </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" readonly name="grand_total-form" id="grand_total-form" class="form-control currency text-right" placeholder="Grand Total">
                                                    <input type="hidden" readonly name="grand_total" id="grand_total" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <input type="hidden" name="is_process" id="is_process">
                                                <input type="hidden" name="is_draft" id="is_draft">
                                                <button type="button" id="btnCancelPO" class="btn btn-danger waves-effect waves-light" onClick="cancelInit('#btnCancelPO')">Batalkan Process</button>
                                                <button type="button" class="btn btn-secondary waves-effect waves-light" onClick="saveInit('#productionForm', 0)">Save as draft</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#productionForm', 1)">Save</button>
                                            </div>
                                        </div>
                                        {{-- <div class="bg-secondary p-4 text-white">
                                            <div class="form-group row">
                                                <label class="col-sm-12 col-form-label">
                                                    <strong>Product PO Customer</strong>
                                                </label>
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
                                            <div class="form-group row">
                                                <label class="col-sm-8 col-form-label text-right"><strong>Total </strong> <span class="text-danger">*</span></label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-prepend" id="basic-addon2">
                                                            <label class="input-group-text">Rp.</label>
                                                        </span>
                                                        <input type="text" readonly name="total_price-form_po" id="total_price-form_po" class="form-control currency text-right" placeholder="Total Price">
                                                        <input type="hidden" readonly name="total_price_po" id="total_price_po" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-8 col-form-label text-right"><strong>Discount </strong> <span class="text-danger">*</span></label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-prepend" id="basic-addon2">
                                                            <label class="input-group-text">%</label>
                                                        </span>
                                                        <input type="text" value="0" name="percent_discount_po" id="percent_discount_po" class="form-control text-right" placeholder="Discount (%)">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-8 col-form-label text-right"></label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-prepend" id="basic-addon2">
                                                            <label class="input-group-text">Rp.</label>
                                                        </span>
                                                        <input type="text" readonly value="0" readonly name="discount-form_po" id="discount-form_po" class="form-control currency text-right" placeholder="Discount (Rp.)">
                                                        <input type="hidden" readonly value="0" name="discount_po" id="discount_po" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-8 col-form-label text-right"><strong>Total After Discount </strong> <span class="text-danger">*</span></label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-prepend" id="basic-addon2">
                                                            <label class="input-group-text">Rp. </label>
                                                        </span>
                                                        <input type="text" readonly name="total_price_after_discount-form_po" id="total_price_after_discount-form_po" class="form-control currency text-right" placeholder="Total After Discount">
                                                        <input type="hidden" readonly name="total_price_after_discount_po" id="total_price_after_discount_po" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-8 col-form-label text-right">
                                                    <div class="checkbox-color checkbox-primary">
                                                        <input name="is_ppn_po"  disabled="disabled"  id="is_ppn_po" type="hidden">
                                                        <input name="is_ppnCHK_po"  disabled="disabled" id="is_ppnCHK_po" type="checkbox" >
                                                        <label for="is_ppnCHK_po">
                                                            <strong>PPN 10%</strong>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-prepend" id="basic-addon2">
                                                            <label class="input-group-text">Rp. </label>
                                                        </span>
                                                        <input type="text" readonly name="ppn-form_po" id="ppn-form_po" class="form-control currency text-right" placeholder="PPN 10%">
                                                        <input type="hidden" readonly name="ppn_po" id="ppn_po" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-8 col-form-label text-right"><strong>Grand Total </strong> <span class="text-danger">*</span></label>
                                                <div class="col-sm-4">
                                                    <div class="input-group">
                                                        <span class="input-group-prepend" id="basic-addon2">
                                                            <label class="input-group-text">Rp. </label>
                                                        </span>
                                                        <input type="text" readonly name="grand_total-form_po" id="grand_total-form_po" class="form-control currency text-right" placeholder="Grand Total">
                                                        <input type="hidden" readonly name="grand_total_po" id="grand_total_po" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div> --}}
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
                <h4 class="modal-title" id="defaultModalLabel" >Select Product Supplier</h4>
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
        var date = new Date();
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

        $("#start_date").val(moment(firstDay).format('YYYY-MM-DD'));
        $("#end_date").val(moment(lastDay).format('YYYY-MM-DD'));

        btnLoadDataClick();

        loadProductSupplier();
        $("#input").hide();
    } );
    // function changePOCustomer(){
    //     var po_customer_id = $("#po_customer_id").val();

    //     if(po_customer_id == undefined) return;

    //     var po = poCustArr.find(a => a.po_customer_id === po_customer_id);
    //     po.items.forEach(item => {
    //         addProductToPO(item.product, item);
    //     });
    //     calculateTotal_po();
    // }
    function btnLoadDataClick(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        loadData(start_date, end_date);
    }

    function loadData(start_date, end_date){
        $('#poSupplierTable').DataTable().destroy();
        var table = $('#poSupplierTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/production/load/'+start_date+'/'+end_date,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "production_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.po_number +"("+ row.supplier.supplier_name+") purchase order supplier??' data-url='/process/production/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
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
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + row.total_price.toLocaleString();
                    }
                },
                { "data": "total_price_after_discount" },
                { "data": "description" },
            ]
        });
    }

    function loadProductSupplier(){
        $('#productTableList').DataTable().destroy();
        var table = $('#productTableList').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/production/load-product-supplier',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                    var productImage = '{{ asset ("/uploads") }}' + "/" + row.images;
                        var img = "";
                        img += '<div class="row">';
                        img += '<img class="text-center" style="width:200px;" src="'+productImage+'"/>';
                        img += '</div>';
                        return img;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var item = "";
                        var btnAdd = "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='addProductToProduction("+ JSON.stringify(row) +", "+item+")'>&nbsp;<i class='icofont icofont-plus'></i> </button>";
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

    function addProductToProduction(data, item){
        console.log(data);

        var qty_order = 0;
        var price = data.price;
        var percent_discount = data.is_service !== 1 ? 100 : 0;
        var total_price = 0;
        var total_discount = 0;
        var total_price_after_discount = 0;
        var description = "";
        if(item != undefined){
            qty_order = item.qty_order != undefined ? item.qty_order : qty_order;
            price = item.price != undefined ? item.price : price;
            percent_discount = item.percent_discount != undefined ? item.percent_discount : percent_discount;
            total_price = item.total_price != undefined ? item.total_price : total_price;
            total_discount = item.total_discount != undefined ? item.total_discount : total_discount;
            total_price_after_discount = item.total_price_after_discount != undefined ? item.total_price_after_discount : total_price_after_discount;
            description = item.description != undefined ? item.description : description;
        }


        var addProduct = '<tr>';

            addProduct += '<td>';
            addProduct += "<button type='button' class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete purchase order item ??' onClick='deleteInitPOItem(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
            addProduct += '</td>';
            addProduct += '<td>';
            addProduct += '<input type="text" value="'+ data.product_name +'" readonly name="product_name[]"  class="product_name form-control" style="width:300px" required>';
            addProduct += '<input type="hidden" value="'+ data.product_supplier_id +'" readonly name="product_supplier_id[]"  class="product_supplier_id form-control" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" onInput="countCost(this)" value="'+qty_order+'" name="qty_order_item[]"  class="qty_order_item form-control" style="width:150px; text-align:center;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += data.unit.unit_name;
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" onInput="countCost(this)" value="'+price+'" name="price_item[]"  class="price_item form-control" style="width:150px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" onInput="countCost(this)" value="'+percent_discount+'" name="percent_discount_item[]"  class="percent_discount_item form-control" style="width:150px; text-align:center;">';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly value="'+total_price+'" name="total_price_item[]"  class="total_price_item form-control" style="width:150px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly value="'+total_discount+'" name="total_discount_item[]"  class="total_discount_item form-control" style="width:150px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly value="'+total_price_after_discount+'" name="total_price_after_discount_item[]"  class="total_price_after_discount_item form-control" style="width:200px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="text" readonly value="'+description+'" name="description_item[]"  class="description_item form-control" style="width:500px">';
            addProduct += '</td>';

            addProduct += '</tr>';

        $("#bodyProduction").append(addProduct);

        $("#closeAddProduct").click();
        calculateTotal();
    }
    function addProductToPO(data, item){

        var qty_order = 0;
        var price = 0;
        var percent_discount = 0;
        var total_price = 0;
        var total_discount = 0
        var total_price_after_discount = 0;
        var description = "";
        if(item != undefined){
            qty_order = item.qty_order != undefined ? item.qty_order : qty_order;
            price = item.price != undefined ? item.price : price;
            percent_discount = item.percent_discount != undefined ? item.percent_discount : percent_discount;
            total_price = item.total_price != undefined ? item.total_price : total_price;
            total_discount = item.total_discount != undefined ? item.total_discount : total_discount;
            total_price_after_discount = item.total_price_after_discount != undefined ? item.total_price_after_discount : total_price_after_discount;
            description = item.description != undefined ? item.description : description;
        }


        var addProduct = '<tr>';
            addProduct += '<td>';
            addProduct += '<input type="text" value="'+ data.product_name +'" readonly name="product_name_po[]"  class="product_name_po form-control" style="width:300px" required>';
            addProduct += '<input type="hidden" value="'+ data.product_id +'" readonly name="product_id_po[]"  class="product_id_po form-control" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly onInput="countCost_po(this)" value="'+qty_order+'" name="qty_order_item_po[]"  class="qty_order_item_po form-control" style="width:150px; text-align:center;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += data.unit.unit_name;
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly onInput="countCost_po(this)" value="'+price+'" name="price_item_po[]"  class="price_item_po form-control" style="width:150px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly onInput="countCost_po(this)" value="'+percent_discount+'" name="percent_discount_item_po[]"  class="percent_discount_item_po form-control" style="width:150px; text-align:center;">';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly value="'+total_price+'" name="total_price_item_po[]"  class="total_price_item_po form-control" style="width:150px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly value="'+total_discount+'" name="total_discount_item_po[]"  class="total_discount_item_po form-control" style="width:150px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" readonly value="'+total_price_after_discount+'" name="total_price_after_discount_item_po[]"  class="total_price_after_discount_item_po form-control" style="width:200px; text-align:right;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="text" readonly value="'+description+'" name="description_item_po[]"  class="description_item_po form-control" style="width:500px">';
            addProduct += '</td>';

            addProduct += '</tr>';

        $("#bodyProduct").append(addProduct);

        $("#closeAddProduct").click();
    }

    function countCost_po(e){
        var qtyOrder = $(e).parent().parent().find(".qty_order_item_po").val();
        var price = $(e).parent().parent().find(".price_item_po").val();
        var percentDiscount = $(e).parent().parent().find(".percent_discount_item_po").val();
        var totalPrice = parseInt(qtyOrder) * parseInt(price);
        var totalDiscount = parseFloat(totalPrice) * (parseFloat(percentDiscount) / 100 );
        var totalPriceAfterDiscount = parseFloat(totalPrice) - parseFloat(totalDiscount);
        var partPrice = $(e).parent().parent().find(".price_item_po").val();
        $(e).parent().parent().find(".total_price_item_po").val(totalPrice);
        $(e).parent().parent().find(".total_discount_item_po").val(totalDiscount);
        $(e).parent().parent().find(".total_price_after_discount_item_po").val(totalPriceAfterDiscount);
        calculateTotal_po();
    }
    function countCost(e){
        var qtyOrder = $(e).parent().parent().find(".qty_order_item").val();
        var price = $(e).parent().parent().find(".price_item").val();
        var percentDiscount = $(e).parent().parent().find(".percent_discount_item").val();
        var totalPrice = parseInt(qtyOrder) * parseInt(price);
        var totalDiscount = parseFloat(totalPrice) * (parseFloat(percentDiscount) / 100 );
        var totalPriceAfterDiscount = parseFloat(totalPrice) - parseFloat(totalDiscount);
        var partPrice = $(e).parent().parent().find(".price_item").val();
        $(e).parent().parent().find(".total_price_item").val(totalPrice);
        $(e).parent().parent().find(".total_discount_item").val(totalDiscount);
        $(e).parent().parent().find(".total_price_after_discount_item").val(totalPriceAfterDiscount);
        calculateTotal();
    }

    function calculateTotal_po(){
        var totalPrice = parseFloat(0);
        var discountPercent = parseFloat(0);
        var discountPrice = parseFloat(0);
        var totalPriceAfterDiscount = parseFloat(0);
        var ppnPrice = parseFloat(0);
        var grandTotal = parseFloat(0);
        var is_ppn = $("#is_ppnCHK_po").prop("checked");


        $('#bodyProduct tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var total = $(this).find(".total_price_after_discount_item_po").val();
            totalPrice += parseFloat(total);
        });

        var discountPercentForm = $("#percent_discount_po").val();
        discountPercent = parseFloat(discountPercentForm.split(",").join(""));
        discountPrice = (parseFloat(totalPrice) * (parseFloat(discountPercent) / 100 ));
        totalPriceAfterDiscount = (parseFloat(totalPrice) - parseFloat(discountPrice));

        $("#total_price-form_po").val(totalPrice);
        $("#total_price-form_po").trigger("focusout");
        $("#total_price_po").val(totalPrice);

        $("#discount-form_po").val(discountPrice);
        $("#discount-form_po").trigger("focusout");
        $("#discount_po").val(discountPrice);

        $("#total_price_after_discount-form_po").val(totalPriceAfterDiscount);
        $("#total_price_after_discount-form_po").trigger("focusout");
        $("#total_price_after_discount_po").val(totalPriceAfterDiscount);

        if(is_ppn){
            ppnPrice = (parseFloat(totalPriceAfterDiscount) * {{ getPPN() }});
        }

        grandTotal = (totalPriceAfterDiscount + ppnPrice);

        $("#ppn-form_po").val(ppnPrice);
        $("#ppn-form_po").trigger("focusout");
        $("#ppn").val(ppnPrice);

        $("#grand_total-form_po").val(grandTotal);
        $("#grand_total-form_po").trigger("focusout");
        $("#grand_total_po").val(grandTotal);
    }
    function calculateTotal(){
        var totalPrice = parseFloat(0);
        var discountPercent = parseFloat(0);
        var discountPrice = parseFloat(0);
        var totalPriceAfterDiscount = parseFloat(0);
        var ppnPrice = parseFloat(0);
        var grandTotal = parseFloat(0);
        var is_ppn = $("#is_ppnCHK").prop("checked");


        $('#bodyProduction tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var total = $(this).find(".total_price_after_discount_item").val();
            totalPrice += parseFloat(total);
        });

        var discountPercentForm = $("#percent_discount").val();
        discountPercent = parseFloat(discountPercentForm.split(",").join(""));
        discountPrice = (parseFloat(totalPrice) * (parseFloat(discountPercent) / 100 ));
        totalPriceAfterDiscount = (parseFloat(totalPrice) - parseFloat(discountPrice));

        $("#total_price-form").val(totalPrice);
        $("#total_price-form").trigger("focusout");
        $("#total_price").val(totalPrice);

        $("#discount-form").val(discountPrice);
        $("#discount-form").trigger("focusout");
        $("#discount").val(discountPrice);

        $("#total_price_after_discount-form").val(totalPriceAfterDiscount);
        $("#total_price_after_discount-form").trigger("focusout");
        $("#total_price_after_discount").val(totalPriceAfterDiscount);

        if(is_ppn){
            ppnPrice = (parseFloat(totalPriceAfterDiscount) * (10 / 100 ));
        }

        grandTotal = (totalPriceAfterDiscount + ppnPrice);

        $("#ppn-form").val(ppnPrice);
        $("#ppn-form").trigger("focusout");
        $("#ppn").val(ppnPrice);

        $("#grand_total-form").val(grandTotal);
        $("#grand_total-form").trigger("focusout");
        $("#grand_total").val(grandTotal);
    }
    function inputDiscPercent(){
        calculateTotal();
    }

    function oninputPPN(){
        calculateTotal();
    }

    async function return_value(e, data){
        $("#bodyProduct").html("");
        $("#bodyProduction").html("");
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            console.log(data);
            $("#productionForm").attr("action", "/process/production/update");
            $("#production_id").val(data.production_id);
            var poNumber = "";
            poNumber = data.po_number !== null ? data.po_number : "";
            $("#po_number").val(poNumber);
            $("#po_date").val(data.po_date);
            $("#due_date").val(data.due_date);
            var poCustNumber = data.po_customer !== null ? data.po_customer.po_number : "";
            var poCustName = data.po_customer !== null ? data.po_customer.customer.customer_name : "";
            $("#po_customer_id_edit").val(poCustNumber+" - "+poCustName);
            var poCustid = data.po_customer_id !== null ? data.po_customer_id : "";
            $("#po_customer_id").val(poCustid);
            $("#supplier_id").val(data.supplier_id);
            $("#description").val(data.description);

            $("#total_price-form").val(data.total_price);
            $("#total_price").val(data.total_price);

            $("#percent_discount").val(data.percent_discount);

            var discountPrice = (parseInt(data.total_price) - parseInt(data.total_price_after_discount));
            $("#discount-form").val(discountPrice);
            $("#discount").val(discountPrice);

            $("#total_price_after_discount-form").val(data.total_price_after_discount);
            $("#total_price_after_discount").val(data.total_price_after_discount);

            $("#is_ppnCHK").prop('checked', parseInt(data.ppn) > 0 ? true : false);


            $("#ppn-form").val(data.ppn);

            $("#ppn").val(data.ppn);

            $("#grand_total-form").val(data.grand_total);
            $("#grand_total").val(data.grand_total);

            data.items.forEach(item => {
                addProductToProduction(item.product, item);
            });

            $("#po_customer_id").trigger("change");
            $("#supplier_id").trigger("change");
            $("#total_price-form").trigger("focusout");
            $("#discount-form").trigger("focusout");
            $("#total_price_after_discount-form").trigger("focusout");
            $("#ppn-form").trigger("focusout");
            $("#grand_total-form").trigger("focusout");


            $("#input_po_customer_id").show();
            $("#select_po_customer_id").hide();

        } else {
            $("#productionForm").trigger("reset");
            $("#supplier_id").trigger('change');

            $("#input_po_customer_id").hide();
            $("#select_po_customer_id").show();
            $("#btnCancelPO").hide();
            $("#productionForm").attr("action", "/process/production/add");

        }
        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;
    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form, is_process){
        $("#is_process").val(is_process === 1 ? 1 : 0);
        $("#is_draft").val(is_process === 0 ? 1 : 0);
        saveDataModal(form, '.closeForm', function() {
            btnLoadDataClick();
            loadSelect2();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            btnLoadDataClick();
        });
    }

    function deleteInitPOItem(e){
        confirmDeleteRow(e, function(){
            var row = e.parentNode.parentNode;
            row.parentNode.removeChild(row);
        });
    }

    function confirmDeleteRow(e, callback){
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
</script>
@endsection

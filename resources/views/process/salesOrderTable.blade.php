@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">SALES ORDER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Sales Order</a></li>
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
                                    <h5>Sales Order</h5>
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
                                    <div class="form-group row">
                                        <label class="col-sm-12 col-form-label">Select periode for load data.</label>
                                        <label class="col-sm-2 col-form-label">Period <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <select name="month_periode_filter" id="month_periode_filter" onchange="periodeChangeFilter()" class="js-example-placeholder col-sm-12" required>
                                                <option value="">--Select--</option>
                                                @foreach(getMonth() as $ls)
                                                    <option value="{{ $ls['month'] }}">{{ $ls['month_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-form-label">Year <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <select name="year_periode_filter" id="year_periode_filter" onchange="periodeChangeFilter()" class="js-example-placeholder col-sm-12" required>
                                                <option value="">--Select--</option>
                                                @foreach(getYearPeriode() as $ls)
                                                    <option value="{{ $ls['year'] }}">{{ $ls['year'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        {{-- <table id="salesOrderTable" class="table table-striped table-bordered nowrap"> --}}
                                        <table id="salesOrderTable" class="table table-striped table-bordered nowrap dt-responsive width-100">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Sales Order No.</th>
                                                    <th>PO No. Customer</th>
                                                    {{-- <th width="8%">status</th> --}}
                                                    <th>Customer</th>
                                                    <th>Total Price</th>
                                                    <th>Order Date</th>
                                                    <th>PO Date Customer</th>
                                                    <th>Created User</th>
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
                                    <h5>Sales Order</h5>
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
                                    <form action="/process/sales-order/add" method="post" enctype="multipart/form-data" id="salesOrderForm">
                                        @csrf
                                        <input type="hidden" name="sales_order_id" id="sales_order_id">
                                        <input type="hidden" name="itemList" id="itemList">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Sales Order Number </label>
                                            <div class="col-sm-5">
                                                <input type="input" readonly name="so_number" id="so_number" class="form-control">
                                            </div>
                                            <label class="col-sm-5 col-form-label">Auto generate after save as process</label>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Customer <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="customer_id" id="customer_id" onchange="customerChange()" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($customerList as $ls)
                                                        <option value="{{ $ls->customer_id }}">{{ $ls->business_entity.". ".$ls->customer_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Month Periode <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="month_periode" id="month_periode" onchange="periodeChanged()" class="js-example-placeholder col-sm-12" required>
                                                        <option value="">--Select--</option>
                                                    @foreach (getMonth() as $ls)
                                                        <option value="{{ $ls['month'] }}">{{ $ls['month_name'] }}</option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Year Periode <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="year_periode" id="year_periode" class="js-example-placeholder col-sm-12" required>
                                                        <option value="">--Select--</option>
                                                    @foreach (getYearPeriode() as $ls)
                                                        <option value="{{ $ls['year'] }}">{{ $ls['year'] }}</option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Date Sales Order <span class="text-danger">*</span></label>
                                            <div class="col-sm-5">
                                                <input type="date" name="so_date" id="so_date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PO Number Customer</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="po_number_customer" id="po_number_customer" class="form-control"
                                                oninput="validateMaxLength(this)" maxlength="30" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PO Date Customer</label>
                                            <div class="col-sm-5">
                                                <input type="date" name="po_date_customer" id="po_date_customer" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <label class="col-sm-6 col-form-label">Please load part for download excel template.</label>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Import From Excel</label>
                                            <div class="col-sm-6 input-group input-group-button">
                                                <input type="file" class="form-control" name="import_part" id="import_part" accept=".xls, .xlsx" placeholder="Choose File .xlsx">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" onClick="importExcelClick('#import_part')" type="button">Upload Excel</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-12"><button type="button" onclick="loadPartCustomer()" id="load_part_customer" class="btn btn-info btn-round btn-sm waves-effect">Load Part Customer</button>
                                            </div>
                                        </div>
                                        <div class="dt-responsive table-responsive">

                                            {{-- <table id="soItemTableList" class="table table-striped table-bordered"> --}}
                                            <table id="soItemTableList" width="100%" class="display table table-bordered table-striped nowrap">
                                            {{-- <table id="soItemTableList" class="table table-striped table-bordered nowrap dt-responsive width-100"> --}}
                                                <thead>
                                                    <tr>
                                                        <th>No</th>
                                                        <th>Part Customer ID</th>
                                                        <th>Product Name</th>
                                                        <th width="60%">Qty Order</th>
                                                        <th width="60%"><span id="m+1">Qty M+1</span></th>
                                                        <th width="60%"><span id="m+2">Qty M+2</span></th>
                                                        <th width="60%"><span id="m+3">Qty M+3</span></th>
                                                        <th width="60%"><span id="m+4">Qty M+4</span></th>
                                                        <th width="60%"><span id="m+5">Qty M+5</span></th>
                                                        <th>Unit</th>
                                                        <th>Product Price</th>
                                                        <th>Total Price Order</th>
                                                        <th width="30%">BOM Cost</th>
                                                        <th>BOP Cost</th>
                                                        <th>Divisi</th>
                                                        <th>Plant</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <input type="hidden" name="is_process" id="is_process">
                                                <input type="hidden" name="is_draft" id="is_draft">
                                                <button type="button" id="btnCancelSO" class="btn btn-danger waves-effect waves-light" onClick="cancelInit('#btnCancelSO')">Batalkan Process</button>
                                                <button type="button" class="btn btn-secondary waves-effect waves-light" onClick="saveInit('#salesOrderForm', 0)">Save as draft</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#salesOrderForm', 1)">Save</button>
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
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        var d = new Date();
        var month = d.getMonth()+1;
        var year = d.getFullYear();
        $('#month_periode_filter').val(month);
        $('#year_periode_filter').val(year);
        $('#month_periode_filter').trigger('change');
        $('#year_periode_filter').trigger('change');

        $("#input").hide();
    } );

    async function periodeChangeFilter(){
        var month_periode = $('#month_periode_filter').val();
        var year_periode = $('#year_periode_filter').val();
        if(month_periode !== "" && year_periode !== ""){
            loadData(month_periode, year_periode);
        }
    }

    function periodeChanged(){
        var monthList = {!! json_encode($monthList) !!};
        var month_periode = $('#month_periode').val();
        var m1 = parseInt(parseInt(month_periode) + 1) > 12 ? (parseInt(parseInt(month_periode) + 1) - 12) : parseInt(parseInt(month_periode) + 1);
        var m2 = parseInt(parseInt(month_periode) + 2) > 12 ? (parseInt(parseInt(month_periode) + 2) - 12) : parseInt(parseInt(month_periode) + 2);
        var m3 = parseInt(parseInt(month_periode) + 3) > 12 ? (parseInt(parseInt(month_periode) + 3) - 12) : parseInt(parseInt(month_periode) + 3);
        var m4 = parseInt(parseInt(month_periode) + 4) > 12 ? (parseInt(parseInt(month_periode) + 4) - 12) : parseInt(parseInt(month_periode) + 4);
        var m5 = parseInt(parseInt(month_periode) + 5) > 12 ? (parseInt(parseInt(month_periode) + 5) - 12) : parseInt(parseInt(month_periode) + 5);

        var month1 = monthList.find(a => a.month === m1);
        var month2 = monthList.find(a => a.month === m2);
        var month3 = monthList.find(a => a.month === m3);
        var month4 = monthList.find(a => a.month === m4);
        var month5 = monthList.find(a => a.month === m5);
        var table = $("#soItemTableList").DataTable();
        $(table.column(4).header()).text(month1.month_name);
        $(table.column(5).header()).text(month2.month_name);
        $(table.column(6).header()).text(month3.month_name);
        $(table.column(7).header()).text(month4.month_name);
        $(table.column(8).header()).text(month5.month_name);
    }

    function loadData(month_periode, year_periode){
        $('#salesOrderTable').DataTable().destroy();
        var table = $('#salesOrderTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/sales-order/load/'+month_periode+'/'+year_periode,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "so_number" },
                { "data": "po_number_customer" },
                {  "mRender": function (data, type, row, num) {
                        var customerStatus = "";
                        customerStatus += row.customer.business_entity+". "+row.customer.customer_name + " ";
                        customerStatus += row.is_process === 1 ? "<span class='btn-success btn-sm' > -procesed- </span>" : "";
                        customerStatus += row.is_draft === 1 ? "<span class='btn-secondary btn-sm' > -draft- </span>" : "";
                        customerStatus += row.is_void === 1 ? "<span class='btn-danger btn-sm' > -void- </span>" : "";
                        return customerStatus;
                    }
                },
                { "data": "total_price" },
                { "data": "so_date" },
                { "data": "po_date_customer" },
                { "data": "created_user" },
                {
                    "mData": "sales_order_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.so_number +"("+ row.customer.business_entity+". "+row.customer.customer_name +") sales order??' data-url='/process/sales-order/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
            ]
        });
    }

    async function loadDataSoItem(customer_id, items){
        var customerList = {!! json_encode($customerList) !!};
        var monthList = {!! json_encode($monthList) !!};
        var periode_month = $("#month_periode").val();
        var periode_year = $("#year_periode").val();
        var periode = monthList.find(a => a.month === parseInt(periode_month));
        var periode_name = periode.month_name;
        var customer = customerList.find(a => a.customer_id === customer_id);
        var export_name = customer.business_entity + "." + customer.customer_name + "-so-" + periode_name + periode_year;
        $('#soItemTableList').DataTable().destroy();
        var table = $('#soItemTableList').DataTable({
            "scrollY":        "300px",
            "scrollX":        true,
            "scrollCollapse": true,
            "fixedColumns":   {
                "left": 3,
            },
            "columnDefs": [
                { "targets": 1, "visible": false },
            ],
            "autoWidth": false,
            "bPaginate": false,
            "bFilter": true,
            "dom":'Bfrtip',
            "buttons": [
                {
                    extend:'excel',exportOptions: {
                        format: {
                            body: function ( data, row, column, node ) {
                                return $(data).is("input") ? $(data).val() : data;
                            }
                        },
                    },
                    className: 'btn btn-block',
                    text: 'Export To Excel',
                    title: 'ERP-Rekadaya-' + export_name,
                },
            ],
            "ajax": '/part-customer/loadbyid/'+customer_id,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="text" value="'+row.part_customer_id+'" class="part_customer_id_input">';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.part_number+ " - " +row.part_name;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var qtyOrder = 0;
                        var item = items != '' ? items.find(a => a.part_customer_id === row.part_customer_id) : '';
                        qtyOrder = item.qty ? item.qty : 0;
                        return '<input type="number" min="0" value="'+qtyOrder+'" class="qtyOrder" onChange="countCost(this)" required>';
                    },
                },
                {  "mRender": function (data, type, row, num) {
                        var qtyM1 = 0;
                        var item = items != '' ? items.find(a => a.part_customer_id === row.part_customer_id) : '';
                        qtyM1 = item.qtyM1 ? item.qtyM1 : 0;
                        return '<input type="number" min="0" value="'+qtyM1+'" class="qtyM1" required>';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var qtyM2 = 0;
                        var item = items != '' ? items.find(a => a.part_customer_id === row.part_customer_id) : '';
                        qtyM2 = item.qtyM2 ? item.qtyM2 : 0;
                        return '<input type="number" min="0" value="'+qtyM2+'" class="qtyM2" required>';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var qtyM3 = 0;
                        var item = items != '' ? items.find(a => a.part_customer_id === row.part_customer_id) : '';
                        qtyM3 = item.qtyM3 ? item.qtyM3 : 0;
                        return '<input type="number" min="0" value="'+qtyM3+'" class="qtyM3" required>';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var qtyM4 = 0;
                        var item = items != '' ? items.find(a => a.part_customer_id === row.part_customer_id) : '';
                        qtyM4 = item.qtyM4 ? item.qtyM4 : 0;
                        return '<input type="number" min="0" value="'+qtyM4+'" class="qtyM4" required>';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var qtyM5 = 0;
                        var item = items != '' ? items.find(a => a.part_customer_id === row.part_customer_id) : '';
                        qtyM5 = item.qtyM5 ? item.qtyM5 : 0;
                        return '<input type="number" min="0" value="'+qtyM5+'" class="qtyM5" required>';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.unit_name;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="text" disabled value="'+numbering(row.part_price)+'" class="price text-right">';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var total_price = 0;
                        var item = items != '' ? items.find(a => a.part_customer_id === row.part_customer_id) : '';
                        var qtyOrder = item.qty ? item.qty : 0;
                        total_price = qtyOrder * row.part_price ;
                        return '<input type="text" disabled value="'+numbering(total_price)+'" class="total_price text-right">';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="text" disabled value="'+row.costBOM.data+'" class="costBOM text-right">';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var bopPrice = "";
                        bopPrice += '<input type="text" disabled value="'+row.costBOP.data+'" class="costBOP text-right">';
                        bopPrice += '<input type="hidden" value="'+row.part_customer_id+'" class="part_customer_id">';
                        bopPrice += '<input type="hidden" value="'+row.unit_id+'" class="unit_id">';
                        bopPrice += '<input type="hidden" value="'+row.divisi_id+'" class="divisi_id">';
                        bopPrice += '<input type="hidden" value="'+row.plant_id+'" class="plant_id">';
                        return bopPrice;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.divisi_name;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.plant_name;
                    }
                },
            ]
        });
    }

    function justGetPrice(part){
        var partPrice = 0;
        var today = getDateNow();
        var priceData = part.part_price.filter(a => a.effective_date <= today);

        priceData = sortDescDateEffective(priceData);
        if(priceData.length > 0)
            partPrice = priceData[0].price;

        return partPrice;
    }

    // function justCalculateCost(item){
    //     var bomPrice = parseFloat(0);
    //     var today = getDateNow();
    //     var data = item.part_supplier.part_price.find(a => a.effective_date <= today);
    //     var price = 0;
    //     if(!data){
    //         swal("Info!", "Price at BOP is not found, please check BOP.");
    //     }else{
    //         price = parseFloat(data.price) * parseFloat(item.amount_usage)
    //     }

    //     return price;
    // }

    async function return_value(e, data){
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#salesOrderForm").attr("action", "/process/sales-order/update");
            $("#defaultModalLabel").text("Edit Sales Order Customer")
            $("#load_part_customer").attr("disabled", true)
            await getData('/process/sales-order/get/'+data.sales_order_id).then(function(result){
                fillToForm(result);
            });
        } else {
            var d = new Date();
            var month = d.getMonth()+1;
            var year = d.getFullYear();
            $("#salesOrderForm").trigger("reset");
            $("#load_part_customer").attr("disabled", false)
            $("#customer_id").trigger('change');
            $('#month_periode').val(month);
            $('#year_periode').val(year);
            $("#month_periode").trigger('change');
            $("#year_periode").trigger('change');
            $("#btnCancelSO").hide();
            $("#salesOrderForm").attr("action", "/process/sales-order/add");
            $("#defaultModalLabel").text("Add Sales Order Customer");
            var table = $('#soItemTableList').DataTable();
            table.clear().draw();

        }
        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;
    }

    function fillToForm(data){
        $("#sales_order_id").val(data.sales_order_id);
        $("#so_number").val(data.so_number);
        $("#customer_id").val(data.customer_id);
        $("#customer_id").trigger('change');
        $("#month_periode").val(data.month_periode);
        $("#month_periode").trigger('change');
        $("#year_periode").val(data.year_periode);
        $("#year_periode").trigger('change');
        $("#so_date").val(data.so_date);
        $("#po_number_customer").val(data.po_number_customer);
        $("#po_date_customer").val(data.po_date_customer);
        $("#po_date_customer").val(data.po_date_customer);
        loadDataSoItem(data.customer_id, data.so_items);
        if(data.so_number){
            $("#btnCancelSO").show();
            $("#btnCancelSO").attr("data-confirm", "Are you sure|want to cancel sales order "+data.so_number+"?");
            $("#btnCancelSO").attr("data-url", "/process/sales-order/cancel/"+data.sales_order_id);

        }else{
            $("#btnCancelSO").hide();
        }
    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form, is_process){
        getItemList();
        $("#is_process").val(is_process === 1 ? 1 : 0);
        $("#is_draft").val(is_process === 0 ? 1 : 0);

        saveDataModal(form, '.closeForm', function() {
            periodeChangeFilter();
            loadSelect2();
        });
    }

    function getItemList(){
        $("#soItemTableList").DataTable().search("").draw()
        var itemList = [];
        $('#soItemTableList tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var part_customer_id = $(this).find(".part_customer_id").val();
            var qty = $(this).find(".qtyOrder").val();
            var qtyM1 = $(this).find(".qtyM1").val();
            var qtyM2 = $(this).find(".qtyM2").val();
            var qtyM3 = $(this).find(".qtyM3").val();
            var qtyM4 = $(this).find(".qtyM4").val();
            var qtyM5 = $(this).find(".qtyM5").val();
            var unit_id = $(this).find(".unit_id").val();
            var price = $(this).find(".price").val();
            var total_price = $(this).find(".total_price").val();
            var divisi_id = $(this).find(".divisi_id").val();
            var plant_id = $(this).find(".plant_id").val();
            data = {
                part_customer_id: part_customer_id,
                qty: qty,
                qtyM1: qtyM1,
                qtyM2: qtyM2,
                qtyM3: qtyM3,
                qtyM4: qtyM4,
                qtyM5: qtyM5,
                unit_id: unit_id,
                price: parseFloat(price.replace(/,/g, '')),
                total_price: parseFloat(total_price.replace(/,/g, '')),
                divisi_id: divisi_id,
                plant_id: plant_id,
            };
            itemList.push(data);
        });
        document.getElementById('itemList').value = JSON.stringify(itemList);
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            periodeChangeFilter();
        });
    }

    function cancelInit(e){
        deleteConfirm(e, function() {
            periodeChangeFilter();
            $('.closeForm').click();
        });
    }

    async function customerChange(){

    }

    async function loadPartCustomer(){
        var customer_id = $("#customer_id").val();
        var items = '';
        if(customer_id != ""){
            await loadDataSoItem(customer_id, items);
        }
    }

    function alertValidation($title){
        swal({
            title: "Error Validate",
            text: "Please add material!",
            type: "warning",
            showCancelButton: false,
        });
    }

    function countCost(e){
        var qtyOrder = $(e).parent().find(".qtyOrder").val();
        var partPrice = $(e).parent().parent().find(".price").val();
        var totalPrice = parseFloat(partPrice.replace(/,/g, '')) * parseFloat(qtyOrder);
        $(e).parent().parent().find(".total_price").val(numbering(totalPrice));
    }

    function importExcelClick(inputFileId){
        let fileImported = $(inputFileId)[0].files[0];
        if(fileImported){
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(fileImported);
            fileReader.onload = (event)=>{
                let data = event.target.result;
                let workbook = XLSX.read(data,{type:"binary", cellDates:true});
                workbook.SheetNames.forEach(sheet => {
                    rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
                    mappingData(rowObject);
                })
            }
        }
    }

    function mappingData(dataList){
        var monthList = {!! json_encode($monthList) !!};
        var month_periode = $('#month_periode').val();
        var m1 = parseInt(parseInt(month_periode) + 1) > 12 ? (parseInt(parseInt(month_periode) + 1) - 12) : parseInt(parseInt(month_periode) + 1);
        var m2 = parseInt(parseInt(month_periode) + 2) > 12 ? (parseInt(parseInt(month_periode) + 2) - 12) : parseInt(parseInt(month_periode) + 2);
        var m3 = parseInt(parseInt(month_periode) + 3) > 12 ? (parseInt(parseInt(month_periode) + 3) - 12) : parseInt(parseInt(month_periode) + 3);
        var m4 = parseInt(parseInt(month_periode) + 4) > 12 ? (parseInt(parseInt(month_periode) + 4) - 12) : parseInt(parseInt(month_periode) + 4);
        var m5 = parseInt(parseInt(month_periode) + 5) > 12 ? (parseInt(parseInt(month_periode) + 5) - 12) : parseInt(parseInt(month_periode) + 5);

        var month1 = monthList.find(a => a.month === m1);
        var month2 = monthList.find(a => a.month === m2);
        var month3 = monthList.find(a => a.month === m3);
        var month4 = monthList.find(a => a.month === m4);
        var month5 = monthList.find(a => a.month === m5);

        var itemList = [];
        dataList.forEach(function(data) {
            item = {
                part_customer_id: data['Part Customer ID'],
                qty: data['Qty Order'],
                qtyM1: data[month1.month_name],
                qtyM2: data[month2.month_name],
                qtyM3: data[month3.month_name],
                qtyM4: data[month4.month_name],
                qtyM5: data[month5.month_name],
            };

            itemList.push(item);
        })
        var customer_id = $("#customer_id").val();
        if(customer_id != ""){
            loadDataSoItem(customer_id, itemList);
        }
    }

    function removeMaterial(e){
        $(e).parent().parent().remove();
    }

    function validateDuplicate(e){
        var isDuplicate = justCheckDuplicate();
        if(isDuplicate){
            $(e).parent().parent().parent().find(".error-text").text("Error : Duplicate part in list!");
        }else{
            $(e).parent().parent().parent().find(".error-text").text("");
        }
    }

    // function justCheckDuplicate(){
    //     var values = $("select[name='part_id[]']").map(function(){return $(this).val();}).get();
    //     return values.some(function(item, idx){
    //             return values.indexOf(item) != idx
    //         });
    // }
</script>
@endsection

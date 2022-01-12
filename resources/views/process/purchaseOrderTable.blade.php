@extends('layouts.headerIn')
@section('content')
<style>
    .tright{
        text-align:right;
    }
</style>
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">PURCHASE ORDER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Purchase Order</a></li>
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
                                    <h5>Purchase Order</h5>
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
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th width="25%">No. PO</th>
                                                    <th width="25%">Supplier</th>
                                                    <th width="25%">Tanggal PO</th>
                                                    <th width="12%">Kebutuhan Bulan</th>
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
                                    <h5>Purchase Order</h5>
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
                                    <form action="/process/purchase-order/add" method="post" enctype="multipart/form-data" id="purchaseForm">
                                        @csrf
                                        <input type="hidden" name="po_id" id="po_id">
                                        <input type="hidden" name="Htotal" id="Htotal">
                                        <input type="hidden" name="Hppn" id="Hppn">
                                        <input type="hidden" name="Htotal_ppn" id="Htotal_ppn">
                                        <input type="hidden" name="status_process" id="status_process">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">No PO *</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="po_number" id="po_number" class="form-control" placeholder="auto generate after save" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Supplier *</label>
                                            <div class="col-sm-10">
                                                <select name="supplier_id" id="supplier_id" class="js-example-placeholder col-sm-12" onchange="getPartList()" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($supplierList as $ls)
                                                        <option value="{{ $ls->supplier_id }}">{{ $ls->business_entity.". ".$ls->supplier_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PO Date *</label>
                                            <div class="col-sm-5">
                                                <input type="date" name="po_date" id="po_date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Period *</label>
                                            <div class="col-sm-3">
                                                <select name="month_periode" id="month_periode" class="so_get js-example-placeholder col-sm-12" onchange="getPartList()" required>
                                                    <option value="">--Select--</option>
                                                    @foreach(getMonth() as $ls)
                                                        <option value="{{ $ls['month'] }}">{{ $ls['month_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label class="col-form-label">Year</label>
                                            <div class="col-sm-3">
                                                <select name="year_periode" id="year_periode" class="so_get js-example-placeholder col-sm-12" onchange="getPartList()" required>
                                                    <option value="">--Select--</option>
                                                    @foreach(getYearPeriode() as $ls)
                                                        <option value="{{ $ls['year'] }}">{{ $ls['year'] }}</option>
                                                    @endforeach
                                                </select>
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
                                            <label class="col-sm-12 col-form-label"><b>Material</b></label>
                                        </div>
                                        <div class="dt-responsive table-responsive">
                                            <table id="partTable" class="table table-striped table-bordered nowrap">
                                                <thead id="headPart">
                                                    <tr>
                                                        <th>Material ID</th>
                                                        <th>Material</th>
                                                        <th>Divisi</th>
                                                        <th>UOM</th>
                                                        <th>Qty Budget</th>
                                                        <th>NG Rate(%)</th>
                                                        <th>Buffer Stock (%)</th>
                                                        <th>Qty Total</th>
                                                        <th>Stock</th>
                                                        <th>Standar Packing</th>
                                                        <th>Order</th>
                                                        <th>Price</th>
                                                        <th>Total</th>
                                                        <th>PPN (0,1)</th>
                                                        <th>Total + PPN</th>
                                                        <th>M+1</th>
                                                        <th>M+2</th>
                                                        <th>M+3</th>
                                                        <th>M+4</th>
                                                        <th>M+5</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-8"></label>
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" id="logProcess" class="btn btn-success waves-effect" style="display:none" data-toggle='modal' data-target='#log-Modal'>Log</button>
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <button type="button" class="save btn btn-info waves-effect waves-light" onClick="saveInit('#purchaseForm', 1)">Save Draft</button>
                                                <button type="button" class="save btn btn-primary waves-effect waves-light" onClick="saveInit('#purchaseForm', 2)">Process</button>

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
<div class="modal fade" id="mail-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Sending Purchase Order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/process/purchase-order/send" method="post" enctype="multipart/form-data" id="sendForm">
                @csrf
                <input type="hidden" name="poID" id="poID">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email To <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="to" id="to" class="mails form-control" value="" data-role="tagsinput" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email CC <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="cc" id="cc" class="mails form-control" value="" data-role="tagsinput" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Subject <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <textarea name="desc" id="desc" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    
                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="sendInit('#sendForm')">Send</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="log-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Log Purchase Order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="dt-responsive table-responsive">
                    <table id="logTable" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>Process</th>
                                <th>Noted</th>
                                <th>Date</th>
                                <th>User</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@php
    $monthList = getMonth();
@endphp
@include('layouts.footerIn')
<script>
    var dataPart = [];
    var itemPO = [];
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

    function loadData(month_periode, year_periode){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "ajax": '/process/purchase-order/load/'+month_periode+'/'+year_periode,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "po_number" },
                {  "mRender": function (data, type, row, num) {

                        return row.supplier.business_entity+" "+row.supplier.supplier_name;
                    }
                },
                { "data": "po_date" },
                {  "mRender": function (data, type, row, num) {
                        var arrMonth = {!! json_encode(getMonth()) !!};
                        var month = arrMonth.filter(e => e.month === row.month_periode);
                        return month[0].month_name+" "+row.year_periode;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var arrColor = {!! json_encode(getColor()) !!};
                        var status = "<span class='btn-"+ arrColor[row.status_process] +" btn-sm' > "+ row.status_po.status_process_name +" </span>";
                        return status;
                    }
                },
                {
                    "mData": "po_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        if(row.status_process < 5 ){
                            @if(isAccess('delete', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.po_number +" PO number??' data-url='/process/purchase-order/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                            @endif
                        }
                        if(row.status_process >= 5 ){
                            @if(isAccess('update', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-inverse btn-icon' onClick=printPurchaseOrder('"+ data +"')>&nbsp;<i class='icofont icofont-download'></i></button>";
                                button += "<button class='btn waves-effect waves-light btn-success btn-icon' onClick='send_value("+ JSON.stringify(row) +")' data-toggle='modal' data-target='#mail-Modal'>&nbsp;<i class='icofont icofont-email'></i></button>";
                            @endif
                        }
                        return button;
                    }
                }

            ]
        });
    }


    async function return_value(e, data){
        var btn = $(e).attr("btn");
        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;

        if (btn == "edit"){
            $("#purchaseForm").attr("action", "/process/purchase-order/update");
            await getData('/process/purchase-order/get/'+data.po_id).then(function(result){
                fillToForm(result);
                fillToLog(result.log_po);
                $("#logProcess").show();
            });
        } else {
            itemPO = [];
            $("#logProcess").hide();
            $("#purchaseForm").trigger("reset");
            $("#purchaseForm").attr("action", "/process/purchase-order/add");
            $('.js-example-placeholder').select2('destroy');
            $('.js-example-placeholder').select2();
            $('#partTable tbody').remove();
        }

    }

    function fillToForm(data){
        $(".save").show();
        if(data.status_process > 3){
            $(".save").hide();
        }

        itemPO = data.po_items;
        $('.js-example-placeholder').select2('destroy');
        $("#po_id").val(data.po_id);
        $("#po_number").val(data.po_number);
        $("#supplier_id").val(data.supplier_id).select2();
        $("#po_date").val(data.po_date);
        $("#month_periode").val(data.month_periode);
        $("#year_periode").val(data.year_periode);
        $('.js-example-placeholder').select2();
        $("#Htotal").val(data.price);
        $("#Hppn").val(data.ppn);
        $("#Htotal_ppn").val(data.total_price);
        getPartList();

    }

    function fillToLog(log){
        $('#logTable').DataTable().destroy();
        var table = $('#logTable').DataTable({
            "data": log,
            "aoColumns": [
                { "data": "log_status.status_process_name" },
                { "data": "comment" },
                {  "mRender": function (data, type, row, num) {

                        return row.created_at.substr(0, 10)+" "+row.created_at.substr(11, 8);
                    }
                },
                { "data": "created_user" }
            ]
        });
    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form, status){
        if ($('input[name="part_id[]"]').length > 0){
            $("#status_process").val(status);
            saveData(form, function() {
                periodeChangeFilter();
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
            periodeChangeFilter();
        });
    }

    function getPartList(){
        var supplier = $("#supplier_id").val();
        var month = $("#month_periode").val();
        var year = $("#year_periode").val();
        var part = [];
        var export_name = "";
        if (supplier != "" && month != "" && year != ""){

            addHeaderColomn();
            var supplierList = {!! json_encode($supplierList) !!};
            var monthList = {!! json_encode($monthList) !!};
            var periode = monthList.find(a => a.month === parseInt(month));
            var periode_name = periode.month_name;
            var supplierData = supplierList.find(a => a.supplier_id === supplier);
            var export_name = supplierData.business_entity + "." + supplierData.supplier_name + "-PO-" + periode_name + year;

            var url = '/process/budgeting/getForPO/'+ supplier +'/'+ month +'/'+ year;
            $('#partTable').DataTable().destroy();
            var table = $('#partTable').DataTable({
                "ajax": url,
                "filter": true,
                "bPaginate": false,
                "bLengthChange": false,
                "dom": 'Bfrtip',
                "buttons": [
                    {
                        extend:'excel',exportOptions: {
                            format: {
                                body: function ( data, row, column, node ) {
                                    return $(data).is("input") ? $(data).val() : data;
                                }
                            },
                            columns: [ 0, 1, 2, 3, 4, 5, 6, 8, 9, 11 ]
                        },
                        className: 'btn btn-block',
                        text: 'Export To Excel',
                        title: 'ERP-Rekadaya-' + export_name,
                    },
                ],
                "columnDefs": [
                    { "targets": 0, "visible": false },
                ],
                "aoColumns": [
                    { "data": "part_supplier.part_supplier_id" },
                    { "data": "part_supplier.part_name" },
                    { "data": "part_supplier.divisi.divisi_name" },
                    { "data": "part_supplier.unit.unit_name" },
                    {  "mRender": function (data, type, row, num) {
                            part = itemPO.filter(a => a.part_supplier_id === row.part_supplier_id );
                            var qty = part[0] === undefined ? Math.ceil(row.totalqty) : Math.ceil(part[0].qty);
                            return '<input type="number" name="qty[]" class="qty form-control tright" style="width:70px" value="'+ Math.ceil(row.totalqty) +'" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            var ng_rate = part[0] === undefined ? "" : Math.ceil(part[0].qty_ng_rate);
                            return '<input type="number" name="ng_rate[]" class="ng_rate form-control tright" style="width:90px" onInput="countTotal(this)" value="'+ ng_rate +'" placeholder="percent" required>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            var buffer_stock = part[0] === undefined ? "" : Math.ceil(part[0].buffer_stock);
                            return '<input type="number" name="buffer_stock[]" class="buffer_stock form-control tright" style="width:90px" onInput="countTotal(this)" value="'+ buffer_stock +'" placeholder="percent" required>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            return '<input type="number" name="qty_total[]" class="qty_total form-control tright" style="width:70px" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            return '<input type="number" name="stock[]" class="stock form-control tright" style="width:70px" value="'+ row.part_supplier.stock +'" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            return '<input type="number" name="standard_packing[]" class="standard_packing form-control tright" style="width:90px" value="'+ row.part_supplier.standard_packing +'" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            return '<input type="number" name="order[]" class="order form-control tright" style="width:70px" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            return '<input type="number" name="price[]" size="10" class="price form-control tright" style="width:90px" value="'+ row.part_supplier.part_price_active.price +'" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            return '<input type="number" name="total[]" class="total form-control tright" style="width:100px" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            return '<input type="number" name="ppn[]" class="ppn form-control tright" style="width:100px" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            return '<input type="number" name="total_ppn[]" class="total_ppn form-control tright" style="width:100px" readonly><input type="hidden" name="part_id[]" class="part_id" value="'+ row.part_supplier_id +'" >';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            var m1 = row.forecast.filter(a => a.next_month === "1");
                            return '<input type="number" value="'+ Math.ceil(m1[0].totalqty) +'" class="m1 form-control tright" style="width:100px" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            var m2 = row.forecast.filter(a => a.next_month === "2");
                            return '<input type="number" value="'+ Math.ceil(m2[0].totalqty) +'" class="m2 form-control tright" style="width:100px" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            var m3 = row.forecast.filter(a => a.next_month === "3");
                            return '<input type="number" value="'+ Math.ceil(m3[0].totalqty) +'" class="m3 form-control tright" style="width:100px" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            var m4 = row.forecast.filter(a => a.next_month === "4");
                            return '<input type="number" value="'+ Math.ceil(m4[0].totalqty) +'" class="m4 form-control tright" style="width:100px" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            var m5 = row.forecast.filter(a => a.next_month === "5");
                            return '<input type="number" value="'+ Math.ceil(m5[0].totalqty) +'" class="m5 form-control tright" style="width:100px" readonly>';
                        }
                    }
                ],
                "initComplete": function(settings, json) {
                    $('.ng_rate').each(function(i) {
                        countTotal(this, supplierData);
                    });
                }
            });
            $('#partTable_wrapper .row').first().find('div:first').remove();
            $('#partTable_filter').css("text-align", "left");

        }

    }

    function addHeaderColomn(){
        var monthList = {!! json_encode($monthList) !!};
        var month_periode = $("#month_periode").val();
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

        var table = $("#partTable").DataTable();
        $(table.column(15).header()).text(month1.month_name);
        $(table.column(16).header()).text(month2.month_name);
        $(table.column(17).header()).text(month3.month_name);
        $(table.column(18).header()).text(month4.month_name);
        $(table.column(19).header()).text(month5.month_name);
    }

    function countTotal(e, supplierData){
        
        var tr = $(e).parent().parent();

        var qty = tr.find(".qty").val();
            qty = qty ? qty : 0;

        var stock = tr.find(".stock").val();
            stock = stock ? stock : 0;

        var ng_rate = tr.find(".ng_rate").val();
            ng_rate = ng_rate ? ng_rate : 0;

        var buffer_stock = tr.find(".buffer_stock").val();
            buffer_stock = buffer_stock ? buffer_stock : 0;

        var packing = tr.find(".standard_packing").val();
            packing = packing ? packing : 0;

        var price = tr.find(".price").val();
            price = price ? price : 0;
        var order = qty;
        var total = 0;
        var ppn = 0;
        var total_ppn = 0;
        var addPacking = 0;

        if(qty != ""){

            qty_total = Math.ceil((parseFloat(qty) * parseFloat(ng_rate) / 100) + parseFloat(qty));
            qty_total = qty_total + Math.ceil(parseFloat(qty_total) * parseFloat(buffer_stock) / 100);
            order = qty_total - stock;
            var sisa = order % packing;
            order = sisa > 0 ? order + (packing - sisa) : order;
            order = order < 0 ? 0 : order;
            total = parseFloat(price) * parseFloat(order);
            if(supplierData.is_ppn == 1)
                ppn = parseFloat(total) * 0.1;

            total_ppn = parseFloat(total) + parseFloat(ppn);
        }

        tr.find(".qty_total").val(qty_total);
        tr.find(".order").val(order);
        tr.find(".total").val(total);
        tr.find(".ppn").val(ppn);
        tr.find(".total_ppn").val(total_ppn);

        countHeaderTotal();
    }

    function countHeaderTotal(){
        var total = 0;
        var ppn = 0;
        var total_ppn = 0;
        $('input[name="total[]"]').each(function(index) {

            if($(this).val() != ""){
                total = total + parseFloat($(this).val());
                ppn = ppn + parseFloat($('input[name="ppn[]"]:eq('+ index +')').val());
                total_ppn = parseFloat(total_ppn + parseFloat($('input[name="total_ppn[]"]:eq('+ index +')').val()));
            }
        });

        $("#Htotal").val(total);
        $("#Hppn").val(ppn);
        $("#Htotal_ppn").val(total_ppn);
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
        var itemList = [];
        dataList.forEach(function(data) {
            item = {
                part_supplier_id: data['Material ID'],
                ng_rate: data['NG Rate(%)'],
                buffer_stock: data['Buffer Stock (%)']
            };

            itemList.push(item);
        })

        $('.ng_rate').each(function(i) {
            var tr = $(this).parent().parent();
            var items = itemList.filter(a => a.part_supplier_id === tr.find(".part_id").val());
            if (items){
                tr.find(".ng_rate").val(items[0].ng_rate);
                tr.find(".buffer_stock").val(items[0].buffer_stock);
                countTotal(this);
            }

        });
    }

    function printPurchaseOrder(poID){
        window.open('/process/purchase-order/print/'+poID, '_blank');
    }

    async function send_value(data){

        $('.mails').tagsinput('removeAll');
        $("#sendForm").trigger("reset");
        var ccMailList = await getData('/master/mail/load');
        ccMailList.forEach(function(mail) {
            $('#cc').tagsinput('add', mail.ccmail_email);
        });
        $("#poID").val(data.po_id);
        $('#to').tagsinput('add', data.supplier.supplier_email);
    }

    function sendInit(form){
        saveData(form, function() {
            periodeChangeFilter();
            $("#closeModal").click();
        });
    }

</script>
@endsection

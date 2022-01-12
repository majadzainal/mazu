@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">ADJUSTMENT PURCHASE ORDER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Adjustment Purchase Order</a></li>
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
                                    <h5>Adjustment Purchase Order</h5>
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
                                    <form action="/process/adjustment-purchase-order/update" method="post" enctype="multipart/form-data" id="purchaseForm">
                                        @csrf
                                        <input type="hidden" name="po_id" id="po_id">
                                        <input type="hidden" name="status_process" id="status_process">
                                        <input type="hidden" name="Htotal" id="Htotal">
                                        <input type="hidden" name="Hppn" id="Hppn">
                                        <input type="hidden" name="Htotal_ppn" id="Htotal_ppn">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">No PO </label>
                                            <div class="col-sm-5">
                                                <input type="text" name="po_number" id="po_number" class="form-control" placeholder="auto generate after save" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Supplier</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="supplier" id="supplier" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PO Date</label>
                                            <div class="col-sm-5">
                                                <input type="date" name="po_date" id="po_date" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Period</label>
                                            <div class="col-sm-3">
                                                <input type="text" name="month" id="month" class="form-control" readonly>
                                            </div>
                                            <label class="col-form-label">Year</label>
                                            <div class="col-sm-3">
                                                <input type="texts" name="year" id="year" class="form-control" readonly>
                                            </div>
                                        </div>
                                        <div class="dt-responsive table-responsive">
                                            <table id="partTable" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Material</th>
                                                        <th>Divisi</th>
                                                        <th>UOM</th>
                                                        <th>Qty Budget</th>
                                                        <th class="adds">NG Rate(%)</th>
                                                        <th class="adds">Buffer Stock (%)</th>
                                                        <th class="adds">Qty Total</th>
                                                        <th>Stock</th>
                                                        <th>Standar Packing</th>
                                                        <th>Order</th>
                                                        <th>Price</th>
                                                        <th>Total</th>
                                                        <th>PPN (0,1)</th>
                                                        <th>Total + PPN</th>
                                                        <th class="adds">m1</th>
                                                        <th class="adds">m2</th>
                                                        <th class="adds">m3</th>
                                                        <th class="adds">m4</th>
                                                        <th class="adds">m5</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyMaterial">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"><b>Note</b></label>
                                            <div class="col-sm-12">
                                                <textarea name="note" id="note" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-8"></label>
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" id="logProcess" class="btn btn-success waves-effect" style="display:none" data-toggle='modal' data-target='#log-Modal'>Log</button>
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#purchaseForm', 4)">Adjustment</button>
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
<div class="modal fade" id="large-Modal" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Material Purchase</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" id="materialForm">
                <input type="hidden" name="poitem_id" id="poitem_id">
                <input type="hidden" name="part_supplier_id" id="part_supplier_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Part Supplier</label>
                        <div class="col-sm-9">
                            <input type="text" name="part_name" id="part_name" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Qty</label>
                        <div class="col-sm-5">
                            <input type="number" name="qty" id="qty" class="form-control" onInput="countTotal()" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Qty NG Rate (%) *</label>
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-prepend" id="basic-addon2">
                                    <label class="input-group-text">%</label>
                                </span>
                                <input type="number" name="qty_ng_rate" id="qty_ng_rate" class="form-control" onInput="countTotal()" placeholder="in percent" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Stock</label>
                        <div class="col-sm-5">
                            <input type="number" name="stock" id="stock" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Order</label>
                        <div class="col-sm-5">
                            <input type="number" name="order" id="order" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Price</label>
                        <div class="col-sm-5">
                            <input type="text" name="price" id="price" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Total</label>
                        <div class="col-sm-5">
                            <input type="text" name="total" id="total" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">PPN (0,1)</label>
                        <div class="col-sm-5">
                            <input type="text" name="ppn" id="ppn" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Total + PPN</label>
                        <div class="col-sm-5">
                            <input type="text" name="total_ppn" id="total_ppn" class="form-control" readonly>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveMaterial()">Save</button>
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
    var monthText =  {!! json_encode(getMonth()) !!};

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
            "ajax": '/process/adjustment-purchase-order/load/'+month_periode+'/'+year_periode,
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
        $("#purchaseForm").attr("action", "/process/adjustment-purchase-order/update");
        await getData('/process/adjustment-purchase-order/get/'+data.po_id).then(function(result){
            fillToForm(result);
            fillToLog(result.log_po);
            $("#logProcess").show();
        });

    }

    function fillToForm(data){
        $("#po_id").val(data.po_id);
        $("#po_number").val(data.po_number);
        $("#supplier").val(data.supplier.supplier_name);
        
        $("#po_date").val(data.po_date);
        $("#month").val(monthText[data.month_periode - 1 ].month_name);
        $("#year").val(data.year_periode);
        $("#Htotal").val(data.price);
        $("#Hppn").val(data.ppn);
        $("#Htotal_ppn").val(data.total_price);
        if(data.additional == 1){
            $(".adds").hide();
        } else {
            $(".adds").show();
        }
        $("#bodyMaterial").html("");
        addHeaderColomn(data.month_periode);
        addMaterial(data);
        
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
        $('#partTable').DataTable().destroy();
        $("#bodyMaterial").html("");
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form, status){
        $("#status_process").val(status);
        saveData(form, function() {
            periodeChangeFilter();
            loadSelect2();
            $('#partTable').DataTable().destroy();
            $('#bodyMaterial').html('');
            $("#input").hide();
            $("#table").show();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            periodeChangeFilter();
        });
    }

    function countTotal(e, data){
        
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
            if(data.supplier.is_ppn == 1)
                ppn = parseFloat(total) * 0.1;
            total_ppn = parseFloat(total) + parseFloat(ppn);
        }
        
        tr.find(".qty_total").val(qty_total);
        tr.find(".order").val(order);
        tr.find(".total").val(total);
        tr.find(".ppn").val(ppn);
        tr.find(".total_ppn").val(total_ppn);

    }

    function countTotalAdditional(e, data){
        
        var tr = $(e).parent().parent();
        var qty = tr.find(".qty").val();
            qty = qty ? qty : 0;

        var stock = tr.find(".stock").val();
            stock = stock ? stock : 0;

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

            //order = qty - stock;
            var sisa = qty % packing;
            order = sisa > 0 ? parseFloat(qty) + (parseFloat(packing) - parseFloat(sisa)) : parseFloat(qty);
            order = order < 0 ? 0 : order;
            total = parseFloat(price) * parseFloat(order);
            if(data.supplier.is_ppn == 1)
                ppn = parseFloat(total) * 0.1;
            total_ppn = parseFloat(total) + parseFloat(ppn);
        }
        
        tr.find(".order").val(order);
        tr.find(".total").val(total);
        tr.find(".ppn").val(ppn);
        tr.find(".total_ppn").val(total_ppn);

        countHeaderTotal();
    }

    async function addMaterial(data){
        var material = '';
        var forcastList = await getData('/process/forecast/getForPO/'+ data.supplier.supplier_id +'/'+ data.month_periode +'/'+ data.year_periode);
        
        $('#partTable').DataTable().destroy();
        
        data.po_items.forEach(function(item) {
            var dataForecast = forcastList.filter(a => a.part_supplier_id === item.part_supplier_id);
            
            var forcast = data.additional == 1 ? "display:none":"";
            material += '<tr>';
            material +='<td><input type="hidden" name="poitem_id[]" value="'+ item.poitem_id +'" >'+ item.part_supplier.part_name +'</td>';
            material +='<td>'+ item.part_supplier.divisi.divisi_name +'</td>';
            material +='<td>'+ item.part_supplier.unit.unit_name +'</td>';
            if(data.additional == 1){
                material +='<td> <input type="number" name="qty[]" class="qty form-control tright" style="width:70px" value="'+ item.qty +'" onInput="countTotalAdditional(this)" required></td>';
                material +='<td style="display:none"> <input type="number" name="ng_rate[]" class="ng_rate form-control tright" style="width:90px;" value="'+ item.qty_ng_rate +'" onInput="countTotal(this)"></td>';
                material +='<td style="display:none"> <input type="number" name="buffer_stock[]" class="buffer_stock form-control tright" style="width:90px;" value="'+ item.buffer_stock +'" onInput="countTotal(this)"></td>';
                material +='<td style="display:none"> <input type="number" name="qty_total[]" class="qty_total form-control tright" style="width:70px;" readonly></td>';
            } else {
                material +='<td> <input type="number" name="qty[]" class="qty form-control tright" style="width:70px" value="'+ item.qty +'" readonly></td>';
                material +='<td> <input type="number" name="ng_rate[]" class="ng_rate form-control tright" style="width:90px" value="'+ item.qty_ng_rate +'" onInput="countTotal(this)"></td>';
                material +='<td> <input type="number" name="buffer_stock[]" class="buffer_stock form-control tright" style="width:90px" value="'+ item.buffer_stock +'" onInput="countTotal(this)"></td>';
                material +='<td> <input type="number" name="qty_total[]" class="qty_total form-control tright" style="width:70px" readonly></td>';
            }
            material +='<td> <input type="number" name="stock[]" class="stock form-control tright" style="width:70px" value="'+ item.stock +'" readonly></td>';
            material +='<td> <input type="number" name="standard_packing[]" class="standard_packing form-control tright" style="width:90px" value="'+ item.part_supplier.standard_packing +'" readonly></td>';
            material +='<td> <input type="number" name="order[]" class="order form-control tright" style="width:70px" readonly></td>';
            material +='<td> <input type="number" name="price[]" class="price form-control tright" style="width:90px" value="'+ item.price +'" readonly></td>';
            material +='<td> <input type="number" name="total[]" class="total form-control tright" style="width:100px" readonly></td>';
            material +='<td> <input type="number" name="ppn[]" class="ppn form-control tright" style="width:100px" readonly></td>';
            material +='<td> <input type="number" name="total_ppn[]" class="total_ppn form-control tright" style="width:100px" readonly></td>';
            var m1 = dataForecast.filter(a => a.next_month === "1");
            material +='<td style="'+ forcast +'"> <input type="number" class="forecast form-control tright" style="width:80px" value="'+ ( m1 != "" ? Math.ceil(m1[0].totalqty) : "") +'" readonly></td>';
            var m2 = dataForecast.filter(a => a.next_month === "2");
            material +='<td style="'+ forcast +'"> <input type="number" class="forecast form-control tright" style="width:80px" value="'+ ( m2 != "" ? Math.ceil(m2[0].totalqty) : "") +'" readonly></td>';
            var m3 = dataForecast.filter(a => a.next_month === "3");
            material +='<td style="'+ forcast +'"> <input type="number" class="forecast form-control tright" style="width:80px" value="'+ ( m3 != "" ? Math.ceil(m3[0].totalqty) : "") +'" readonly></td>';
            var m4 = dataForecast.filter(a => a.next_month === "4");
            material +='<td style="'+ forcast +'"> <input type="number" class="forecast form-control tright" style="width:80px" value="'+ ( m4 != "" ? Math.ceil(m4[0].totalqty) : "") +'" readonly></td>';
            var m5 = dataForecast.filter(a => a.next_month === "5");
            material +='<td style="'+ forcast +'"> <input type="number" class="forecast form-control tright" style="width:80px" value="'+ ( m5 != "" ? Math.ceil(m5[0].totalqty) : "") +'" readonly></td>';
            material +='</tr>';
        });
        $('#bodyMaterial').append(material);
        var table = $('#partTable').DataTable({
            "filter": true,
            "bPaginate": false,
            "bLengthChange": false,
            "initComplete": function(settings, json) {
                if(data.additional == 1){
                    $('.qty').each(function(i) {
                        countTotalAdditional(this, data);
                    });
                } else {
                    $('.ng_rate').each(function(i) {
                        countTotal(this, data);
                    });
                }
            }
        });
        $('#partTable_wrapper .row').first().find('div:first').remove();
        $('#partTable_filter').css("text-align", "left");
        
    }

    function addHeaderColomn(month_periode){
        var monthList = {!! json_encode($monthList) !!};
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
        $(table.column(14).header()).text(month1.month_name);
        $(table.column(15).header()).text(month2.month_name);
        $(table.column(16).header()).text(month3.month_name);
        $(table.column(17).header()).text(month4.month_name);
        $(table.column(18).header()).text(month5.month_name);
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



</script>
@endsection

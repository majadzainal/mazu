@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">BUDGETING TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Forecast</a></li>
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
                                    <h5>Budgeting</h5>
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
                                        <table id="forecastTable" class="table table-striped table-bordered nowrap dt-responsive width-100">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Sales Order</th>
                                                    <th>Periode</th>
                                                    <th>Part Supplier</th>
                                                    <th>Qty Budgeting</th>
                                                    <th>Unit</th>
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
                                    <h5>Detail budgeting by sales order</h5>
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
                                        <label class="col-sm-2 col-form-label">Sales Order Number </label>
                                        <div class="col-sm-5">
                                            <input type="input" readonly name="so_number" id="so_number" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Customer </label>
                                        <div class="col-sm-10">
                                            <input type="input" readonly name="customer_id" id="customer_id" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Month Periode </label>
                                        <div class="col-sm-5">
                                            <input type="input" readonly name="month_periode" id="month_periode" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Year Periode </label>
                                        <div class="col-sm-5">
                                            <input type="input" readonly name="year_periode" id="year_periode" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Date Sales Order</label>
                                        <div class="col-sm-5">
                                            <input type="date" readonly name="so_date" id="so_date" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">PO Number Customer</label>
                                        <div class="col-sm-5">
                                            <input type="text" readonly name="po_number_customer" id="po_number_customer" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">PO Date Customer</label>
                                        <div class="col-sm-5">
                                            <input type="date" name="po_date_customer" id="po_date_customer" readonly class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group" style="text-align:left;">
                                        <hr>
                                        <label class="col-sm-12 col-form-label"><h4> Sales Order Product</h4></label>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Product</th>
                                                    <th>Qty</th>
                                                    <th>Unit</th>
                                                    <th>Price</th>
                                                    <th>Total Price</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodyPart">
                                                <tr>
                                                    <td>
                                                        <input type="text" name="part_customer_id[]" class="part_customer_id form-control col-sm-12" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty[]" class="qty form-control col-sm-12" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="unit[]" class="unit form-control col-sm-12" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="price[]" class="price form-control col-sm-12" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="total_price[]" class="total_price form-control col-sm-12" readonly>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group" style="text-align:left;">
                                        <hr>
                                        <label class="col-sm-12 col-form-label"><h4> Part Supplier Budgeting</h4></label>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Part Supplier</th>
                                                    <th>Qty Budgeting</th>
                                                    <th>Unit</th>
                                                    <th>Price</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodyPartSupplier">
                                                <tr>
                                                    <td>
                                                        <input type="text" name="part_supplier_id[]" class="part_supplier_id form-control col-sm-12" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="qty[]" class="qty form-control col-sm-12" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="unit[]" class="unit form-control col-sm-12" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="price[]" class="price form-control col-sm-12" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" name="total_price[]" class="total_price form-control col-sm-12" readonly>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group row modal-footer">
                                        <div class="col-sm-12" style="text-align:right;">
                                            <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
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

    function loadData(month_periode, year_periode){
        var monthList = {!! json_encode($monthList) !!};
        $('#forecastTable').DataTable().destroy();
        var table = $('#forecastTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/forecast/load/'+month_periode+'/'+year_periode,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "sales_order.so_number" },
                {  "mRender": function (data, type, row, num) {
                        var month = monthList.find(a => a.month === parseInt(row.month_periode));
                        return month.month_name +" - "+ row.year_periode + " ";
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.part_supplier.part_number+" - "+row.part_supplier.part_name + " ";
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return Number(row.qty).toFixed(8);
                    }
                },
                { "data": "part_supplier.unit.unit_name" },
                {
                    "mData": "sales_order_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('read', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info ' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i> | detail</button>";
                        @endif
                        return button;
                    }
                },
            ]
        });
    }


    async function return_value(e, data){
        var btn = $(e).attr("btn");
        var monthList = {!! json_encode($monthList) !!};
        $("#so_number").val(data.sales_order.so_number);
        $("#customer_id").val(data.sales_order.customer.business_entity +". "+ data.sales_order.customer.customer_name);
        $("#month_periode").val(monthList.find(a => a.month === parseInt(data.sales_order.month_periode)).month_name);
        $("#year_periode").val(data.sales_order.year_periode);
        $("#so_date").val(data.sales_order.so_date);
        $("#po_number_customer").val(data.sales_order.po_number_customer);
        $("#po_date_customer").val(data.sales_order.po_date_customer);

        justFillToTablePart(data.sales_order);
        await getData('/process/budgeting/get/'+data.sales_order_id).then(function(result){
            justFillToTablePartSupplier(result);
        });

        $("#table").hide();
        $("#input").show();
        document.documentElement.scrollTop = 0;
    }

    function justFillToTablePart(sales_order){
        $("#bodyPart").html('');
        if(sales_order.so_items.length >= 1){
            sales_order.so_items.forEach(function(item) {
                addPartSalesOrder(item);
            });
        }
        addSummary(sales_order.total_price);
    }

    function addSummary(total_price){
        var part = '<tr>';
        part += '<td></td>';
        part += '<td></td>';
        part += '<td></td>';
        part += '<td class="text-right"><label class="col-form-label"><strong>Total : </strong></label></td>';
        part += '<td>';
        part += '<input type="text" value="'+ numbering(total_price) +'" name="total_price[]" class="total_price  text-right form-control col-sm-12" readonly>';
        part += '</td>';
        part += '</tr>';

        $('#bodyPart').append(part);
    }

    function justFillToTablePartSupplier(items){
        $("#bodyPartSupplier").html('');
        $total_price = 0;
        if(items.length >= 1){
            items.forEach(function(item) {
                addPartSupplier(item);
                $total_price += parseFloat(item.total_price);
            });
        }

        addSummarySupplier($total_price);
    }

    function addSummarySupplier(total_price){
        var part = '<tr>';
        part += '<td></td>';
        part += '<td></td>';
        part += '<td></td>';
        part += '<td class="text-right"><label class="col-form-label"><strong>Total : </strong></label></td>';
        part += '<td>';
        part += '<input value="'+ numbering(total_price) +'" name="total_price[]" class="total_price text-right form-control col-sm-12" readonly>';
        part += '</td>';
        part += '</tr>';

        $('#bodyPartSupplier').append(part);
    }

    function addPartSalesOrder(item){
        var part = '<tr>';
        part += '<td>';
        part += '<input type="text" value="'+ item.part_customer.part_name +' - '+ item.part_customer.part_number +'" name="part_customer_id[]" class="part_customer_id form-control col-sm-12" readonly>';
        part += '</td>';
        part += '<td>';
        part += '<input type="number" value="'+ item.qty +'" name="qty[]" class="qty form-control col-sm-12" readonly>';
        part += '</td>';
        part += '<td>';
        part += '<input type="text" value="'+ item.part_customer.unit.unit_name +'"  name="unit[]" class="unit form-control col-sm-12" readonly>';
        part += '</td>';
        part += '<td>';
        part += '<input type="text" value="'+ numbering(item.price) +'" name="price[]" class="price text-right form-control col-sm-12" readonly>';
        part += '</td>';
        part += '<td>';
        part += '<input type="text" value="'+ numbering(item.total_price) +'" name="total_price[]" class="total_price text-right form-control col-sm-12" readonly>';
        part += '</td>';
        part += '</tr>';

        $('#bodyPart').append(part);
    }
    function addPartSupplier(item){
        var part = '<tr>';
        part += '<td>';
        part += '<input type="text" value="'+ item.part_supplier.part_name +' - '+ item.part_supplier.part_number +'" name="part_supplier_id[]" class="part_supplier_id form-control col-sm-12" readonly>';
        part += '</td>';
        part += '<td>';
        part += '<input type="text" value="'+ numbering(Number(item.qty).toFixed(8)) +'" name="qty[]" class="qty form-control col-sm-12" readonly>';
        part += '</td>';
        part += '<td>';
        part += '<input type="text" value="'+ item.part_supplier.unit.unit_name +'" name="unit[]" class="unit form-control col-sm-12" readonly>';
        part += '</td>';
        part += '<td>';
        part += '<input type="text" value="'+ numbering(item.total_price / item.qty) +'" name="price[]" class="price text-right form-control col-sm-12" readonly>';
        part += '</td>';
        part += '<td>';
        part += '<input type="text" value="'+ numbering(item.total_price) +'" name="total_price[]" class="total_price text-right form-control col-sm-12" readonly>';
        part += '</td>';
        part += '</tr>';

        $('#bodyPartSupplier').append(part);
    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function cancelInit(e){
        deleteConfirm(e, function() {
            periodeChangeFilter();
            $('.closeForm').click();
        });
    }
</script>
@endsection

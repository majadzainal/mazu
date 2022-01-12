@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">INVENTORY PART SUPPLIER</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Inventory</a></li>
                        <li class="breadcrumb-item"><a href="#!">Inventory</a></li>
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
                                    <h5>Inventory</h5>
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

                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th>Nama Supplier</th>
                                                    <th>Divisi</th>
                                                    <th>Part</th>
                                                    <th>UOM</th>
                                                    <th>Stock</th>
                                                    <th>Minimum Stock</th>
                                                    <th>Deficit Stock</th>
                                                    <th>Price</th>
                                                    <th>Log</th>
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
                <h4 class="modal-title" id="defaultModalLabel" >Part Supplier</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Month</label>
                    <div class="col-sm-4">
                        <select name="month" id="month" class="js-example-placeholder col-sm-12" onChange="periodeChange()" required>
                            <option value="">--Select--</option>
                            @foreach(getMonth() as $ls)
                                <option value="{{ $ls['month'] }}">{{ $ls['month_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="col-sm-2 col-form-label">Year</label>
                    <div class="col-sm-4">
                        <select name="year" id="year" class="js-example-placeholder col-sm-12" onChange="periodeChange()" required>
                            <option value="">--Select--</option>
                            @foreach(getYearPeriode() as $ls)
                                <option value="{{ $ls['year'] }}">{{ $ls['year'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="dt-responsive table-responsive">
                    <table id="logTable" class="table table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th width="2%">No.</th>
                                <th>Part</th>
                                <th>Qty</th>
                                <th>Type</th>
                                <th>Warehouse</th>
                                <th>Date Log</th>
                                <th>Description</th>
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
@include('layouts.footerIn')
<script>
    var part_id = "";
    $(document).ready(function() {
        loadData();
    } );

    function loadData(){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "ajax": '/process/inventory/part-supplier/load',
            "rowsGroup": [1],
            "aoColumns": [
                { "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "mRender": function (data, type, row, num) {
                        return row.supplier.business_entity+" "+row.supplier.supplier_name;
                    }
                },
                { "data": "divisi.divisi_name" },
                { "data": "part_name" },
                { "data": "unit.unit_name" },
                { "data": "stock" },
                { "data": "minimum_stock" },
                { "mRender": function (data, type, row, num) {
                        var deficit = row.stock - row.minimum_stock;
                        return deficit;
                    }
                },
                { "data": "part_price_active.price" },
                {
                    "mData": "part_supplier_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('read', $MenuID))
                        button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick=return_value('"+ data +"') >&nbsp;<i class='icofont icofont-table'></i></button>";
                        @endif
                        return button;
                    }
                }
            ]
        });
    }

    function return_value(id){
        var total_qty = 0;
        $('#logTable').DataTable().destroy();
        var table = $('#logTable').DataTable({
            "ajax": '/log/part-stock/get-data-part-stock/'+ id,
            "scrollY": "290px",
            "scrollCollapse": true,
            "paging":         false,
            "aoColumns": [
                { "mRender": function (data, type, row, num) {
                        if (num.row == 0)
                            total_qty = 0;
                        if(row.type == "IN"){
                            total_qty = total_qty + parseInt(row.qty);
                        } else if(row.type == "OUT"){
                            total_qty = total_qty - parseInt(row.qty);
                        }

                        return num.row+1;
                    }
                },
                { "data": "part_supplier.part_name" },
                { "data": "qty" },
                { "data": "type" },
                { "data": "warehouse.warehouse_name" },
                { "data": "date_log" },
                { "data": "description" }
            ],
            "initComplete": function(settings, json) {
                var row = '<tr>';
                    row += '<td colspan="2">TOTAL QTY</td>';
                    row += '<td colspan="4">'+ total_qty +'</td>';
                    row += '</tr>';

                $("#logTable").append(
                    $('<tfoot/>').append(row)
                );
            }
        });

        part_id = id;


    }

    function periodeChange(){

        var total_qty = 0;
        var month = $("#month").val();
        var year = $("#year").val();

        if (month !="" && year != ""){
        $('#logTable').DataTable().destroy();
            var table = $('#logTable').DataTable({
                "ajax": '/log/part-stock/get-data-part-stock-periode/'+ part_id +'/'+ month +'/'+ year,
                "scrollY":        "290px",
                "scrollCollapse": true,
                "paging":         false,
                "aoColumns": [
                    { "mRender": function (data, type, row, num) {
                            if (num.row == 0)
                                total_qty = 0;

                            if(row.type == "IN"){
                                total_qty = total_qty + parseInt(row.qty);
                            } else if(row.type == "OUT"){
                                total_qty = total_qty - parseInt(row.qty);
                            }
                            $("#total_qty").text(total_qty);
                            return num.row+1;
                        }
                    },
                    { "data": "part_supplier.part_name" },
                    { "data": "qty" },
                    { "data": "type" },
                    { "data": "warehouse.warehouse_name" },
                    { "data": "date_log" },
                    { "data": "description" }
                ],
                "initComplete": function(settings, json) {
                    var row = '<tr>';
                        row += '<td colspan="2">TOTAL QTY</td>';
                        row += '<td colspan="4">'+ total_qty +'</td>';
                        row += '</tr>';

                    $("#logTable").append(
                        $('<tfoot/>').append(row)
                    );
                }
            });
        }
    }

</script>
@endsection

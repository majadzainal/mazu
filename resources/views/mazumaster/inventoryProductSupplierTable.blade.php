@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">INVENTORY MATERIAL / BAHAN TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Material / Bahan</a></li>
                        <li class="breadcrumb-item"><a href="#!">Inventory Material / Bahan</a></li>
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
                                <div class="card-block" id="table">
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTablesInventory" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th width="11%">Action</th>
                                                    <th>Material / Bahan</th>
                                                    <th>Stock</th>
                                                    <th>Warehouse</th>
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
                <h4 class="modal-title" id="defaultModalLabel" >Log Stock Material Bahan</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="dt-responsive table-responsive">
                    <table id="logTable" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="2%">No.</th>
                                <th>Material / Bahan</th>
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
    $(document).ready(function() {
        loadDataInventory();
        loadSelect2();
    });

    function loadSelect2(){
        $(".js-example-placeholder").select2({
            placeholder: "--select--"
        });
    }

    function loadDataInventory(outlet_id){
        $('#newTablesInventory').DataTable().destroy();
        var table = $('#newTablesInventory').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/master/product-supplier-inventory/load-product',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "stock_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('read', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value_inventory("+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-table'></i></button>";
                        @endif
                        return button;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.product_code+"-"+row.product_name;
                    }
                },
                { "data": "stock" },
                { "data": "stock_warehouse.warehouse.warehouse_name" },
            ]
        });
    }

    function return_value_inventory(data){
        var total_qty = 0;
        $('#logTable').DataTable().destroy();
        var table = $('#logTable').DataTable({
            "ajax": '/master/product-supplier-inventory/load-log-stock/'+data.product_supplier_id,
            "scrollY": "290px",
            "scrollCollapse": true,
            "paging":  false,
            "aoColumns": [
                { "mRender": function (data, type, row, num) {
                    console.log(row);
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
                { "mRender": function (data, type, row, num) {
                        return row.product_supplier.product_code+"-"+row.product_supplier.product_name;
                    }
                },
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
</script>
@endsection

@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">PRODUCTION REPORT</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Production</a></li>
                        <li class="breadcrumb-item"><a href="#!">Production Report</a></li>
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
                        <div class="col-sm-12" id="filter">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Production Report</h5>
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
                                    <form id="reportForm">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Plant</label>
                                            <div class="col-sm-8">
                                                <select name="plant_id" id="plant_id" class="select-style col-sm-12">
                                                    <option value="">--Select--</option>
                                                    @foreach($plantList as $ls)
                                                        <option value="{{ $ls->plant_id }}">{{ $ls->plant_name."+".$ls->description }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!--<div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Part Customer</label>
                                            <div class="col-sm-8">
                                                <select name="part_customer_id" id="part_customer_id" class="select-style col-sm-12">
                                                    <option value="">--Select--</option>
                                                    @foreach($partCustomerList as $ls)
                                                        <option value="{{ $ls->part_customer_id }}">{{ $ls->part_name."+Customer : ".$ls->customer->business_entity." ".$ls->customer->customer_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>-->
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Date From *</label>
                                            <div class="col-sm-3">
                                                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ date('Y-m-d') }}">
                                            </div>
                                            <label class="col-sm-2 col-form-label">Date To *</label>
                                            <div class="col-sm-3">
                                                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ date('Y-m-d') }}">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-8">
                                                <button type="button" class="btn btn-default waves-effect cancelBtn">cancel</button>
                                                <button type="button" class="btn btn-info waves-effect waves-light" onClick="getProductionReport()">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12" id="recap">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Production Report</h5>
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
                                        <label class="col-sm-2 col-form-label">Plant</label>
                                        <label class="col-sm-10 col-form-label" id="plant"></label>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Date</label>
                                        <label class="col-sm-10 col-form-label" id="dateRange"></label>
                                    </div>
                                    
                                    <div class="dt-responsive table-responsive">
                                        <table id="partProduction" class="table table-striped table-bordered nowrap" width="100%">
                                            <thead id="headPart">
                                                <tr>
                                                    <th width="50%">Part Customer</th>
                                                    <th width="20%">Qty</th>
                                                    <th width="30%">Date</th>
                                                </tr>
                                            </thead>
                                            
                                        </table>
                                    </div>
                                    
                                    <div class="dt-responsive table-responsive">
                                        <table id="rawMatTable" class="table table-striped table-bordered nowrap" width="100%">
                                            <thead id="headPart">
                                                <tr>
                                                    <th width="30%">Material</th>
                                                    <th width="10%">Qty</th>
                                                    <th width="10%">Unit</th>
                                                    <th width="20%">Warehouse</th>
                                                    
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
                                    <div class="form-group row modal-footer">
                                        <div class="col-sm-12" style="text-align:right;">
                                            <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                            <button type="button" class="btn btn-primary waves-effect" onclick="printProductionReport()">Print</button>
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
    
    $(document).ready(function() {
        $("#recap").hide();
	});

    $('.cancelBtn').click(function(e) {
        $("#reportForm").trigger("reset");
        loadSelect2();
    })

    $('.closeForm').click(function(e) {
        $("#recap").hide();
        $("#filter").show();
    })

    async function getProductionReport(){
        var plant = $("#plant_id").val() != '' ? $("#plant_id").val() : 0 ;
        var dateFrom = $("#date_from").val();
        var dateTo = $("#date_to").val();
        var part = [];
        if (dateFrom <= dateTo){
            await getData('/production/report/get/'+ plant +'/'+ dateFrom +'/'+ dateTo).then(function(result){
                
                $("#plant").text( (plant == 0 ? '-' : $("#plant_id option:selected").text().split("+")[0]));
                $("#dateRange").text((dateFrom == dateTo ? dateFrom : dateFrom +' to '+ dateTo));
                rawMaterial(result.rawMatList);
                partProduction(result.partProductionList)
                $("#filter").hide();
                $("#recap").show();
            });
        } else {
            swal('Info', 'Check filter date again!', 'info');
        }

    }

    function rawMaterial(rawMatList){
        $('#rawMatTable').DataTable().destroy();
        var table = $('#rawMatTable').DataTable({
            "data": rawMatList,
            "filter": true,
            "bPaginate": false,
            "bLengthChange": false,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        part = row.part_supplier != null ? row.part_supplier.part_name : row.part_customer.part_name;
                        return part ;
                    }
                },
                { "data": "qty" },
                { "data": "units.unit_name" },
                { "data": "warehouse.warehouse_name" }
            ],
            "initComplete": function(settings, json) {
                loadSelect2();
            }
        });
        $('#rawMatTable_wrapper .row').first().find('div:first').html('<br><b>Raw Material</b>');
    }

    function partProduction(partProductionList){
        $('#partProduction').DataTable().destroy();
        var table = $('#partProduction').DataTable({
            "data": partProductionList,
            "filter": true,
            "bPaginate": false,
            "bLengthChange": false,
            "aoColumns": [
                { "data": "part_customer.part_name" },
                { "data": "qty" },
                { "data": "schedule_date" }
            ]
        });

        $('#partProduction_wrapper .row').first().find('div:first').html('<br><b>Part Production</b>');
    }

    function printProductionReport(){
        var plant = $("#plant_id").val() != '' ? $("#plant_id").val() : 0 ;
        var dateFrom = $("#date_from").val();
        var dateTo = $("#date_to").val();
        window.open('/production/report/print/'+ plant +'/'+ dateFrom +'/'+ dateTo, '_blank');
    }

</script>
@endsection

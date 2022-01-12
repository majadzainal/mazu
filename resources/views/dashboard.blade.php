@extends('layouts.headerIn')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/morris.js/css/morris.css') }}">
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">DASHBOARD</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Dashboard</a>
                        </li>
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
                        <!-- task, page, download counter  start -->
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-purple">{{ round($qtySo, 0) }}</h4>
                                            <h6 class="text-muted m-b-0">Sales Order</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="fa fa-bar-chart f-28"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-purple">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <a href="/process/sales-order/table"><p class="text-white m-b-0">Open detail</p></a>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class="fa fa-line-chart text-white f-16"></i>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-green">-</h4>
                                            <h6 class="text-muted m-b-0">Purchase Order</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="fa fa-file-text-o f-28"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-green">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <a href="/process/purchase-order/table"><p class="text-white m-b-0">Open detail</p></a>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class="fa fa-line-chart text-white f-16"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-red">{{ round($qtyBudgeting, 0) }}</h4>
                                            <h6 class="text-muted m-b-0">Budgeting</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="fa fa-calendar-check-o f-28"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-red">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <a href="/process/budgeting/table"><p class="text-white m-b-0">Open detail</p></a>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class="fa fa-line-chart text-white f-16"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-6">
                            <div class="card">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col-8">
                                            <h4 class="text-c-blue">-</h4>
                                            <h6 class="text-muted m-b-0">Production</h6>
                                        </div>
                                        <div class="col-4 text-right">
                                            <i class="fa fa-cubes f-28"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-c-blue">
                                    <div class="row align-items-center">
                                        <div class="col-9">
                                            <a href="#"><p class="text-white m-b-0">Open detail</p></a>
                                        </div>
                                        <div class="col-3 text-right">
                                            <i class="fa fa-line-chart text-white f-16"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- task, page, download counter  end -->
                        <div class="col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Process Machine</h5>
                                    <span></span>
                                </div>
                                <div class="card-block">
                                    <div class="form-group row">
                                        <label class="col-sm-1 col-form-label">Plant</label>
                                        <div class="col-sm-3">
                                            <select name="plant_id" id="plant_id" class="select-style col-sm-12" onchange="getMachineProcess()">
                                                <option value="">--Select--</option>
                                                @foreach($plantList as $ls)
                                                    <option value="{{ $ls->plant_id }}">{{ $ls->plant_name."+".$ls->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="process-machine"></div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Deficit Stock Part Supplier</h5>
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
                                                    <th>Supplier</th>
                                                    <th>Divisi</th>
                                                    <th>Part Name</th>
                                                    <th>Minimum Stock</th>
                                                    <th>Stock</th>
                                                    <th>Deficit Stock</th>
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
@include('layouts.footerIn')
<script src="{{ asset ('/assets/files/bower_components/raphael/js/raphael.min.js') }}"></script>
<script src="{{ asset ('/assets/files/bower_components/morris.js/js/morris.js') }}"></script>
<script>
    $(document).ready(function() {
        loadData();
        getMachineProcess();
    
    } );

    function loadData(){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "ajax": '/dashboard/load-part-supplier',
            "rowsGroup": [1],
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "supplier.supplier_name" },
                { "data": "divisi.divisi_name" },
                { "data": "part_name" },
                { "data": "minimum_stock" },
                { "data": "stock" },
                {  "mRender": function (data, type, row, num) {
                        var deficit = row.minimum_stock - row.stock;
                        return deficit;
                    }
                }
            ]
        });
    }

    async function getMachineProcess(){
        var plant = $("#plant_id").val();
        //var requestDate = $("#request_date").val();
        var part = [];
            await getData('/dashboard/machine-process/'+ (plant == ''? 0 : plant )).then(function(result){
                if (result != "" || plant == ""){
                    mappingData(result);
                } else {
                    swal('Info', $( "#plant_id option:selected" ).text().split("+")[0]+' plant, data not found', 'error');
                } 
            });

    }

    function mappingData(list){
        var processMachine = [];
        var machine = [];
        list.forEach(function(item) {
            var index = processMachine.findIndex(a => a['period'] == item.report_date);
            if (index == -1){
                var data = {};
                data['period'] = item.report_date;
                data[item.code] = item.total_ct;
                
                processMachine.push(data);
                
            } else {
                processMachine[index][item.code] = item.total_ct;
            }

            if(machine.includes(item.code) == false){
                machine.push(item.code);
            } 
        });

        $("#process-machine").empty();
        $("#process-machine svg").remove();
        lineChartMachine(processMachine, machine);
    }

    function lineChartMachine(processMachine, machine) {
        window.lineChart = Morris.Line({
            element: 'process-machine',
            data: processMachine,
            xkey: 'period',
            ykeys: machine,
            labels: machine,
            lineColors: ['#B4C1D7', '#FF9F55', '#6e4e4c', '#34d5eb', '#7d8f91', '#9cb32d', '#4f2c73', '#4f2c73', '#e6d7ca', '#de6c09', '#dede09', '#212117', '#9a989c', '#8f608a'],
            resize: true,
            parseTime: false
        });
        
    }
</script>
@endsection

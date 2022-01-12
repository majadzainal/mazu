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
                        <h5 class="m-b-10">DAILY REPORT PRODUCTION</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Production</a></li>
                        <li class="breadcrumb-item"><a href="#!">Daily Report</a></li>
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
                                    <h5>Daily Report</h5>
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
                                                    <th width="15%">Plant</th>
                                                    <th width="15%">Date Report</th>
                                                    <th width="25%">Note</th>
                                                    <th width="15%">Status</th>
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
                                    <h5>Daily Report</h5>
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
                                    <form action="/production/daily-report/add" method="post" enctype="multipart/form-data" id="reportForm">
                                        @csrf
                                        <input type="hidden" name="report_id" id="report_id">
                                        <input type="hidden" name="plant" id="plant">
                                        <input type="hidden" name="rdate" id="rdate">
                                        <input type="hidden" name="is_status" id="is_status">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Plant *</label>
                                            <div class="col-sm-10">
                                                <select name="plant_id" id="plant_id" class="select-style col-sm-12" onchange="getProduction()" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($plantList as $ls)
                                                        <option value="{{ $ls->plant_id }}">{{ $ls->plant_name."+".$ls->description }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Date *</label>
                                            <div class="col-sm-5">
                                                <input type="date" name="report_date" id="report_date" class="form-control" onchange="getProduction()" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Note </label>
                                            <div class="col-sm-5">
                                                <textarea name="note" id="note" class="form-control"></textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-12 col-form-label"><b>Part Production</b></label>
                                        </div>
                                        <div class="dt-responsive table-responsive">
                                            <table id="partProduction" class="table table-striped table-bordered nowrap">
                                                <thead id="headPart">
                                                    <tr>
                                                        <th width="30%">Part Name</th>
                                                        <th width="20%">Production Schedule/Plan</th>
                                                        <th width="17%">Actual</th>
                                                        <th width="17%">Over Time</th>
                                                        <th width="17%">Total</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                        <div class="form-group row">
                                            <hr>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-8"></label>
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <button type="button" class="save btn btn-info waves-effect waves-light" onClick="saveInit('#reportForm', 1)">Save Draft</button>
                                                <button type="button" class="save btn btn-primary waves-effect waves-light" onClick="saveInit('#reportForm', 2)">Submit</button>

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
                <h4 class="modal-title" id="defaultModalLabel" >Sending Daily Report Production</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/production/daily-report/send" method="post" enctype="multipart/form-data" id="sendForm">
                @csrf
                <input type="hidden" name="req_id" id="req_id">
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
@php
    $monthList = getMonth();
@endphp
@include('layouts.footerIn')
<script>
    var dataPart = [];
    
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
            "ajax": '/production/daily-report/load/'+month_periode+'/'+year_periode,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {

                        return row.plant.plant_name;
                    }
                },
                { "data": "report_date" },
                { "data": "description" },
                {  "mRender": function (data, type, row, num) {
                        var arrStatus = {!! json_encode(getStatusRequestRawMat()) !!};
                        return arrStatus[row.is_status];
                    }
                },
                {
                    "mData": "report_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete ??' data-url='/production/daily-report/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        @if(isAccess('read', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-inverse btn-icon' onClick=printDailyReport('"+ data +"')>&nbsp;<i class='icofont icofont-download'></i></button>";
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
        if (btn == "edit"){
            $("#reportForm").attr("action", "/production/daily-report/update");
            $("#report_id").val(data.report_id);
            $("#plant_id").val(data.plant_id);
            $("#plant").val(data.plant_id);
            $("#report_date").val(data.report_date);
            $("#rdate").val(data.report_date);
            $("#note").val(data.description);
            if(data.is_status != 1)
                $(".save").hide();
            else 
                $(".save").show();
            
            loadSelect2();
            var arrDataProduction = [];
            data.report_item.forEach(function(item) {
                data = {
                    part_id: item.part_customer_id,
                    part_name: item.part_customer.part_name,
                    qty: item.production_plan,
                    wip: item.is_wip,
                    part_id_induk: item.reference_id,
                    actual: item.actual,
                    over_time: item.over_time,
                    total: item.total
                };
                arrDataProduction.push(data);
            });
            partProduction(arrDataProduction);
            
        } else {
            itemPO = [];
            $("#reportForm").trigger("reset");
            $("#reportForm").attr("action", "/production/daily-report/add");
            $('#partProduction tbody').remove();
            loadSelect2();
        }        
    }

    async function getProduction(){
        var plant = $("#plant_id").val();
        var reportDate = $("#report_date").val();
        var part = [];
        if (plant != "" && reportDate != ""){
            await getData('/production/daily-report/getDailyReport/'+ plant +'/'+ reportDate).then(function(result){
                var dataList = mapProductionWip(result);
                partProduction(dataList); 
            });
        }
    }

    function mapProductionWip(partProductionList){
        var arrDataProduction = [];

        partProductionList.forEach(function(item) {
            data = {
                part_id: item.part_customer_id,
                part_name: item.part_customer.part_name,
                qty: item.qty,
                wip: 0,
                part_id_induk: 0,
                actual: '',
                over_time: '',
                total: ''
            };

            arrDataProduction.push(data);

            item.bom.bom_item.forEach(function(bom) {
                if(bom.wip != null){
                    data = {
                        part_id: bom.wip.part_customer_id,
                        part_name: bom.wip.part_name,
                        qty: 0,
                        wip: 1,
                        part_id_induk: item.part_customer_id,
                        actual: '',
                        over_time: '',
                        total: ''
                    };
                    arrDataProduction.push(data);        
                }
            });
    
        });

        return arrDataProduction;
    }
    
    function partProduction(partProductionList){
        
        $('#partProduction').DataTable().destroy();
        var table = $('#partProduction').DataTable({
            "data": partProductionList,
            "filter": false,
            "bPaginate": false,
            "bLengthChange": false,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        if (row.wip == 0){
                            return row.part_name+'<input type="hidden" name="part_customer_id[]" class="part_customer_id form-control" value="'+row.part_id+'">';
                        } else {
                            return '<span style="padding-left:50px;">&bull; WIP : '+row.part_name+'</span><input type="hidden" name="part_customer_id[]" class="part_customer_id form-control" value="'+row.part_id+'">';
                        }
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="number" name="production_plan[]" class="production_plan form-control" value="'+ row.qty +'" >';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="number" name="actual[]" class="actual form-control" onInput="sumTotal(this)" value="'+ row.actual +'">';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="number" name="over_time[]" class="over_time form-control" onInput="sumTotal(this)" value="'+ row.over_time +'">';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="number" name="total[]" class="total form-control" value="'+ row.total +'"><input type="hidden" name="wip[]" value="'+ row.wip +'" ><input type="hidden" name="reference_id[]" value="'+ row.part_id_induk +'" >';
                    }
                },
            ]
        });
    }

    function sumTotal(e){
        var tr = $(e).parent().parent();
        var actual = tr.find(".actual").val();
        var over_time = tr.find(".over_time").val();
        if(actual != "" && over_time != ""){
            var total = parseInt(actual) + parseInt(over_time);
            tr.find(".total").val(total);
        }
    }

    
    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form, status){
        if ($('input[name="part_customer_id[]"]').length > 0){
            $("#is_status").val(status);
            saveData(form, function() {
                periodeChangeFilter();
                loadSelect2();
                $('#bodyMaterial').html('');
                $("#input").hide();
                $("#table").show();
            });
        } else {
            swal('Error', 'Raw Material cannot be empty', 'error');
        }
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            periodeChangeFilter();
        });
    }

    function printDailyReport(repot_id){
        window.open('/production/daily-report/print/'+repot_id, '_blank');
    }

    function sendReqRawMat(data){
        $('.mails').tagsinput('removeAll');
        $("#sendForm").trigger("reset");
        $("#req_id").val(data.request_id);
        
    }

    function sendInit(form){
        saveData(form, function() {
            periodeChangeFilter();
            $("#closeModal").click();
        });
    }


</script>
@endsection

@extends('layouts.headerIn')
@section('content')
<style>
    .fc-content{
        text-align: center;
        font-weight: bold;
    }

    .disabled {
        background-color: red;
        color: #FFFFFF;
        cursor: not-allowed;
    }

    .event {
        flex: 0 0 auto;
        font-size: 13px;
        border-radius: 4px;
        padding: 5px;
        margin-bottom: 2px;
        line-height: 14px;
        border: 1px solid #b5dbdc;
        color: #fff;
        text-decoration: none;
        text-align:center;
    }

    .event:hover{
        cursor: pointer;
        color: #000;
    }

    .qty{
        background: #515080;
    }

    .shift1{
        background: #d6a889;
    }

    .shift2{
        background: #7499e3;
    }

    .off{
        background: #F22613;
        vertical-align: middle;
        cursor: default;
        color: #fff;
    }

    .event-desc {
        color: #666;
        margin: 3px 0 7px 0;
        text-decoration: none;	
    }
    
</style>
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">GENERATE SCHEDULE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Generate Schedule</a></li>
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
                                    <h5>Generate Schedule</h5>
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
                                    <form action="/production/generate-schedule/create" method="post" enctype="multipart/form-data" id="generateForm">
                                        @csrf
                                        <input type="hidden" id="scheduleList" name="scheduleList">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Month *</label>
                                            <div class="col-sm-3">
                                                <select name="month" id="month" class="getSO select-style col-sm-12" >
                                                    <option value="">--Select--</option>
                                                    @foreach(getMonth() as $ls)
                                                        <option value="{{ $ls['month'] }}">{{ $ls['month_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label class="col-sm-1 col-form-label">Year *</label>
                                            <div class="col-sm-3">
                                                <select name="year" id="year" class="getSO select-style col-sm-12" >
                                                    <option value="">--Select--</option>
                                                    @foreach(getYearPeriode() as $ls)
                                                        <option value="{{ $ls['year'] }}">{{ $ls['year'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Sales Order *</label>
                                            <div class="col-sm-8">
                                                <select name="so_id" id="so_id" class="select-style col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($soList as $ls)
                                                        <option value="{{ $ls->sales_order_id }}">{{ $ls->so_number."+".$ls->customer->business_entity." ".$ls->customer->customer_name."+".getMonth()[($ls->month_periode - 1)]['month_name']." ".$ls->year_periode }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Generate Date from *</label>
                                            <div class="col-sm-3">
                                                <input type="date" name="date_from" id="date_from" class="form-control">
                                            </div>
                                            <label class="col-sm-1 col-form-label">To *</label>
                                            <div class="col-sm-3">
                                                <input type="date" name="date_to" id="date_to" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-8">
                                                <button type="button" class="btn btn-info waves-effect waves-light" onClick="generateSchedule()">Generate</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="calendarTable table table-sm table-styling table-bordered">
                                                <thead>
                                                    <tr class="table-primary">
                                                        <th>#</th>
                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-10 col-form-label"></label>
                                            <div class="col-sm-2">
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#generateForm')">Save</button>
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
<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Edit Production Schedule</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    <input type="hidden" name="idSO" id="idSO">
                    <input type="hidden" name="idPart" id="idPart">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Schedule Date</span></label>
                        <div class="col-sm-10">
                            <input type="date" name="schedule_date" id="schedule_date" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Shift 1</label>
                        <div class="col-sm-10">
                            <select name="shift1" id="shift1" class="js-example-placeholder col-sm-12" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Shift 2</label>
                        <div class="col-sm-10">
                            <select name="shift2" id="shift2" class="js-example-placeholder col-sm-12" required>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Qty </label>
                        <div class="col-sm-10">
                            <input type="number" name="qty" id="qty" class="form-control" required>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                <button type="button" id="simpan" class="btn btn-primary waves-effect waves-light" >Save</button>
            </div>
        </div>
    </div>
</div>
@include('layouts.footerIn')
<script>
    var scheduleList = [];
    var dayOffList = [];
    var rowSchedule = "";
    var soID = "";

    $(document).ready(function() {
        $('.js-example-placeholder').select2('destroy');
        $('.js-example-placeholder').select2();
        
	});


    $(".getSO").on('change', function(event){
        
        var month = $("#month").val();
        var year = $("#year").val();

        if(month != "" && year != ""){
            setSoList(month, year);
        }
    });

    function setSoList(month, year){
        var soList = {!! json_encode($soList) !!};
        var dataSO = soList.filter(a => a.month_periode === month && a.year_periode === year );
        var arrMonth = {!! json_encode(getMonth()) !!};
        var option = '<option value="">--Select--</option>';

        if(dataSO != ""){    
            dataSO.forEach(function(so) {
                var m = arrMonth.filter(e => e.month == so.month_periode);
                option +='<option value="'+ so.sales_order_id +'" >'+ so.so_number+'+'+so.customer.business_entity+' '+so.customer.customer_name +'+'+ m[0].month_name +" "+ so.year_periode +'</option>';
            });
        }

        $("#so_id").html(option);
        loadSelect2();
    }

    function generateSchedule(){
        var dateFrom = new Date($("#date_from").val());
        var dateTo = new Date($("#date_to").val());
        
        rowSchedule = "";
        var totalDay = 0;
        var rowDate = '<tr class="table-primary">';
        var rowDay = '<tr class="table-info">';
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        rowDate += '<th style="text-align:center;vertical-align:middle" rowspan="2">Part Customer</th>';
        rowDate += '<th style="text-align:center;vertical-align:middle" rowspan="2">Qty</th>';
        var arrdayOff = {!! json_encode($dayOffList) !!};

        for (var d = dateFrom; d <= dateTo; d.setDate(d.getDate() + 1)) {
            var dates = new Date(d);
            var red = "";
            var tempDate = d.getFullYear()+ "-" + (d.getMonth() + 1) + "-" + (d.getDate() < 10 ? "0"+d.getDate() : d.getDate()) ;
            var dayOff = arrdayOff.filter(a => a.day_off == tempDate);
            if(dates.getDay() == 0 || dates.getDay() == 6 || dayOff.length > 0){
                red = 'background-color:red;';
            } else {
                totalDay++;
            }
            rowDate += '<th style="text-align:center" >'+ dates.getDate() +'</th>';
            rowDay += '<th style="text-align:center;'+ red +'" >'+ days[dates.getDay()] +'</th>';

        }

        rowDate +='</tr>';
        rowDay +='</tr>';
        $(".calendarTable thead").html("");
        $(".calendarTable thead").append(rowDate);
        $(".calendarTable thead").append(rowDay);
        
        setScheduleSO(totalDay);
    }

    function setScheduleSO(totalDay){

        var soList = {!! json_encode($soList) !!};
        var so_id = $("#so_id").val();
        var soData = soList.find(a => a.sales_order_id === so_id);
        var arrdayOff = {!! json_encode($dayOffList) !!};
        soID = so_id;
        
        var soSchedule = '';
        soData.so_items.forEach(function(item) {
            var tempDay = totalDay;
            var tempQty = item.qty;
            soSchedule += '<tr id="'+ item.part_customer_id +'">';
            soSchedule += '<td>'+ item.part_customer.part_name +'</td>';
            soSchedule += '<td style="text-align:center">'+ item.qty +'</td>';
            
            var dateFrom = new Date($("#date_from").val());
            var dateTo = new Date($("#date_to").val());

            for (var d = dateFrom; d <= dateTo; d.setDate(d.getDate() + 1)) {
                var dates = new Date(d);
                var red = "";
                var tempDate = d.getFullYear()+ "-" + (d.getMonth() + 1) + "-" + (d.getDate() < 10 ? "0"+d.getDate() : d.getDate()) ;
                var dayOff = arrdayOff.filter(a => a.day_off == tempDate);
                if( dayOff.length > 0){
                    soSchedule += '<td style="text-align:center;">'+ (dayOff.length > 0 ? '<div class="event off">'+ dayOff[0].name.split(" ").join("<br>") +'</div>' : '' ) +'</td>';
                } else if(dates.getDay() == 0 || dates.getDay() == 6 ){
                    red = 'background-color:red;';
                    soSchedule += '<td style="text-align:center;'+red+'" ></td>';
                } else {
                    var qty = Math.ceil(tempQty/tempDay);
                    data = {
                        sales_order_id: soData.sales_order_id,
                        part_customer_id: item.part_customer_id,
                        schedule_date: tempDate,
                        qty: qty,
                        shift1: 1,
                        shift2: 1
                    };
                    scheduleList.push(data);
                    var view = setView(data);
                    soSchedule += '<td class="'+ tempDate +'" >'+ view + '</td>';
                    tempDay = tempDay - 1;
                    tempQty = tempQty - qty;
                }
                
            }

            soSchedule +='</tr>';
        });

        $(".calendarTable tbody").html(soSchedule);

    }

    function setView(data){
        var content = '<div class="event qty" data-toggle="modal" data-target="#large-Modal" onClick=returnValue('+ JSON.stringify(data) +')>Qty : '+ data.qty +'</div>';

        if(data.shift1 == 1){
            content += '<div class="event shift1" data-toggle="modal" data-target="#large-Modal" onClick=returnValue('+ JSON.stringify(data) +')>Shift 1</div>';
        }
        if(data.shift2 == 1){
            content += '<div class="event shift2" data-toggle="modal" data-target="#large-Modal" onClick=returnValue('+ JSON.stringify(data) +')>Shift 2</div>';
        }
        return content;
    }

    
    function returnValue(data){ 
        $("#scheduleForm").trigger("reset");
        $("#idSO").val(data.sales_order_id);
        $("#idPart").val(data.part_customer_id);
        $("#schedule_date").val(data.schedule_date);
        $("#shift1").val(data.shift1);
        $("#shift2").val(data.shift2);
        $("#shift1").trigger('change');
        $("#shift2").trigger('change');
        $("#qty").val(data.qty);
    }

    
    $('#simpan').on('click',function(){
        var idSO = $("#idSO").val();
        var idPart = $("#idPart").val();
        var schedule_date = $("#schedule_date").val();
        var shift1 = $("#shift1").val();
        var shift2 = $("#shift2").val();
        var qty = $("#qty").val();
        
        var index = scheduleList.findIndex((a => a.part_customer_id == idPart && a.schedule_date == schedule_date ));
        scheduleList[index].shift1 = shift1;
        scheduleList[index].shift2 = shift2;
        scheduleList[index].qty = qty;

        data = {
            sales_order_id: idSO,
            part_customer_id: idPart,
            schedule_date: schedule_date,
            qty: qty,
            shift1: shift1,
            shift2: shift2
        };
        var view = setView(data);
        var tr = $("#"+idPart);
        tr.find("."+schedule_date).html(view);
        $('#large-Modal').modal('toggle');

     
    });

    function saveInit(form){
        var soList = {!! json_encode($soList) !!};
        var soData = soList.find(a => a.sales_order_id === soID);
        var save = 1;
        soData.so_items.forEach(function(item) {
            var sumQty = scheduleList.filter(a => a.part_customer_id == item.part_customer_id).map(sum => parseInt(sum.qty)).reduce((prev, curr) => prev + curr, 0);
            if (sumQty > item.qty || sumQty < item.qty){
                save = 0;
                swal('Info', item.part_customer.part_name+' part, please check the qty per schedule!!', 'error');
            }

        });
        
        if(save){
            document.getElementById('scheduleList').value = JSON.stringify(scheduleList);
            saveData(form);
        }
    }

    function loadDayOff(){
        var arrdayOff = {!! json_encode($dayOffList) !!};
        arrdayOff.forEach(function(item) {
            var day = '<div class="event off">'+ item.name.split(" ").join("<br>") +'</div>';
            $("tr").find("."+item.day_off).html(day);
            
        });
    }

    
</script>
@endsection

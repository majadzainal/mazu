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
                        <h5 class="m-b-10">PRODUCTION SCHEDULE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Production Schedule</a></li>
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
                                    <h5>Production Schedule</h5>
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
                                        <label class="col-sm-2 col-form-label">Plant *</label>
                                        <div class="col-sm-8">
                                            <select name="plant_id" id="plant_id" class="getData select-style col-sm-12" required>
                                                <option value="">--Select--</option>
                                                @foreach($plantList as $ls)
                                                    <option value="{{ $ls->plant_id }}">{{ $ls->plant_name."+".$ls->description }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Month *</label>
                                        <div class="col-sm-3">
                                            <select name="month" id="month" class="getData js-example-placeholder col-sm-12" required>
                                                <option value="">--Select--</option>
                                                @foreach(getMonth() as $ls)
                                                    <option value="{{ $ls['month'] }}">{{ $ls['month_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Year *</label>
                                        <div class="col-sm-3">
                                            <select name="year" id="year" class="getData js-example-placeholder col-sm-12" required>
                                                <option value="">--Select--</option>
                                                @foreach(getYearPeriode() as $ls)
                                                    <option value="{{ $ls['year'] }}">{{ $ls['year'] }}</option>
                                                @endforeach
                                            </select>
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
                <form action="" method="post" enctype="multipart/form-data" id="scheduleForm">
                    @csrf
                    <input type="hidden" name="idSchedule" id="idSchedule">
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
                <button type="button" id="closeModalImport" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
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
    
    $(document).ready(function() {
        var d = new Date();
        var month = d.getMonth()+1;
        var year = d.getFullYear();
        $('#month').val(month);
        $('#year').val(year);
        $('.js-example-placeholder').select2('destroy');
        $('.js-example-placeholder').select2();
        
	});


    $(".getData").on('change', function(event){
        var plant = $("#plant_id").val();
        var month = $("#month").val();
        var year = $("#year").val();

        if(plant != "" && month != "" && year != ""){
            setDateCalendar(month, year);
            setSchedule(plant, month, year);
        }
    });

    
    function setDateCalendar(month, year){
        var d = new Date();
        d.setFullYear(year, month, 0);
        var lastDate = d.getDate();

        rowSchedule = "";
        var rowDate = '<tr class="table-primary">';
        var rowDay = '<tr class="table-info">';
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        rowDate += '<th style="text-align:center;vertical-align:middle" rowspan="2">Part Customer</th>';
        for(var i=1; i<=lastDate; i++){
            var num = i < 10 ? '0'+i : i;
            var week = new Date(year+'-'+month+'-'+i);
            var red = "";
            rowDate += '<th style="text-align:center">'+ i +'</th>';
            rowDay += '<th style="text-align:center">'+ days[week.getDay()] +'</th>';
            if(week.getDay() == 0 || week.getDay() == 6){
                red = 'style="background-color:red;"';
            }
            rowSchedule += '<td class="'+ year +'-'+ month +'-'+ num +'" '+ red +'></td>';
        }

        //<div class="event">Qty : 12 </div><div class="event">Shift 1</div><div class="event">Shift 2</div><button>Edit</button>
        rowDate +='</tr>';
        rowDay +='</tr>';
        $(".calendarTable thead").html("");
        $(".calendarTable thead").append(rowDate);
        $(".calendarTable thead").append(rowDay);
    }

    async function setSchedule(plant, month, year){
        await getData('/production/schedule/get/'+plant+'/'+month+'/'+year).then(function(result){
            var row = '';
            result.partList.forEach(function(item) {
                row += '<tr id="'+ item.part_customer_id +'">';
                row += '<td>'+ item.part_customer.part_name +'</td>';
                row += rowSchedule;
                row +='</tr>';
            });
            $(".calendarTable tbody").html(row);

            result.scheduleList.forEach(function(item) {
                var tr = $("#"+item.part_customer_id);
                var content = '<div class="event qty" data-toggle="modal" data-target="#large-Modal" onClick=returnValue('+ JSON.stringify(item) +')>Qty : '+ item.qty +'</div>';
                if(item.shift1 == 1){
                    content += '<div class="event shift1" data-toggle="modal" data-target="#large-Modal" onClick=returnValue('+ JSON.stringify(item) +')>Shift 1</div>'
                }

                if(item.shift2 == 1){
                    content += '<div class="event shift2" data-toggle="modal" data-target="#large-Modal" onClick=returnValue('+ JSON.stringify(item) +')>Shift 2</div>'
                }

                tr.find("."+item.schedule_date).html(content);
            });

            loadDayOff();
            
        });
    }

    function returnValue(data){
        
        $("#idSchedule").val(data.schedule_id);
        $("#idSO").val(data.sales_order_id);
        $("#idPart").val(data.part_customer_id);
        $("#schedule_date").val(data.schedule_date);
        $("#shift1").val(data.shift1);
        $("#shift2").val(data.shift2);
        $("#qty").val(data.qty);
    }

    $('#simpan').on('click',function(){
        var form = "#scheduleForm";
        $("#scheduleForm").attr("action", "/production/schedule/update");
        saveData(form, function() {
            $('#large-Modal').modal('toggle');
            $(".getData").trigger('change');
        });
     
    });

    function loadDayOff(){
        var arrdayOff = {!! json_encode($dayOffList) !!};
        arrdayOff.forEach(function(item) {
            var day = '<div class="event off">'+ item.name.split(" ").join("<br>") +'</div>';
            $("tr").find("."+item.day_off).html(day);
        });
    }

    
</script>
@endsection

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
                                        <label class="col-sm-2 col-form-label">Sales Order *</label>
                                        <div class="col-sm-4">
                                            <select name="so_id" id="so_id" class="select-style col-sm-12" onchange="getPartList()" required>
                                                <option value="">--Select--</option>
                                                @foreach($soList as $ls)
                                                    <option value="{{ $ls->sales_order_id }}">{{ $ls->so_number."+".$ls->customer->customer_name."+Month : ".getMonth()[$ls->month_periode-1]['month_name']."  Year : ".$ls->year_periode }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <label class="col-sm-2 col-form-label">Part Customer *</label>
                                        <div class="col-sm-4">
                                            <select name="part_customer" id="part_customer" class="select-style col-sm-12" onchange="getProductionSchedule()" required>
                                                <option value="">--Select--</option>
                                                
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div id='calendar'></div>
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

    
    $(document).ready(function() {
        loadDayOff();
    
	    $('#external-events .fc-event').each(function() {

	        // store data so the calendar knows to render an event upon drop
	        $(this).data('event', {
	            title: $.trim($(this).text()), // use the element's text as the event title
	            stick: true // maintain when user navigates (see docs on the renderEvent method)
	        });

	        // make the event draggable using jQuery UI
	        $(this).draggable({
	            zIndex: 999,
	            revert: true, // will cause the event to go back to its
	            revertDuration: 0 //  original position after the drag
	        });

	    });

	    setTimeout(function(){
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month'
                },
                defaultDate: '2021-09-01',
                navLinks: true, // can click day/week names to navigate views
                businessHours: true, // display business hours
                editable: true,
                droppable: false, // this allows things to be dropped onto the calendar
                selectable: true,
                selectHelper:true,
                select: function(start, end, allDay) {
                    
                },
                eventClick: function(event) {
                    if(event.id != 'off'){
                        var schedule = scheduleList.filter(e => e.schedule_id === event.id);
                        $("#idSchedule").val(schedule[0].schedule_id);
                        $("#idSO").val(schedule[0].sales_order_id);
                        $("#idPart").val(schedule[0].part_customer_id);
                        $("#schedule_date").val(schedule[0].schedule_date);
                        $("#shift1").val(schedule[0].shift1);
                        $("#shift2").val(schedule[0].shift2);
                        $("#qty").val(schedule[0].qty);
                        $("#large-Modal").modal();
                        $('#simpan').on('click',function(){
                            var form = "#scheduleForm";
                            $("#scheduleForm").attr("action", "/process/production-schedule/update");
                            saveData(form, function() {
                                getProductionSchedule();
                                $('#large-Modal').modal('toggle');
                                $('.js-example-placeholder').select2('destroy');
                                $('.js-example-placeholder').select2();
                            });
                            $("#simpan").unbind(); 
                        });
                    }
                },
                events: dayOffList,
                dayRender: function(date, cell){
                    
                }
            });
		},350);
	});

    function loadDayOff(){
        var arrdayOff = {!! json_encode($dayOffList) !!};
        arrdayOff.forEach(function(item) {
            day = {
                id:'off',
                title: item.name,
                start: item.day_off,
                borderColor: 'red',
                backgroundColor: 'red',
                textColor: '#fff'
            };
            dayOffList.push(day);
        });
    }
    async function getPartList(){
        var soID = $("#so_id").val();
        
        await getData('/process/sales-order/get-item/'+soID).then(function(result){
            setOptionPart(result);
        });
        
    }

    function setOptionPart(item){
        var option='<option value="">--Select--</option>';
        item.forEach(function(part) {
            option += '<option value="'+ part.part_customer_id +'">'+ part.part_customer.part_name +'</option>';
        });
        $("#part_customer").html(option);
        loadSelect2();
    }

    async function getProductionSchedule(){
        var soID = $("#so_id").val();
        var part = $("#part_customer").val();

        if(soID != "" && part != ""){
            await getData('/process/production-schedule/get/'+soID+'/'+part).then(function(result){
                scheduleList = result;
                var itemList = [];
                result.forEach(function(item) {

                    if(item.shift1 == 1){
                        data1 = {
                            id: item.schedule_id,
                            title: "shift 1",
                            start: item.schedule_date,
                            constraint: 'businessHours',
                            borderColor: '#FC6180',
                            backgroundColor: '#d6a889',
                            textColor: '#fff'
                        };
                        itemList.push(data1);
                    }
                    

                    if(item.shift2 == 1){
                        data2 = {
                            id: item.schedule_id,
                            title: "Shift 2",
                            start: item.schedule_date,
                            constraint: 'businessHours',
                            borderColor: '#FC6180',
                            backgroundColor: '#7499e3',
                            textColor: '#fff'
                        };
                        itemList.push(data2);
                    }
                    
                    data3 = {
                        id: item.schedule_id,
                        title: 'qty : '+item.qty,
                        start: item.schedule_date,
                        constraint: 'businessHours',
                        borderColor: '#FC6180',
                        backgroundColor: '#b34e0b',
                        textColor: '#fff'
                    };
                    itemList.push(data3);
                });
                
                $('#calendar').fullCalendar('removeEvents');
                $('#calendar').fullCalendar('addEventSource', itemList);
                $('#calendar').fullCalendar('addEventSource', dayOffList);         
                $('#calendar').fullCalendar('rerenderEvents' );
                
            });
            
        }
        
    }
    
</script>
@endsection

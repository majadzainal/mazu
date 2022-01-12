@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"> DAY OFF TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Master</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Day Off</a>
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
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Day Off</h5>
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
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5>Day Off</h5>
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
                                                        <label class="col-sm-2 col-form-label">Year <span class="text-danger"> *</span></label>
                                                        <div class="col-sm-6">
                                                            <select name="year_periode_filter" id="year_periode_filter" onchange="periodeChangeFilter()" class="js-example-placeholder col-sm-12" required>
                                                                <option value="">--Select--</option>
                                                                @foreach(getYearPeriode() as $ls)
                                                                    <option value="{{ $ls['year'] }}">{{ $ls['year'] }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if(isAccess('create', $MenuID))
                                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                                    @endif
                                                    <div class="dt-responsive table-responsive">
                                                        <table id="searchTable" class="table table-striped table-bordered nowrap">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%">No</th>
                                                                    <th>Date</th>
                                                                    <th>Name</th>
                                                                    <th width="11%">Action</th>
                                                                </tr>
                                                            </thead>

                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
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

<!--MODAL CUSTOMER STATUS-->
<div class="modal fade" id="large-Modal"  role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Add Day Off</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="dayOffForm">
                @csrf
                <input type="hidden" name="day_off_id" id="day_off_id">
                <input type="hidden" name="year" id="year">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Day Off <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="date" name="day_off" id="day_off" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Name <span class="text-danger"> *</span> </label>
                        <div class="col-sm-6">
                            <input type="text" name="name_day_off" id="name_day_off" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Description </label>
                        <div class="col-sm-8">
                            <input type="text" name="description" id="description" class="form-control">
                        </div>
                    </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#dayOffForm', '#closeModal')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!---END MODAL CUSTOMER STATUS--->
@include('layouts.footerIn')
<script>
    $(document).ready(function() {

        var d = new Date();
        var year = d.getFullYear();
        $('#year_periode_filter').val(year);
        $('#year_periode_filter').trigger('change');
        loadCalendar(year);
    });

    async function loadCalendar(year){
        var data = await getData('/master/day-off/load/'+year);
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
        var itemList = [];
        data.forEach(function(item) {
            data2 = {
                title: item.name_day_off,
                start: item.day_off,
                borderColor: '#FC6180',
                backgroundColor: '#FC6180',
                textColor: '#fff'
            };
            itemList.push(data2);
        });
        setTimeout(function(data){
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay,listMonth'
            },
            defaultDate: new Date(),
            navLinks: true, // can click day/week names to navigate views
            businessHours: false, // display business hours
            editable: false,
            droppable: false, // this allows things to be dropped onto the calendar
            drop: function() {

                // is the "remove after drop" checkbox checked?
                if ($('#checkbox2').is(':checked')) {
                    // if so, remove the element from the "Draggable Events" list
                    $(this).remove();
                }
            },
            events: itemList,
            });
        },350);
    }

    function loadData(year){
        $('#searchTable').DataTable().destroy();
        $('#searchTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/day-off/load/'+year,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "day_off" },
                { "data": "name_day_off" },
                {
                    "mData": "day_off_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete day off "+ row.name_day_off +" ??' data-url='/master/day-off/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }
            ]
        });
    }

    function return_value(e, data){
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#dayOffForm").attr("action", "/master/day-off/update");
            $("#defaultModalLabel").text("Edit Day Off")
            $("#day_off_id").val(data.day_off_id);
            $("#day_off").val(data.day_off);
            $("#year").val(data.year);
            $("#name_day_off").val(data.name_day_off);
            $("#description").val(data.description);

        } else {
            $("#dayOffForm").trigger("reset");
            $("#dayOffForm").attr("action", "/master/day-off/add");
            $("#defaultModalLabel").text("Add Day Off")
        }

    }

    function saveInit(form, modalId){
        var day_off = $("#day_off").val();
        year = day_off != "" ? new Date(day_off) : new Date();
        $("#year").val(year.getFullYear());

        saveDataModal(form, modalId, function() {
            periodeChangeFilter();
        });
    }

    function periodeChangeFilter(){
        var year_periode = $('#year_periode_filter').val();
        if(year_periode !== ""){
            loadData(year_periode);
        }
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            periodeChangeFilter();
        });
    }

</script>
@endsection

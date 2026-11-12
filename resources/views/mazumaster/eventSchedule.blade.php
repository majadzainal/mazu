@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">EVENT SCHEDULE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Event</a></li>
                        <li class="breadcrumb-item"><a href="#!">Schedule</a></li>
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
                                    <h5>Event Schedule</h5>
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
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                    @endif
                                    <div class="dt-responsive table-responsive">
                                        <table id="searchTable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Status</th>
                                                    <th>Event Name</th>
                                                    <th>Description</th>
                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th width="11%">Action</th>
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

<!--MODAL CUSTOMER STATUS-->
<div class="modal fade" id="large-Modal"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabelStatus" >Add Event Schedule</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="scheduleForm">
                @csrf
                <input type="hidden" name="event_schedule_id" id="event_schedule_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Event Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="event_name" id="event_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Event Description <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="description" id="description" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">Start Event <span class="text-danger"> *</span></label>
                        <div class="col-sm-4">
                            <input name="start_date" id="start_date" class="form-control" type="date" required/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">End Event <span class="text-danger"> *</span></label>
                        <div class="col-sm-4">
                            <input name="end_date" id="end_date" class="form-control" type="date" required/>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#scheduleForm', '#closeModal')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!---END MODAL CUSTOMER STATUS--->
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
    } );

    function loadData(){
        $('#searchTable').DataTable().destroy();
        $('#searchTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/event-schedule/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "event_schedule_id",
                    "mRender": function (data, type, row, num) {
                        var button_open = "<button class='btn-sm btn-danger' data-confirm='Are you sure|want to close event schedule "+ row.event_name +"??' data-url='/master/event-schedule/close/" + data + "' onClick='closeInit(this)'>open</button>";
                        var button_close = "<button class='btn-sm btn-default'>close</button>";
                        var button = row.is_closed ? button_close : button_open;

                        return button;
                    }
                },
                { "data": "event_name" },
                { "data": "description" },
                { "data": "start_date" },
                { "data": "end_date" },
                {
                    "mData": "event_schedule_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete owner "+ row.owner_name +" ??' data-url='/master/event-schedule/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
            ]
        });
    }

    function return_value(e, data){

        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#scheduleForm").attr("action", "/master/event-schedule/update");
            $("#defaultModalLabelStatus").text("Edit Event Schedule")
            $("#event_schedule_id").val(data.event_schedule_id);
            $("#event_name").val(data.event_name);
            $("#description").val(data.description);

            $("#start_date").val(data.start_date.replace(" ", "T"));
            $("#end_date").val(data.end_date.replace(" ", "T"));
        } else {
            $("#scheduleForm").trigger("reset");
            $("#scheduleForm").attr("action", "/master/event-schedule/add");
            $("#defaultModalLabelStatus").text("Add Event Schedule")
        }

        $("#start_date").trigger("change");
        $("#end_date").trigger("change");

    }

    function saveInit(form, modalId){
        saveDataModal(form, modalId, function() {
            loadData();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }

    function closeInit(e) {
        deleteConfirm(e, function() {
            loadData();
        });
      }

</script>
@endsection

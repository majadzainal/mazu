@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">CASH OUT TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Cash Out</a></li>
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
                                    <h5>Cash Out</h5>
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
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" btn="add" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add</button>
                                    @endif
                                    <div class="form-group row">
                                        @if ($isEvent)
                                        <label class="col-sm-2 col-form-label">Event <span class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <select name="event_schedule_id" id="event_schedule_id" onchange="changeEvent()" class="js-example-placeholder col-sm-12" required>
                                                <option value="">--Select--</option>
                                                @foreach($eventList as $ls)
                                                    <option value="{{ $ls->event_schedule_id }}">{{ $ls->event_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @endif
                                        <label class="col-sm-12 col-form-label">Select periode for load data.</label>
                                        <label class="col-sm-2 col-form-label">Period <span class="text-danger"> *</span></label>
                                        <div class="col-sm-4">
                                            <input type="date" name="start_date" id="start_date" class="form-control">
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="date" name="end_date" id="end_date" class="form-control">
                                        </div>
                                        <button class="col-sm-2 btn btn-primary btn-sm btn-round waves-effect waves-light" onClick="btnLoadDataClick()">Load Data</button>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th width="11%">Action</th>
                                                    <th>Code</th>
                                                    <th>Name</th>
                                                    <th>Date</th>
                                                    <th>Cash Out (Rp.)</th>
                                                    <th>Description</th>
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
                <h4 class="modal-title" id="defaultModalLabel" >Cash Out</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="customerForm">
                @csrf
                <input type="hidden" name="cash_out_id" id="cash_out_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Code <span class="text-danger"> </span></label>
                        <div class="col-sm-6">
                            <input type="text" name="cash_out_code" id="cash_out_code" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="cash_out_name" id="cash_out_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" name="description" id="description" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Date</label>
                        <div class="col-sm-4">
                            <input type="date" name="cash_out_date" id="cash_out_date" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Cash Out (Rp.)</label>
                        <div class="col-sm-4">
                            <input type="text" value="0" name="dec_cash_out-form" id="dec_cash_out-form" class="form-control currency text-right" placeholder="" required>
                            <input type="hidden" value="0" readonly name="dec_cash_out" id="dec_cash_out" class="form-control">
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#customerForm', '#closeModal')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        var date = new Date();
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

        $("#start_date").val(moment(firstDay).format('YYYY-MM-DD'));
        $("#end_date").val(moment(lastDay).format('YYYY-MM-DD'));

        btnLoadDataClick();

    } );

    function changeEvent(){
        var eventSchedule = $("#event_schedule_id").val();
        var eventList = {!! json_encode($eventList) !!};
        var event = eventList.find(x => x.event_schedule_id === eventSchedule);
        $("#start_date").val(event.start_date);
        $("#end_date").val(event.end_date);

        btnLoadDataClick();
    }

    function btnLoadDataClick(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        if(start_date !== "" && end_date !== ""){
            loadData(start_date, end_date);
        }

    }

    function loadData(start_date, end_date){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/cash-out/load/'+start_date+'/'+end_date,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "cash_out_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete cash out "+ row.cash_out_name +" ??' data-url='/process/cash-out/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "cash_out_code" },
                { "data": "cash_out_name" },
                { "data": "cash_out_date" },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + row.dec_cash_out.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                { "data": "description" },

            ]
        });
    }

    function return_value(e, data){
        var btn = $(e).attr("btn");

        if (btn == "edit"){
            $("#customerForm").attr("action", "/process/cash-out/update");
            $("#defaultModalLabel").text("Edit Cash Out")
            $("#cash_out_id").val(data.cash_out_id);
            $("#cash_out_code").val(data.cash_out_code);
            $("#cash_out_name").val(data.cash_out_name);
            $("#cash_out_date").val(data.cash_out_date);
            $("#description").val(data.description);
            $("#dec_cash_out-form").val(data.dec_cash_out);
            $("#dec_cash_out").val(data.dec_cash_out);

            $("#dec_cash_out-form").trigger("focusout");
        } else {
            $("#customerForm").trigger("reset");
            $("#customerForm").attr("action", "/process/cash-out/add");
            $("#defaultModalLabel").text("Add Cash Out");
        }

    }

    $("#dec_cash_out-form").bind('keyup', function (e) {
        var cashOut = $("#dec_cash_out-form").val();
        var cashOutFin = parseFloat(cashOut.split(",").join(""));
        $("#dec_cash_out").val(cashOutFin);
    });

    function saveInit(form, modalId){
        var cashOut = $("#dec_cash_out-form").val();
        var cashOutFin = parseFloat(cashOut.split(",").join(""));
        $("#dec_cash_out").val(cashOutFin);
        saveDataModal(form, modalId, function() {
            btnLoadDataClick();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            btnLoadDataClick();
        });
    }

</script>
@endsection

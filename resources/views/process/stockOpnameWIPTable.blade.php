@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">STOCK OPNAME WIP</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Stock Opname</a></li>
                        <li class="breadcrumb-item"><a href="#!">WIP</a></li>
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
                            <div class="card bg-warning p-15">
                                @foreach($scheduleList as $ls)
                                    <h5 class="text-danger">{{ $ls->opname_date }} | {{ $ls->plant->plant_name}} | {{ $ls->start_datetime }}</h5>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12" id="table">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Stock Opname WIP</h5>
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
                                        <table id="searchTableOpname" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Stock Opname Number</th>
                                                    <th>Date</th>
                                                    <th>Plant</th>
                                                    <th>Start Datetime</th>
                                                    <th>End Datetime</th>
                                                    <th>Description</th>
                                                    <th>By User</th>
                                                    <th width="11%">Action</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!---END TABLE SECTION-->
                        <div class="col-sm-12" id="input">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Stock Opname WIP</h5>
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
                                    <form action="/process/stock-opname-wip/add" method="post" enctype="multipart/form-data" id="stockopnamefgform">
                                        @csrf
                                        <input type="hidden" name="stock_opname_id" id="stock_opname_id">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Stock Opname Number </label>
                                            <div class="col-sm-5">
                                                <input type="input" readonly name="stock_opname_number" id="stock_opname_number" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row opname_schedule_id_form">
                                            <label class="col-sm-2 col-form-label">Schedule </label>
                                            <div class="col-sm-8">
                                                <select name="opname_schedule_id" id="opname_schedule_id" onchange="scheduleChange()" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($scheduleList as $ls)
                                                        <option value="{{ $ls->opname_schedule_id }}">{{ $ls->plant->plant_name.", start opname : ".$ls->start_datetime}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row opname_schedule_id_form_edit">
                                            <label class="col-sm-2 col-form-label">Schedule </label>
                                            <div class="col-sm-8">
                                                <select name="opname_schedule_id_edit" id="opname_schedule_id_edit" onchange="scheduleChangeEdit()" class="js-example-placeholder js-example-disabled col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($scheduleList as $ls)
                                                        <option value="{{ $ls->opname_schedule_id }}">{{ $ls->plant->plant_name.", start opname : ".$ls->start_datetime}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Plant </label>
                                            <div class="col-sm-5">
                                                <input type="hidden" readonly name="plant_id" id="plant_id" class="form-control"/>
                                                <select name="plant_id_select" id="plant_id_select" class="js-example-placeholder col-sm-12 js-example-disabled" readonly="true" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($plantList as $ls)
                                                        <option value="{{ $ls->plant_id }}">{{ $ls->plant_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Stock Opname Date</label>
                                            <div class="col-sm-5">
                                                <input type="date" name="stock_opname_date" id="stock_opname_date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Description</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="description" id="description" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row import-excel-form">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <label class="col-sm-6 col-form-label">Please load part for download excel template.</label>
                                        </div>
                                        <div class="form-group row import-excel-form">
                                            <label class="col-sm-2 col-form-label">Import From Excel</label>
                                            <div class="col-sm-6 input-group input-group-button">
                                                <input type="file" class="form-control" name="import_part" id="import_part" accept=".xls, .xlsx" placeholder="Choose File .xlsx">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" onClick="importExcelClick('#import_part')" type="button">Upload Excel</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" style="text-align:left;">
                                            <hr>
                                            <label class="col-sm-12 col-form-label"><h4>WIP</h4></label>
                                        </div>
                                        <div class="dt-responsive table-responsive">
                                            <table id="searchTable1" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Part ID</th>
                                                        <th>Warehouse ID</th>
                                                        <th>Unit ID</th>
                                                        <th>WIP</th>
                                                        <th>Warehouse</th>
                                                        <th width="40%">&emsp;&emsp;&emsp;&emsp;Stock&emsp;&emsp;&emsp;&emsp;</th>
                                                        <th>Adjustment Stock</th>
                                                        <th>Stock After Adjustment</th>
                                                        <th>Unit</th>
                                                        <th width="30%">&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Note&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-8"></label>
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                {{-- <button type="button" class="save btn btn-info waves-effect waves-light" onClick="saveInit('#stockopnamefgform', 1)">Save Draft</button> --}}
                                                <button type="button" id="save_stock_opname_form" class="save btn btn-primary waves-effect waves-light" onClick="saveInit('#stockopnamefgform', 2)">Process</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!---END INPUT SECTION--->

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
        loadData();

        $("#input").hide();
    } );

    function loadData(){
        $('#searchTableOpname').DataTable().destroy();
        $('#searchTableOpname').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "autoWidth": false,
            "ajax": '/process/stock-opname-wip/load-opname',
            "aoColumns": [
                { "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "stock_opname_number" },
                { "data": "stock_opname_date" },
                { "data": "plant.plant_name" },
                {  "mRender": function (data, type, row, num) {
                        var startDate = row.schedule.start_datetime ? row.schedule.start_datetime : '-';
                        return startDate;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var endDate = row.schedule.end_datetime ? row.schedule.end_datetime : '-';
                        return endDate;
                    }
                },
                { "data": "description" },
                { "data": "created_user" },
                {
                    "mData": "stock_opname_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete/cancel Stock Opname Raw Material ("+ row.stock_opname_number +")??' data-url='/process/stock-opname-wip/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }
            ]
        });
    }

    function loadDataPart(plant_id){
        var export_name = "";
        var scheduleList = {!! json_encode($scheduleList) !!};
        var plantList = {!! json_encode($plantList) !!};
        var opname_schedule_id = $('#opname_schedule_id').val();
        var plant_id = $('#plant_id').val();


        var schedule = scheduleList.find(a => a.opname_schedule_id === opname_schedule_id);


        export_name = "Stok Opname WIP "+schedule.opname_date+"-"+schedule.plant.plant_name;

        $('#searchTable1').DataTable().destroy();
        $('#searchTable1').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "autoWidth": false,
            "columnDefs": [
                { "targets": 0, "visible": false },
                { "targets": 1, "visible": false },
                { "targets": 2, "visible": false },
            ],
            "dom": 'Bfrtip',
            "buttons": [
                {
                    extend:'excel',exportOptions: {
                        format: {
                            body: function ( data, row, column, node ) {
                                return $(data).is("input") ? $(data).val() : data;
                            }
                        },
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },
                    className: 'btn btn-block',
                    text: 'Export To Excel',
                    title: 'ERP-Rekadaya-' + export_name,
                },
            ],
            "ajax": '/process/stock-opname-wip/load/'+plant_id,
            "aoColumns": [
                { "data": "part_customer_id" },
                { "data": "warehouse_id" },
                { "data": "unit_id" },
                {  "mRender": function (data, type, row, num) {
                        var part = row.part_number+' - '+ row.part_name;
                        return part;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.warehouse_name;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var stock = '<input type="number" value="'+row.stock+'" readonly name="stock[]" class="form-control stock">';
                        return stock;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var stock_adjustment = '<input type="number" value="0" onInput="countAdjustment(this)" name="stock_adjustment[]" class="form-control stock_adjustment">';
                        return stock_adjustment;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var stock_after_adjustment = '<input type="number" value="'+row.stock+'" readonly name="stock_after_adjustment[]" class="form-control stock_after_adjustment">';
                        return stock_after_adjustment;
                    }
                },
                { "data": "unit_name" },
                {  "mRender": function (data, type, row, num) {
                        var note = '<input type="input" name="note[]" class="form-control note"><input type="hidden" name="warehouse_id[]"  value="'+row.warehouse_id+'" class="form-control warehouse_id"> <input type="hidden" name="part_customer_id[]"  value="'+row.part_customer_id+'" class="form-control part_customer_id"><input type="hidden" name="unit_id[]"  value="'+row.unit_id+'" class="form-control unit_id">';
                        return note;
                    }
                },
            ]
        });

    }

    function loadDataPartEdit(stokOpnameId){
        var export_name = "";
        var scheduleList = {!! json_encode($scheduleList) !!};
        var plantList = {!! json_encode($plantList) !!};
        var opname_schedule_id = $('#opname_schedule_id_edit').val();
        var plant_id = $('#plant_id').val();


        var schedule = scheduleList.find(a => a.opname_schedule_id === opname_schedule_id);


        export_name = "Stok Opname WIP "+schedule.opname_date+"-"+schedule.plant.plant_name;

        $('#searchTable1').DataTable().destroy();
        $('#searchTable1').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "autoWidth": false,
            "columnDefs": [
                { "targets": 0, "visible": false },
                { "targets": 1, "visible": false },
                { "targets": 2, "visible": false },
            ],
            "dom": 'Bfrtip',
            "buttons": [
                {
                    extend:'excel',exportOptions: {
                        format: {
                            body: function ( data, row, column, node ) {
                                return $(data).is("input") ? $(data).val() : data;
                            }
                        },
                        columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                    },
                    className: 'btn btn-block',
                    text: 'Export To Excel',
                    title: 'ERP-Rekadaya-' + export_name,
                },
            ],
            "ajax": '/process/stock-opname-wip/load-opname-item/'+stokOpnameId,
            "aoColumns": [
                { "data": "part_customer.part_customer_id" },
                { "data": "warehouse.warehouse_id" },
                { "data": "unit_id" },
                {  "mRender": function (data, type, row, num) {
                        var part = row.part_customer.part_number+' - '+ row.part_customer.part_name;
                        return part;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.warehouse.warehouse_name;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var stock = '<input type="number" value="'+row.stock+'" readonly name="stock[]" class="form-control stock">';
                        return stock;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var deviation = '<input type="number" value="'+row.deviation+'" name="deviation[]" readonly class="form-control deviation">';
                        return deviation;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var stock_adjustment = '<input type="number" value="'+row.stock_adjustment+'" readonly name="stock_adjustment[]" class="form-control stock_adjustment">';
                        return stock_adjustment;
                    }
                },
                { "data": "part_customer.unit.unit_name" },
                {  "mRender": function (data, type, row, num) {
                        var note = '<input type="input" value="'+row.note ? row.note : ""+'" name="note[]" readonly class="form-control note">';
                        return note;
                    }
                },
            ]
        });

    }

    function return_value(e, data){

        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#stockopnamefgform").attr("action", "/process/stock-opname-wip/update");
            $("#plant_id").val(data.plant_id);
            $(".save").hide();
            $(".import-excel-form").hide();
            $(".opname_schedule_id_form_edit").show();
            $(".opname_schedule_id_form").hide();
            $("#stock_opname_date").attr("disabled", "disabled");
            fillToForm(data);
        } else {
            $("#stockopnamefgform").trigger("reset");
            $(".save").show();
            $(".import-excel-form").show();
            $(".opname_schedule_id_form_edit").hide();
            $(".opname_schedule_id_form").show();
            $("#stock_opname_date").removeAttr("disabled");
            $("#stockopnamefgform").attr("action", "/process/stock-opname-wip/add");
        }

        $("#opname_type_id").trigger("change");
        $("#plant_id").trigger("change");
        $("#start_datetime").trigger("change");

        $("#table").hide();
        $("#input").show();
    }

    function fillToForm(data) {
        itemPO = data.po_items;
        $("#stock_opname_id").val(data.stock_opname_id);
        $("#stock_opname_number").val(data.stock_opname_number);
        $("#opname_schedule_id_edit").val(data.opname_schedule_id);
        $("#stock_opname_date").val(data.stock_opname_date);
        $("#description").val(data.description);


        $("#opname_schedule_id_edit").trigger("change");

    }

    function saveInit(form){
        saveData(form, function() {
            loadData();
            $("#input").hide();
            $("#table").show();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            // loadData();
        });
    }


    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    });

    function countAdjustment(e) {
        var tr = $(e).parent().parent();
        var stock = tr.find(".stock").val();
        var stock_adjustment = tr.find(".stock_adjustment").val();
        var stock_after_adjustment = parseInt(stock) + parseInt(stock_adjustment);
        tr.find(".stock_after_adjustment").val(stock_after_adjustment);
    }

    function scheduleChange(){
        var scheduleId = $("#opname_schedule_id").val();
        var scheduleList = {!! json_encode($scheduleList) !!};
        var schedule = scheduleList.find(a => a.opname_schedule_id === scheduleId);
        trigerForm(schedule);

        if(schedule.plant_id){
            loadDataPart(schedule.plant_id);
        }
    }

    function scheduleChangeEdit(){
        var scheduleId = $("#opname_schedule_id_edit").val();
        var stockOpnameId = $("#stock_opname_id").val();
        var scheduleList = {!! json_encode($scheduleList) !!};
        var schedule = scheduleList.find(a => a.opname_schedule_id === scheduleId);
        trigerForm(schedule);

        if(stockOpnameId){
            loadDataPartEdit(stockOpnameId);
        }
    }

    function trigerForm(schedule){
        var startTime = moment(schedule.start_datetime, 'YYYY-MM-DD HH:mm:ss').toDate();
        var now = new Date();

        if (now < startTime)
        {
            swal("Info!", "Time is not starting stock opname!");
        }
        $("#plant_id").val(schedule.plant_id);
        $("#plant_id_select").val(schedule.plant_id);
        $("#plant_id_select").trigger("change");
    }

    function importExcelClick(inputFileId){
        let fileImported = $(inputFileId)[0].files[0];
        if(fileImported){
            let fileReader = new FileReader();
            fileReader.readAsBinaryString(fileImported);
            fileReader.onload = (event)=>{
                let data = event.target.result;
                let workbook = XLSX.read(data,{type:"binary", cellDates:true});
                workbook.SheetNames.forEach(sheet => {
                    rowObject = XLSX.utils.sheet_to_row_object_array(workbook.Sheets[sheet]);
                    mappingData(rowObject);
                })
            }
        }
    }

    function mappingData(dataList){
        var itemList = [];
        dataList.forEach(function(data) {
            item = {
                part_customer_id: data['Part ID'],
                warehouse_id: parseInt(data['Warehouse ID']),
                stock: parseInt(data['Stock']) ? parseInt(data['Stock']) : 0,
                stock_adjustment: parseInt(data['Adjustment Stock']) ? parseInt(data['Adjustment Stock']) : 0,
                note: data['Note'] ? data['Note'] : '',
            };

            itemList.push(item);
        })

        $('.stock_adjustment').each(function(i) {
            var tr = $(this).parent().parent();
            var part_id = tr.find(".part_customer_id").val();
            var warehouse_id = parseInt(tr.find(".warehouse_id").val());
            var items = itemList.filter(a => a.part_customer_id === part_id && a.warehouse_id === warehouse_id);
            if (items){
                tr.find(".stock_adjustment").val(items[0].stock_adjustment);
                tr.find(".note").val(items[0].note);
                countAdjustment(this);
            }

        });
    }

</script>
@endsection

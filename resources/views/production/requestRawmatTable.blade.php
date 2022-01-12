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
                        <h5 class="m-b-10">REQUEST RAW MATERIAL TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Production</a></li>
                        <li class="breadcrumb-item"><a href="#!">Request Raw Material</a></li>
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
                                    <h5>Request Raw Material</h5>
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
                                                    <th width="25%">Request Number</th>
                                                    <th width="15%">Plant</th>
                                                    <th width="10%">Request Date</th>
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
                                    <h5>Purchase Order</h5>
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
                                    <form action="/production/request-raw-material/add" method="post" enctype="multipart/form-data" id="requestForm">
                                        @csrf
                                        <input type="hidden" name="request_id" id="request_id">
                                        <input type="hidden" name="plant" id="plant">
                                        <input type="hidden" name="rdate" id="rdate">
                                        <input type="hidden" name="is_status" id="is_status">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Request Number *</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="request_number" id="request_number" class="form-control" placeholder="auto generate after save" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Plant *</label>
                                            <div class="col-sm-10">
                                                <select name="plant_id" id="plant_id" class="select-style col-sm-12" onchange="getRawMaterial()" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($plantList as $ls)
                                                        <option value="{{ $ls->plant_id }}">{{ $ls->plant_name."+".$ls->description }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Request Date *</label>
                                            <div class="col-sm-5">
                                                <input type="date" name="request_date" id="request_date" class="form-control" onchange="getRawMaterial()" required>
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
                                                        <th width="50%">Part Customer</th>
                                                        <th width="20%">Qty</th>
                                                        <th width="30%">Date</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                        <div class="form-group row">
                                            <hr>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-12 col-form-label"><b>Raw Material</b></label>
                                        </div>
                                        <div class="dt-responsive table-responsive">
                                            <table id="rawMatTable" class="table table-striped table-bordered nowrap">
                                                <thead id="headPart">
                                                    <tr>
                                                        <th width="30%">Material</th>
                                                        <th width="10%">Qty</th>
                                                        <th width="10%">Unit</th>
                                                        <th width="20%">Warehouse</th>
                                                        <th width="10%">Stock Warehouse</th>
                                                        <th width="20%">Note</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-8"></label>
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <button type="button" class="save btn btn-info waves-effect waves-light" onClick="saveInit('#requestForm', 1)">Save Draft</button>
                                                <button type="button" class="save btn btn-primary waves-effect waves-light" onClick="saveInit('#requestForm', 2)">Submit</button>

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
                <h4 class="modal-title" id="defaultModalLabel" >Sending Request Raw Material</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/production/request-raw-material/send" method="post" enctype="multipart/form-data" id="sendForm">
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
            "ajax": '/production/request-raw-material/load/'+month_periode+'/'+year_periode,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "request_number" },
                {  "mRender": function (data, type, row, num) {

                        return row.plant.plant_name;
                    }
                },
                { "data": "request_date" },
                { "data": "description" },
                {  "mRender": function (data, type, row, num) {
                        var arrStatus = {!! json_encode(getStatusRequestRawMat()) !!};
                        return arrStatus[row.is_status];
                    }
                },
                {
                    "mData": "request_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.request_number +" request number??' data-url='/production/request-raw-material/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        if(row.is_status == 4 || row.is_status == 5 ){
                            @if(isAccess('update', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-inverse btn-icon' onClick=printReqRawMat('"+ data +"')>&nbsp;<i class='icofont icofont-download'></i></button>";
                                button += "<button class='btn waves-effect waves-light btn-success btn-icon' onClick='sendReqRawMat("+ JSON.stringify(row) +")' data-toggle='modal' data-target='#mail-Modal'>&nbsp;<i class='icofont icofont-email'></i></button>";
                            @endif
                        }
                        return button;
                    }
                }
            ]
        });
    }

    async function getRawMaterial(){
        var plant = $("#plant_id").val();
        var requestDate = $("#request_date").val();
        var part = [];
        if (plant != "" && requestDate != ""){
            await getData('/production/request-raw-material/getRawMaterial/'+ plant +'/'+ requestDate).then(function(result){
                
                rawMaterial(result.rawMatList, result.partList, '');
                partProduction(result.partProductionList)
                
            });
            
            //$('#partTable_wrapper .row').first().find('div:first').remove();
            //$('#partTable_filter').css("text-align", "left");

        }

    }
    
    function rawMaterial(rawMatList, partList, dataEdit){
        var materialList = dataEdit == ''? rawMatList : dataEdit;
        $('#rawMatTable').DataTable().destroy();
            var table = $('#rawMatTable').DataTable({
                "data": materialList,
                "filter": false,
                "bPaginate": false,
                "bLengthChange": false,
                "aoColumns": [
                    {  "mRender": function (data, type, row, num) {
                            part = partList.filter(a => a.part_id === row.part_id );
                            return part[0].part_name+' <p> Part Number : '+part[0].part_number+'</p><input type="hidden" name="part_id[]" value="'+part[0].part_id+'">';
                        }
                    },
                    { "data": "qty" },
                    {  "mRender": function (data, type, row, num) {
                            part = partList.filter(a => a.part_id === row.part_id );
                            return part[0].unit.unit_name+'<input type="hidden" name="unit[]" value="'+part[0].unit_id+'"><input type="hidden" name="qty[]" value="'+row.qty+'"><input type="hidden" name="price[]" value="'+part[0].part_price_active.price+'">';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            part = partList.filter(a => a.part_id === row.part_id );
                            
                            var wrh =  "<select name='warehouse[]' class='js-example-placeholder col-md-12' onChange='getStock(this, "+ JSON.stringify(part[0]) +")' required>";
                            wrh += "<option value=''>--Select--</option>";
                            part[0].stock_warehouse.forEach(function(item) {
                                var select = row.warehouse_id === item.warehouse.warehouse_id ? 'selected' : ''; 
                                wrh +='<option value="'+ item.warehouse.warehouse_id +'" '+ select +'>'+ item.warehouse.warehouse_name +'</option>';
                                });
                            wrh +=  '</select>';
                            return wrh;
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            var stock = "";
                            if (row.warehouse_id != undefined){
                                part = partList.filter(a => a.part_id === row.part_id );
                                var stockArr = part[0].stock_warehouse.filter(a => a.warehouse_id === row.warehouse_id );
                                stock = stockArr[0].stock;
                            }
                            return '<input type="number" name="stock[]" value="'+ stock +'" class="stock form-control tright" readonly>';
                        }
                    },
                    {  "mRender": function (data, type, row, num) {
                            var note = row.note == undefined ? '' : row.note;
                            return '<textarea name="note_material[]" class="form-control">'+ note +'</textarea>';
                        }
                    },
                ],
                "initComplete": function(settings, json) {
                    loadSelect2();
                }
            });
    }

    function partProduction(partProductionList){
        $('#partProduction').DataTable().destroy();
        var table = $('#partProduction').DataTable({
            "data": partProductionList,
            "filter": false,
            "bPaginate": false,
            "bLengthChange": false,
            "aoColumns": [
                { "data": "part_customer.part_name" },
                { "data": "qty" },
                { "data": "schedule_date" }
            ]
        });
    }

    function getStock(e, part){
        var stocks = part.stock_warehouse.filter(a => a.warehouse_id == $(e).val() );
        var tr = $(e).parent().parent();
        tr.find(".stock").val(stocks[0].stock);
    }

    async function return_value(e, data){
        var btn = $(e).attr("btn");
        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;
        if (btn == "edit"){
            $("#requestForm").attr("action", "/production/request-raw-material/update");
            $("#request_id").val(data.request_id);
            $("#request_number").val(data.request_number);
            $("#plant_id").val(data.plant_id);
            $("#plant").val(data.plant_id);
            $("#request_date").val(data.request_date);
            $("#rdate").val(data.request_date);
            $("#note").val(data.description);
            if(data.is_status != 1)
                $(".save").hide();
            else 
                $(".save").show();
            
            loadSelect2();
            await getData('/production/request-raw-material/getRawMaterial/'+ data.plant_id +'/'+ data.request_date).then(function(result){
                partProduction(result.partProductionList);
                rawMaterial(result.rawMatList, result.partList, data.request_item);
            });
        } else {
            itemPO = [];
            $("#requestForm").trigger("reset");
            $("#requestForm").attr("action", "/production/request-raw-material/add");
            //$('.js-example-placeholder').select2('destroy');
            //$('.js-example-placeholder').select2();
            $('#partTable tbody').remove();
        }

        
    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form, status){
        if ($('input[name="part_id[]"]').length > 0){
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

    function printReqRawMat(reqId){
        window.open('/production/request-raw-material/print/'+reqId, '_blank');
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

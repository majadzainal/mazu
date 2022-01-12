@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">PROCESS PRICE TABLE</h5>
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
                        <li class="breadcrumb-item"><a href="#!">Process Price</a>
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
                                    <h5 id="defaultModalLabelprocessPrice">Process Price Table</h5>
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
                                <div class="tab-content card-block">
                                    <div id="process-price-table">
                                        @if(isAccess('create', $MenuID))
                                            <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" onClick="return_value_process_price(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                        @endif
                                        <div class="dt-responsive table-responsive">
                                            <table id="newTablesprocessPrice" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th width="3%">No.</th>
                                                        <th>rocess Name</th>
                                                        <th>Machine Code</th>
                                                        <th>Machine Brand</th>
                                                        <th>Line</th>
                                                        <th width="11%">Action</th>
                                                    </tr>
                                                </thead>

                                            </table>
                                        </div>
                                    </div>
                                    <div id="inputprocessPrice">
                                        <form action="/bill-material/add" method="post" enctype="multipart/form-data" id="processPriceForm">
                                            @csrf
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Process Name <span class="text-danger"> *</span></label>
                                                <div class="col-sm-10">
                                                    <select name="process_id_price" id="process_id_price" onchange="checkPriceList()" class="js-example-placeholder col-sm-12" required>
                                                        <option value="">--Select--</option>
                                                        @foreach($processList as $ls)
                                                            <option value="{{ $ls['process_id'] }}">{{ $ls['process_name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Machine <span class="text-danger"> *</span></label>
                                                <div class="col-sm-10">
                                                    <select name="pmachine_id_price" id="pmachine_id_price" onchange="checkPriceList()" class="js-example-placeholder col-sm-12" required>
                                                        <option value="">--Select--</option>
                                                        @foreach($machineList as $ls)
                                                            <option value="{{ $ls['pmachine_id'] }}">{{ $ls['code']." - ".$ls['brand']." - ".$ls['line'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">Cycle Time</label>
                                                <div class="col-sm-10">
                                                    <input type="number" name="cycle" id="cycle" value="1" readonly class="form-control col-sm-2" required>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-sm-12"><button type="button" onClick="addPrice('')" class="btn btn-info btn-round btn-sm waves-effect">Add Price</button>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="table-responsive">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Effective Date</th>
                                                                <th>Price</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="bodyPrice">

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-sm-9"></label>
                                                <div class="col-sm-3" style="text-align:right;">
                                                    <button type="button" class="btn btn-default waves-effect closeForm" id="closeForm">Close</button>
                                                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#processPriceForm', '.closeForm')">Save</button>
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
</div>
<div id="styleSelector"></div>
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
        $("#inputprocessPrice").hide();
    } );


    function loadData(){

        $('#newTablesprocessPrice').DataTable().destroy();
        $('#newTablesprocessPrice').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/process-price/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "process.process_name" },
                { "data": "process_machine.code" },
                { "data": "process_machine.brand" },
                { "data": "process_machine.line" },
                {
                    "mData": "process.process_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value_process_price(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete process price "+ row.process.process_name +"-"+ row.process_machine.code +" ??' data-url='/master/process-price/delete/" + row.process.process_id + "/"+ row.process_machine.pmachine_id +"' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }
            ]
        });
    }

    function return_value_process_price(e, data){
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            // getData('/master/process-price/get/'+data.process_id+'/'+data.pmachine_id).then(function(data, result){
            //     fillToTableInput(data, result);
            // });
            $("#processPriceForm").attr("action", "/master/process-price/update");
            $("#defaultModalLabelprocessPrice").text("Edit Process Price");
            $("#cycle").val(1);
            $("#process_id_price").val(data.process_id);
            $("#pmachine_id_price").val(data.pmachine_id);
            trigerSelect();

        } else {
            $("#processPriceForm").trigger("reset");
            trigerSelect();
            $("#cycle").val(1);
            $("#processPriceForm").attr("action", "/master/process-price/add");
            $("#defaultModalLabelprocessPrice").text("Add Process Price");

        }
        $("#defaultModalLabelprocessPrice").text("Process Price Table");
        $("#inputprocessPrice").show();
        $("#process-price-table").hide();
        document.documentElement.scrollTop = 0;
    }
    function checkPriceList(){
        var process_id_price =  $("#process_id_price").val();
        var pmachine_id_price = $("#pmachine_id_price").val();
        if(process_id_price !== '' && pmachine_id_price !== ''){
            getData('/master/process-price/get/'+process_id_price+'/'+pmachine_id_price).then(function(result){
                fillToTableInput(result);
            });
        }
    }

    function fillToTableInput(dataList){
        clearTable('bodyPrice');
        if(dataList.length >= 1){

            $("#processPriceForm").attr("action", "/master/process-price/update");
            $("#defaultModalLabelprocessPrice").text("Edit Process Price");
            dataList.forEach(function(price) {
                addPrice(price);
            });
        }else{
            $("#processPriceForm").attr("action", "/master/process-price/add");
            $("#defaultModalLabelprocessPrice").text("Add Process Price");
        }
    }

    function trigerSelect(){
        $("#process_id_price").trigger('change');
        $("#pmachine_id_price").trigger('change');
    }

    function saveInit(form, closeModalId){
        var values = $("input[name='effective_date[]']").map(function(){return $(this).val();}).get();
        if(values.length >= 1){
            if(!justCheckDuplicate()){
                saveDataModal(form, closeModalId, function() {
                    loadData();
                });
            }else{
                swal({
                    title: "Error Validate",
                    text: "Duplicate effective date price! \n please check price list.",
                    type: "warning",
                    showCancelButton: false,
                });
            }
        }else{
            swal({
                title: "Error Validate",
                text: "Please input price!",
                type: "warning",
                showCancelButton: false,
            });
        }
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }

    $('.closeForm').click(function(e) {
        clearTable('bodyPrice');
        $("#defaultModalLabelprocessPrice").text("Process Price Table");
        $("#inputprocessPrice").hide();
        $("#process-price-table").show();
        document.documentElement.scrollTop = 0;
    })

    function addPrice(data){
        var priceID = data.process_price_id ? data.process_price_id : "";
        var effective_date = data.effective_date ? data.effective_date : "";
        var price = data.price ? data.price : "";

        var addPrice = "<tr>";
            addPrice += "<td>";
            addPrice += "<input type='hidden' name='process_price_id[]' value='"+ priceID +"'>";
            addPrice += "<input onchange='validateDuplicate(this)' type='date' name='effective_date[]' class='form-control' value='"+ effective_date +"' required>";
            addPrice += "<span class='text-danger error-text col-sm-12'></span>";
            addPrice += "</td>";
            addPrice += "<td><input type='number' name='price[]' class='form-control' value='"+ price +"' required></td>";
            addPrice += "<td><button type='button' onClick='removePrice(this)' class='btn waves-effect waves-light btn-warning btn-icon'>&nbsp;<i class='icofont icofont-trash'></i></button></td>";
            addPrice += "</tr>";
        $('#bodyPrice').append(addPrice);

    }

    function removePrice(e){
        $(e).parent().parent().remove();
    }

    function clearTable(idTable){
        var node = document.getElementById(idTable);
            while (node.hasChildNodes()) {
            node.removeChild(node.lastChild);
        }
    }

    function validateDuplicate(e){
        var isDuplicate = justCheckDuplicate();
        if(isDuplicate){
            $(e).parent().find(".error-text").text("Error : Duplicate date!");
        }else{
            $(e).parent().find(".error-text").text("");
        }
    }

    function justCheckDuplicate(){
        var values = $("input[name='effective_date[]']").map(function(){return $(this).val();}).get();
        return values.some(function(item, idx){
                return values.indexOf(item) != idx
            });
    }

</script>
@endsection

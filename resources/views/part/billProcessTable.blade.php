@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">BILL OF PROCESS TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Part</a></li>
                        <li class="breadcrumb-item"><a href="#!">Bill Of Process</a></li>
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
                                    <h5>Bill of Process</h5>
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
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Import</label>
                                            <div class="col-sm-5 input-group input-group-button">
                                                <input type="file" class="form-control" name="import_bill_material" id="import_bill_material" accept=".xls, .xlsx" placeholder="Choose File .xlsx">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" onClick="readImportExcels('#import_bill_material')" data-toggle="modal" data-target="#large-ModalImport" type="button">Upload Excel</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-5">
                                                <a href="/bill-process/template-download" class="btn btn-success">Download Template</a>
                                            </div>
                                        </div>
                                        <hr>
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" btn="add" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add</button>
                                    @endif
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th width="25%">Customer</th>
                                                    <th width="25%">Part</th>
                                                    <th>Plant</th>
                                                    <th>Status</th>
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
                                    <h5>Bill of Process</h5>
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
                                    <form action="/bill-process/add" method="post" enctype="multipart/form-data" id="bopForm">
                                        @csrf
                                        <input type="hidden" name="bill_process_id" id="bill_process_id">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Customer <span class="text-danger"> *</span></label>
                                            <div class="col-sm-10">
                                                <select name="customer_id" id="customer_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($customerList as $ls)
                                                        <option value="{{ $ls->customer_id }}">{{ $ls->business_entity.". ".$ls->customer_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Part Name <span class="text-danger"> *</span></label>
                                            <div class="col-sm-10">
                                                <select name="part_customer_id" id="part_customer_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Status <span class="text-danger"> *</span></label>
                                            <div class="col-sm-10">
                                                <select name="status_id" id="status_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach(getStatus() as $ls)
                                                        <option value="{{ $ls['status_id'] }}">{{ $ls['status_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Plant <span class="text-danger"> *</span></label>
                                            <div class="col-sm-10">
                                                <select name="plant_id" id="plant_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($plantList as $ls)
                                                        <option value="{{ $ls->plant_id }}">{{ $ls->plant_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12"><button type="button" onClick="addProcess('')" class="btn btn-info btn-round btn-sm waves-effect">Add Process</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th width="10%">Process <span class="text-danger"> *</span></th>
                                                        <th width="30%">Process Name <span class="text-danger"> *</span></th>
                                                        <th width="30%">MC <span class="text-danger"> *</span></th>
                                                        <th width="10%">Cycle Time <span class="text-danger"> *</span></th>
                                                        <th width="20%">Price</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyProcess">
                                                    <tr>
                                                        <td>
                                                            <input type="number" name="process_order[]" class="process_order form-control" required></td>
                                                        </td>
                                                        <td>
                                                            <select name="process_id[]" class="process_id js-example-placeholder col-sm-12" onChange="countCost(this)" required>
                                                                <option value="">--Select--</option>
                                                                @foreach($processList as $ls)
                                                                    <option value="{{ $ls->process_id }}">{{ $ls->process_name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <select name="mc[]" class="mc js-example-placeholder col-sm-12" onChange="countCost(this)" required>
                                                                <option value="">--Select--</option>
                                                                @foreach($machineList as $ls)
                                                                    <option value="{{ $ls->pmachine_id }}">{{ $ls->code }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td>
                                                            <input type="number" name="price[]" class="price form-control" readonly>
                                                        </td>

                                                        <td><button type="button" onClick="removeProcess(this)" class="btn waves-effect waves-light btn-warning btn-icon">&nbsp;<i class='icofont icofont-trash'></i></button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-9"></label>
                                            <div class="col-sm-3" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#bopForm')">Save</button>
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
<!--MODAL IMPORT EXCEL--->
<div class="modal fade" id="large-ModalImport" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Import Part Customer</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-12">
                        <div class="dt-responsive table-responsive">
                            <table id="tableImport" class="table table-striped table-bordered nowrap">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Action</th>
                                        <th>Customer</th>
                                        <th>Part Name</th>
                                        <th>Status</th>
                                        <th>Plant</th>
                                        <th>Process</th>
                                        <th>Process Name</th>
                                        <th>MC</th>
                                        <th>Cycle Time</th>
                                        <th style="display:none">Customer ID</th>
                                        <th style="display:none">Part ID</th>
                                        <th style="display:none">Process ID</th>
                                        <th style="display:none">MC ID</th>
                                        <th style="display:none">plant ID</th>
                                        <th style="display:none">Status ID</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBodyImport">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="closeModalImport" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                <form action="/bill-process/import" method="post" enctype="multipart/form-data" id="importForm">
                    @csrf
                    <input type="hidden" name="importList" id="importList" class="form-control">
                    <button type="button" onClick="saveImport('#importForm')" class="btn btn-primary waves-effect waves-light" >Import</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--- CLOSE MODAL IMPORT EXCEL--->
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
        $("#input").hide();
    } );

    $('#customer_id').change(function(e) {
        var arrPartCustomer = {!! json_encode($partCustomerList) !!};
        var partCustomer = arrPartCustomer.filter(e => e.customer_id === $(this).val());
        var dataPart = [];

        partCustomer.forEach(function(part) {
            var part = {
                id: part.part_customer_id,
                text: part.part_name+" - "+part.part_number
            };
            dataPart.push(part);
        });

        $("#part_customer_id").html('<option value="">--select--</option>');
        $("#part_customer_id").select2('destroy');
        $("#part_customer_id").select2({
            data: dataPart,
            placeholder: "--select--"
        });
    });

    function loadData(){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "ajax": '/bill-process/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.customer.business_entity+". "+row.customer.customer_name;
                    }
                },
                { "data": "part_customer.part_name" },
                { "data": "plant.plant_name" },
                {  "mRender": function (data, type, row, num) {
                        var arrStatus = {!! json_encode(getStatus()) !!};
                        var status = arrStatus.filter(e => e.status_id === row.status_id);
                        return status[0].status_name;
                    }
                },
                {
                    "mData": "bill_process_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.part_customer.part_name +" part??' data-url='/bill-process/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
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
            $("#bopForm").attr("action", "/bill-process/update");
            $("#defaultModalLabel").text("Edit Bill Of Process")
            $("#bill_process_id").val(data.bill_process_id);
            $("#part_customer_id").change();
            $("#customer_id").val(data.customer_id);
            $("#customer_id").trigger('change');
            $("#plant_id").val(data.plant_id);
            $("#plant_id").trigger('change');
            $("#status_id").val(data.status_id);
            $("#status_id").trigger('change');
            $("#bodyProcess").html('');
            data.bop_item.forEach(function(item) {
                addProcess(item);
            });
            $('.process_id').each(function() {
                countCost(this);
            });
            $("#part_customer_id").val(data.part_customer_id);
            $("#part_customer_id").trigger('change');
        } else {
            $("#bopForm").attr("action", "/bill-process/add");
            $("#bopForm").trigger("reset");
            $('#bodyProcess').html('');
            loadSelect2();
            $("#defaultModalLabel").text("Add Bill Of Process");

        }
        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;
    }

    $('.closeForm').click(function(e) {
        $("#bopForm").trigger("reset");
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form){
        var values = $("select[name='process_id[]']").map(function(){return $(this).val();}).get();
        if(values.length >= 1){
            saveData(form, function() {
                loadData();
                loadSelect2();
                $('#bodyProcess').html('');
                addProcess('');
                $("#input").hide();
                $("#table").show();
            });
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

    function displayImportData(dataList){

        var arrCustomer = {!! json_encode($customerList) !!};
        var arrPartCustomer = {!! json_encode($partCustomerList) !!};
        var arrPlant = {!! json_encode($plantList) !!};
        var arrProcess = {!! json_encode($processList) !!};
        var arrStatus = {!! json_encode(getStatus()) !!};
        var arrMC = {!! json_encode($machineList) !!};


        var no = 1;
        dataList.forEach(function(data) {

            // var customer = arrCustomer.filter(e => e.customer_id === data.customer_id);
            // var partCustomer = arrPartCustomer.filter(e => e.part_customer_id === data.part_customer_id);
            // var process = arrProcess.filter(e => e.process_id === data.process_id);
            // var mc = arrMC.filter(e => e.pmachine_id === data.pmachine_id);

            var customer = arrCustomer.filter(e => e.business_entity.toLowerCase()+". "+e.customer_name.toLowerCase() === data.customer.toLowerCase());
            var partCustomer = arrPartCustomer.filter(e => e.part_name.toLowerCase() === data.part_name.toLowerCase() );
            var process = arrProcess.filter(e => e.process_name.toLowerCase() === data.process_name.toLowerCase());
            var mc = arrMC.filter(e => e.code.toLowerCase() === data.mc.toLowerCase());

            if(customer != "" && partCustomer != "" && process != "" && mc != ""){

                var plant = arrPlant.filter(e => e.plant_id === data.plant_id);
                var status = arrStatus.filter(e => e.status_name.toLowerCase() === data.status.toLowerCase());
                button = "<button type='button' class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete material "+ process[0].proccess_name +" ??' onClick='doDeleteImport(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                var tableBody = '';
                tableBody += '<tr>';
                tableBody += '<td>'+ no +'</td>';
                tableBody += '<td>'+ button +'</td>';
                tableBody += '<td>'+ customer[0].business_entity + '. ' + customer[0].customer_name +'</td>';
                tableBody += '<td>'+ partCustomer[0].part_name +'</td>';
                tableBody += '<td>'+ (status != "" ? status[0].status_name : "") +'</td>';
                tableBody += '<td>'+ (plant != "" ? plant[0].plant_name : "") +'</td>';
                tableBody += '<td>'+ (data.process ? data.process : "") +'</td>';
                tableBody += '<td>'+ process[0].process_name +'</td>';
                tableBody += '<td>'+ (data.mc ? data.mc : "") +'</td>';
                tableBody += '<td>'+ (data.cycle_time ? data.cycle_time : "") +'</td>';
                tableBody += '<td style="display:none">'+ customer[0].customer_id +'</td>';
                tableBody += '<td style="display:none">'+ partCustomer[0].part_customer_id +'</td>';
                tableBody += '<td style="display:none">'+ process[0].process_id +'</td>';
                tableBody += '<td style="display:none">'+ mc[0].pmachine_id +'</td>';
                tableBody += '<td style="display:none">'+ (plant != "" ? plant[0].plant_id : "") +'</td>';
                tableBody += '<td style="display:none">'+ (status != "" ? status[0].status_id : "") +'</td>';
                tableBody += '</tr>';
                $("#tableBodyImport").append(tableBody);

            }
            no++;
        });
    }

    function getDataImport(){
        var dataList = [];
        $('#tableBodyImport tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            data = {
                customer_id: this.cells[10].innerHTML,
                part_customer_id: this.cells[11].innerHTML,
                status_id: this.cells[15].innerHTML,
                plant_id: this.cells[14].innerHTML,
                process_id: this.cells[12].innerHTML,
                process_order: this.cells[6].innerHTML,
                cycle_time: this.cells[9].innerHTML,
                mc: this.cells[13].innerHTML,
                process_name: this.cells[7].innerHTML,
            };
            dataList.push(data);
        });

        return dataList;
    }

    function saveImport(form){
        var data = getDataImport();
        document.getElementById('importList').value = JSON.stringify(data);
        saveData(form, function() {
            loadData();
            $("#closeModalImport").click();
        });
    }


    function addProcess(data){
        var item_bop_id = data.item_bop_id ? data.item_bop_id : "";
        var process_order = data.process_order ? data.process_order : "";
        var process_id = data.process_id ? data.process_id : "";
        var mc = data.mc ? data.mc : "";
        var cycle_time = data.cycle_time ? data.cycle_time : "";

        var process = '<tr>';
        process +='<td><input type="hidden" name="item_bop_id[]" value="'+item_bop_id+'">';
        process +='<input type="number" name="process_order[]" value="'+ process_order +'" class="process_order form-control" required>';
        process +='</td>';
        process +='<td>';
        process +=  '<select name="process_id[]" class="process_id js-example-placeholder col-sm-12" onChange="countCost(this)" required>';
        process +=      '<option value="">--Select--</option>';
                            @foreach($processList as $ls)
                                var selected = (process_id == '{{ $ls->process_id }}')? "selected":"";
                                process += '<option value="{{ $ls->process_id }}" '+selected+'>{{ $ls->process_name }}</option>';
                            @endforeach
        process +=  '</select>';
        process +='</td>';
        process +='<td>';
        process +=  '<select name="mc[]" class="mc js-example-placeholder col-sm-12" onChange="countCost(this)" required>';
        process +=      '<option value="">--Select--</option>';
                            @foreach($machineList as $ls)
                                var selected = (mc == '{{ $ls->pmachine_id }}')? "selected":"";
                                process += '<option value="{{ $ls->pmachine_id }}" '+selected+'>{{ $ls->code }}</option>';
                            @endforeach
        process +=  '</select>';
        process +='</td>';
        process +='<td><input type="number" name="cycle_time[]" class="cycle_time form-control" value="'+ cycle_time +'"></td>';
        process +='<td><input type="number" name="price[]" class="price form-control" readonly></td>';
        process +='<td><button type="button" onClick="removeProcess(this)" class="btn waves-effect waves-light btn-warning btn-icon">&nbsp;<i class="icofont icofont-trash"></i></button></td>';
        process +='</tr>';

        $('#bodyProcess').append(process);
        loadSelect2();


    }

    function removeProcess(e){
        $(e).parent().parent().remove();
    }

    function countCost(e){
        var today = getDateNow();
        var process_id = $(e).parent().parent().find(".process_id").val();
        var mc = $(e).parent().parent().find(".mc").val();
        var cycle_time = $(e).parent().parent().find(".cycle_time").val();
        var arrPrice = {!! json_encode($priceList) !!};

        var price = 0;
        if (process_id != "" && mc != ""){
            var priceData = arrPrice.filter(e => e.process_id == process_id && e.pmachine_id == mc && e.effective_date <= today);
            priceData = sortDescDateEffective(priceData);

            if(priceData.length > 0)
                price = priceData[0].price;
        }

        //var cost = parseInt(cycle_time) * parseInt(price);

        $(e).parent().parent().find(".price").val(price);
        //$(e).parent().parent().find(".cost").val(cost);

    }
</script>
@endsection

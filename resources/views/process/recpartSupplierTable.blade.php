@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">RECEIVING PART SUPPLIER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Receiving Part Supplier</a></li>
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
                                    <h5>Receiving Part Supplier</h5>
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
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" btn="add" manually="0" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add By Scan</button>
                                        <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" btn="add" manually="1" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add By Manually</button>
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
                                                    <th>Rec. Part Number</th>
                                                    <th>No. PO</th>
                                                    <th>No. DO Supplier</th>
                                                    <th>Delivered By</th>
                                                    <th>Received By</th>
                                                    <th>Date Receive</th>
                                                    <th>Action</th>
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
                                    <h5>Receiving Part Supplier Form</h5>
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
                                    <form action="/process/receiving-part-supplier/add" method="post" enctype="multipart/form-data" id="recPartForm">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Rec. Part Number</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="is_manually" id="is_manually" hidden>
                                                <input type="text" name="recpart_number" id="recpart_number" class="form-control" placeholder="auto generate after save" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Period <span class="text-danger"> *</span></label>
                                            <div class="col-sm-3">
                                                <select name="month_periode" id="month_periode" onchange="periodeChange()" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach(getMonth() as $ls)
                                                        <option value="{{ $ls['month'] }}">{{ $ls['month_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label class="col-form-label">Year <span class="text-danger"> *</span></label>
                                            <div class="col-sm-3">
                                                <select name="year_periode" id="year_periode" onchange="periodeChange()" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach(getYearPeriode() as $ls)
                                                        <option value="{{ $ls['year'] }}">{{ $ls['year'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <input type="hidden" name="recpart_supplier_id" id="recpart_supplier_id">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">No PO<span class="text-danger"> *</span></label>
                                            <div class="col-sm-10" id="select_po_id" onchange="poidChange()">
                                                <select name="po_id" id="po_id" class="select-style col-sm-12" required>
                                                    <option value="">--Select--</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">No. DO Supplier <span class="text-danger"> *</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" name="do_number_supplier" id="do_number_supplier" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Delivered By <span class="text-danger"> *</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" name="delivered_by" id="delivered_by" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Received By <span class="text-danger"> *</span></label>
                                            <div class="col-sm-5">
                                                <input type="text" name="received_by" id="received_by" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Date Received <span class="text-danger"> *</span></label>
                                            <div class="col-sm-5">
                                                <input type="date" name="date_receive" id="date_receive" class="form-control" required>
                                            </div>
                                        </div>

                                        <div id="by-scan">
                                        <hr>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <label class="col-sm-6 col-form-label">Generate QR label supplier in menu <strong>Part ->  <a href="/generate-part-label/table" target="_Blank"> Generate Label </a> </strong></label>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Scan QR</label>
                                            <div class="col-sm-6 input-group input-group-button">
                                                <input type="text" class="form-control" name="packing_number" id="packing_number">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-primary" onClick="addScanClick()" type="button">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        </div><!--BY-SCAN-->

                                        <div class="table-responsive">
                                            <table id="receivingTable" class="table table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Material</th>
                                                        <th>Warehouse</th>
                                                        <th>Order</th>
                                                        <th>Received</th>
                                                        <th>Remain</th>
                                                        <th>Receive</th>
                                                        <th>Over</th>
                                                        <th>Detail</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyMaterial">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-8"></label>
                                            <div class="col-sm-4" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <button type="button" id="btn-save" class="save btn btn-primary waves-effect waves-light" onClick="saveInit('#recPartForm')">Save</button>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12" id="edit">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Receiving Part Supplier Form</h5>
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
                                        <label class="col-sm-2 col-form-label">Rec. Part Number <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="recpart_number_edit" id="recpart_number_edit" class="form-control" placeholder="auto generate after save" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Period <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="text" name="month_periode_edit" readonly id="month_periode_edit" class="form-control" required>
                                        </div>
                                        <label class="col-form-label">Year <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="text" name="year_periode_edit" readonly id="year_periode_edit" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">No PO<span class="text-danger"> *</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="po_id_edit" readonly id="po_id_edit" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">No. DO Supplier <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="do_number_supplier_edit" readonly id="do_number_supplier_edit" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Delivered By <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="delivered_by_edit" id="delivered_by_edit" readonly class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Received By <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="text" name="received_by_edit" id="received_by_edit" readonly class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Date Received <span class="text-danger"> *</span></label>
                                        <div class="col-sm-5">
                                            <input type="date" name="date_receive_edit" id="date_receive_edit" readonly class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th rowspan="2">Material</th>
                                                    <th rowspan="2">Warehouse</th>
                                                    <th colspan="5" class="text-center">Qty</th>
                                                    <th class="text-center">Detail</th>
                                                </tr>
                                                <tr>
                                                    <th>Order</th>
                                                    <th>Received</th>
                                                    <th>Remain</th>
                                                    <th>Receive</th>
                                                    <th>Over</th>
                                                </tr>
                                            </thead>
                                            <tbody id="bodyMaterialEdit">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-group row modal-footer">
                                        <label class="col-sm-8"></label>
                                        <div class="col-sm-4" style="text-align:right;">
                                            <button type="button" class="btn btn-default waves-effect closeFormEdit">Close</button>
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
<!--MODAL PRINT LABEL--->
<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Part supplier packing number.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="partLabelDetail" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Part Label</th>
                                <th>Standard Packing</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="partLabelDetailBody">
                        </tbody>
                    </table>
                </div>
            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--MODAL PRINT LABEL--->
@include('layouts.footerIn')
<script>
    var dataListPO = [];
    var poList = [];
    var is_manually = "0";

    $(document).ready(function() {
        var d = new Date();
        var month = d.getMonth()+1;
        var year = d.getFullYear();
        $('#month_periode_filter').val(month);
        $('#year_periode_filter').val(year);
        $('#month_periode_filter').trigger('change');
        $('#year_periode_filter').trigger('change');

        $("#input").hide();
        $("#edit").hide();
    } );

    async function periodeChangeFilter(){
        var month_periode = $('#month_periode_filter').val();
        var year_periode = $('#year_periode_filter').val();
        if(month_periode !== "" && year_periode !== ""){
            loadData(month_periode, year_periode);
        }
    }

    function loadData(){

        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/receiving-part-supplier/load/'+month_periode+'/'+year_periode,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "recpart_number" },
                { "data": "po.po_number" },
                { "data": "do_number_supplier" },
                { "data": "delivered_by" },
                { "data": "received_by" },
                { "data": "date_receive" },
                {
                    "mData": "recpart_supplier_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit' manually='0'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.po.po_number +" PO number??' data-url='/process/receiving-part-supplier/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                }

            ]
        });
    }


    async function return_value(e, data){
        var btn = $(e).attr("btn");
        var manually = $(e).attr("manually");
        $("#table").hide();
        document.documentElement.scrollTop = 0;

        if (btn == "edit"){
            $("#edit").show();
            $("#purchaseForm").attr("action", "/process/purchase-order/update");

            await fillToForm(data);


        } else {
            $("#input").show();
                $("#recPartForm").trigger("reset");
                $("#recPartForm").attr("action", "/process/receiving-part-supplier/add");
                $(".js-example-placeholder").trigger('change');
            if(manually == "1"){
                $("#by-scan").hide();
                is_manually = "1";
            }else{
                $("#by-scan").show();
                is_manually = "0";
            }

        }

    }

    async function fillToForm(data){
        var arrMonth = {!! json_encode(getMonth()) !!};
        var month = arrMonth.find(e => e.month === data.po.month_periode);
        $("#recpart_number_edit").val(data.recpart_number);
        $("#month_periode_edit").val(month.month_name);
        $("#year_periode_edit").val(data.po.year_periode);
        $("#po_id_edit").val(data.po.po_number+' - '+data.po.supplier.supplier_name);
        $("#do_number_supplier_edit").val(data.do_number_supplier);
        $("#delivered_by_edit").val(data.delivered_by);
        $("#received_by_edit").val(data.received_by);
        $("#date_receive_edit").val(data.date_receive);

        $("#bodyMaterialEdit").html('');
        data.recpart_item.forEach(function(part) {
            addMaterialEdit(part)
        });

    }

    async function periodeChange(){
        var month_periode = $('#month_periode').val();
        var year_periode = $('#year_periode').val();
        poList = [];
        dataListPO = [];
        await justFillOptionPO(month_periode, year_periode);
    }

    async function justFillOptionPO(month_periode, year_periode){
        if(month_periode !== "" && year_periode !== ""){
            dataListPO = await getData('/process/receiving-part-supplier/getbyperiode/'+month_periode+'/'+year_periode);
        }
        dataListPO.forEach(function(ls) {
            var ls = {
                id: ls.po_id,
                text: ls.po_number+' - '+ls.supplier.supplier_name,
            };
            poList.push(ls);
        });

        $("#po_id").html('<option value="">--select--</option>');
        $("#po_id").select2('destroy');
        $("#po_id").select2({
            data: poList,
            placeholder: "--select--"
        });
    }

    async function poidChange(){
        var po_id = $('#po_id').val();
        var dataPO = dataListPO.find(a => a.po_id === po_id);
        $("#bodyMaterial").html('');
        dataPO.po_items.forEach(function(part) {
            addMaterial(part)
        });
    }

    async function addMaterial(item){
        var qtyOrder = item.order;
        var qtyRecData = await getData('/process/receiving-part-supplier/getqtyreceive/'+item.poitem_id);
        var qtyRec = qtyRecData.length >= 1 ? qtyRecData[0].qty_in : 0;
        var qtyRemain = qtyOrder - qtyRec;
        var material = '<tr>';
        material +='<td>';
        material +='<input type="hidden" name="poitem_id[]" class="po_id" value="'+item.poitem_id+'">';
        material +='<input type="hidden" name="part_label[]" class="part_label">';
        material +='<input type="hidden" name="part_supplier_id[]" class="part_supplier_id" value="'+item.part_supplier.part_supplier_id+'">';
        material +='<label class="col-form-label">'+item.part_supplier.part_number+' - '+item.part_supplier.part_name+'</label>';
        material +='</td>';
        material +='<td>';
        material += '<select name="warehouse_id[]" class="js-example-placeholder col-sm-12" required>';
        material += '<option value="">--Select--</option>';
            @foreach($warehouseList as $ls)
                material += '<option value="{{ $ls->warehouse_id }}">{{ $ls->warehouse_name }}</option>';
            @endforeach
        material += '</select>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty[]" class="qty form-control" value="'+qtyOrder+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_received[]" class="qty_received form-control" value="'+qtyRec+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_remain[]" class="qty_remain form-control" value="'+qtyRemain+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number"'
        material += is_manually == "1" ? '' : 'readonly ';
        material += 'name="qty_in[]" class="qty_in form-control" onChange="qtyChange(this)" value="0"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_over[]" class="qty_over form-control" value="0"></td>';
        material +='</td>';
        material += '<td>';
        material += '<button type="button" class="btn waves-effect waves-light btn-info btn-icon" data-toggle="modal" data-target="#large-Modal" btn="add-label" onClick="bntDetailReceiving(this)"><i class="icofont icofont-edit"></i></button>'
        material +='</td>';
        material += '</tr>';

        $('#bodyMaterial').append(material);

        loadSelect2();
    }

    async function addMaterialEdit(item){
        var qtyOrder = item.po_item.order;
        var material = '<tr>';
        material +='<td>';
        material +="<input type='hidden' name='part_label[]' class='part_label' value='"+item.part_label_list+"'>";
        material +='<label class="col-form-label">'+item.po_item.part_supplier.part_number+' - '+item.po_item.part_supplier.part_name+'</label>';
        material +='</td>';
        material +='<td>';
        material +='<input type="text" readonly name="warehouse[]" class="warehouse form-control" value="'+item.warehouse.warehouse_name+'"></td>';
        material += '<select name="warehouse_id[]" class="js-example-placeholder col-sm-12" required>';

        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty[]" class="qty form-control" value="'+qtyOrder+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_received[]" class="qty_received form-control" value="'+(parseInt(qtyOrder) - (parseInt(item.qty_remain) + parseInt(item.qty_in) ))+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_remain[]" class="qty_remain form-control" value="'+(parseInt(item.qty_remain) + parseInt(item.qty_in))+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_in[]" class="qty_in form-control" onChange="qtyChange(this)" value="'+item.qty_in+'"></td>';
        material +='</td>';
        material +='<td>';
        material +='<input type="number" readonly name="qty_over[]" class="qty_over form-control" value="'+item.qty_over+'"></td>';
        material +='</td>';
        material += '<td>';
        material += '<button type="button" class="btn waves-effect waves-light btn-info btn-icon" data-toggle="modal" data-target="#large-Modal" btn="edit-label" onClick="bntDetailReceiving(this)"><i class="icofont icofont-edit"></i></button>'
        material +='</td>';
        material += '</tr>';

        $('#bodyMaterialEdit').append(material);

        loadSelect2();
    }

    function qtyChange(e){
        var qtyIn = parseInt($(e).val());
        var qtyOrder = parseInt($(e).parent().parent().find(".qty").val());
        var qtyReceived = parseInt($(e).parent().parent().find(".qty_received").val());
        var countQty = (parseInt(qtyIn) + parseInt(qtyReceived) - parseInt(qtyOrder));
        var qtyOver = countQty > 0 ? countQty : 0;
        $(e).parent().parent().find(".qty_over").val(qtyOver);

    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })
    $('.closeFormEdit').click(function(e) {
        $("#edit").hide();
        $("#table").show();
    })

    function addScanClick(){
        var packing_number = $("#packing_number").val();
        addToReceivingTable(packing_number);
    }

    $("#packing_number").keypress(function (ev) {
        var keycode = (ev.keyCode ? ev.keyCode : ev.which);
        if (keycode == '13') {
            var packing_number = $("#packing_number").val();
            addToReceivingTable(packing_number);
        }
    });

    async function addToReceivingTable(packing_number){
        if(packing_number !== ""){
            var data = await getData('/generate-part-label/get-label/'+packing_number);
            if(data != null){
                justAddToFieldReceiving(data);
            }
            else{
                swal('Info', 'Packing number ['+packing_number+'] is not valid, please scan other packing number', 'info');
            }
        }
    }

    function justAddToFieldReceiving(data){
        $('#receivingTable tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var part_supplier_id = $(this).find(".part_supplier_id").val();
            if(part_supplier_id === data.part_supplier_id){

                var qty_in = $(this).find(".qty_in").val();
                var label_list = $(this).find(".part_label").val();
                var dataLabel = label_list !== "" ? jQuery.parseJSON(label_list) : [];

                if(dataLabel.length >= 1){
                    var isScanned = dataLabel.find(x => x.part_label_id === data.part_label_id);
                    if(isScanned){
                        swal('Info', 'Packing number ['+data.part_label+']is scanned, please scan other packing number', 'info');
                    }else{
                        var total = parseInt(qty_in) + parseInt(data.standard_packing);
                        $(this).find(".qty_in").val(total);
                        var dataLabelAdded = justAddToPartLabel(dataLabel, data);
                        $(this).find(".part_label").val(dataLabelAdded);
                    }
                }else{
                    var total = parseInt(qty_in) + parseInt(data.standard_packing);
                    $(this).find(".qty_in").val(total);
                    var dataLabelAdded = justAddToPartLabel(dataLabel, data);
                    $(this).find(".part_label").val(dataLabelAdded);
                }

                $(this).find(".qty_in").trigger('change');
                $("#packing_number").val("");
            }
        });
    }

    function justAddToPartLabel(dataLabel, data){
        dataLabel.push(data);
        return JSON.stringify(dataLabel);
    };

    function saveInit(form){
        $("#is_manually").val(is_manually);
        if ($('input[name="poitem_id[]"]').length > 0){
            saveData(form, function() {
                periodeChangeFilter();
                loadSelect2();
                $('#bodyMaterial').html('');
                $("#input").hide();
                $("#table").show();
            });
        } else {
            swal('Error', 'Material items cannot be empty', 'error');
        }
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            periodeChangeFilter();
        });
    }

    function deleteInitLabel(e){
        var btn = $(e).attr("btn");
        if(btn == "edit-label"){
            return;
        }else{
            var text = $(e).attr("data-confirm").split('|');
            swal({
                title: text[0],
                text: text[1],
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: false
            },
            function(){
                var part_label = $(e).parent().parent().find(".part_label").val()
                var part_supplier_id = $(e).parent().parent().find(".part_supplier_id").val()
                recalculateReceiving(part_supplier_id, part_label);
                swal.close()
                $('#closeModal').click();
            });
        }

    };

    function recalculateReceiving(part_id, part_label){
        $('#receivingTable tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var part_supplier_id = $(this).find(".part_supplier_id").val();
            if(part_supplier_id === part_id){

                var label_list = $(this).find(".part_label").val();
                var dataLabel = label_list !== "" ? jQuery.parseJSON(label_list) : [];
                if(dataLabel.length >= 1){
                    var total = 0;
                    var labelListNew = [];
                    dataLabel.forEach(function(item){
                        if(item.part_label !== part_label){
                            total += parseInt(item.standard_packing);
                            labelListNew.push(item);
                        }
                    })

                    $(this).find(".qty_in").val(total);
                    $(this).find(".part_label").val(JSON.stringify(labelListNew));
                    $(this).find(".qty_in").trigger('change');
                    $("#packing_number").val("");
                }
            }
        });
    };


    function bntDetailReceiving(e){
        var btn = $(e).attr("btn");
        var labelList = $(e).parent().parent().find(".part_label").val();
        var dataLabel = labelList !== "" ? jQuery.parseJSON(labelList) : [];
        $("#partLabelDetailBody").html('');
        dataLabel.forEach(function(data) {
            justFillToBodyMaterialDetail(data, btn);
        });

    }

    function justFillToBodyMaterialDetail(data, btn){
        var material = '<tr>';
        material +='<td>';
        material += '<input type="hidden" class="part_label" value="'+data.part_label+'"/>';
        material += '<input type="hidden" class="part_supplier_id" value="'+data.part_supplier_id+'"/>';
        material += '<input type="hidden" class="standard_packing" value="'+data.standard_packing+'"/>';
        material += data.part_label.toUpperCase();
        material +='</td>';
        material +='<td>';
        material += data.standard_packing;
        material +='</td>';
        material +='<td>';
        material += "<button type='disabled' class='btn waves-effect waves-light btn-warning btn-icon' btn='"+btn+"' data-confirm='Are you sure|want to delete "+ data.part_label.toUpperCase() +" part label??' data-url='#' onClick='deleteInitLabel(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
        material +='</td>';
        material +='</tr>';

        $('#partLabelDetailBody').append(material);
    }

    function saveMaterial(){

        if (!$("#materialForm")[0].checkValidity()) {
            $("#materialForm")[0].reportValidity();
        } else {
            addMaterial();
        }
    }

    function return_material(e, material){
        $("#materialForm").trigger("reset");

        if($("#supplier_id").val() == "" || $("#month_periode").val() == "" || $("#year_periode").val() == ""){
            swal('Error', 'supplier and period cannot be empty', 'error');
        } else {
            $('#large-Modal').modal('show');
            if (material == ""){

            } else {
                material.forEach(function(part) {
                    $("#"+part.name).val(part.value);
                });
                $("#part_id").trigger('change');
            }
        }
    }



</script>
@endsection

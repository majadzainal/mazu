@extends('layouts.headerIn')
@section('content')
<style type="text/css">
    page {
        background: white;
        display: block;
        margin: 0;
        padding: 0;
    }
    page[size="A4"] {
        width: 21cm;
        height: 29.7cm;
    }

    .td-main{
        width: 50%;
        color: #000;
    }
    .table-label {
        border-collapse: collapse;
        width: 100%;
        color: #000;
        color: #000;
    }

    .td-label {
        width: 50%;
        border: 2px solid #000;
        padding : 3px;
        color: #000;
        text-align:center;
    }

    .th-label {
        border: 2px solid #000;
        padding : 3px;
        color: #000;
        background-color:#dfebf0;
    }

    .td-left{
        width: 25%;
        text-align: left;
        font-size: 12px;
        color: #000;
        border: 2px solid #000;
        padding-right: 2px;
        padding-left: 2px;
    }
    .td-right{
        width: 75%;
        text-align: left;
        color: #000;
        border: 2px solid #000;
        padding-right: 2px;
        padding-left: 2px;
    }
    .tr-qrcode{
        height: 100px;
    }

    .td-left-qr{
        width: 25%;
        text-align: center;
        font-size: 25px;
        font-weight: bold;
        border: 2px solid #000;
        padding-right: 2px;
        padding-left: 2px;
        color: #000;
    }
    .td-right-qr{
        width: 75%;
        text-align: center;
        border: 2px solid #000;
        padding-right: 2px;
        padding-left: 2px;
        color: #000;
    }

    .table-part{
        float: left;
    }

</style>
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10"> GENERATE PART LABEL TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Part</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Generate Part Label</a>
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
                        <div class="col-sm-12" id="table">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Part Label</h5>
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
                                        <label class="col-sm-2 col-form-label">Supplier <span class="text-danger"> *</span></label>
                                        <div class="col-sm-6">
                                            <select name="supplier_id_filter" id="supplier_id_filter" onChange="supplierChangeFilter()" class="js-example-placeholder col-sm-12" required>
                                                <option value="">--Select--</option>
                                                @foreach($supplierList as $ls)
                                                    <option value="{{ $ls->supplier_id }}">{{ $ls->business_entity.". ".$ls->supplier_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Part Supplier <span class="text-danger"> *</span></label>
                                        <div class="col-sm-6">
                                            <select name="part_supplier_id_filter" id="part_supplier_id_filter" onChange="partSupplierChangeFilter()" class="js-example-placeholder col-sm-12" required>
                                                <option value="">--Select--</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6" style="text-align:left;">
                                            <button class="btn btn-success btn-sm btn-round waves-effect waves-light" id="check-all" btn="cek_all" onClick="checkAll(this)"></i> Select All Label </button>
                                            <button class="btn btn-danger btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" onClick="printLabel()"> Print Selected Label</button>
                                        </div>
                                        @if(isAccess('create', $MenuID))
                                        <div class="col-sm-6" style="text-align:right;">
                                            <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Generate Label</button>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="searchTable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Print</th>
                                                    <th>Label ID</th>
                                                    <th>Part Name</th>
                                                    <th>Standard Packing</th>
                                                    <th>Unit</th>
                                                    <th>Divisi</th>
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
                                    <h5>Generate Part Label</h5>
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
                                    <form action="/generate-part-label/add" method="post" enctype="multipart/form-data" id="partLabelForm">
                                        @csrf
                                        <input type="hidden" name="itemList" id="itemList">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Supplier <span class="text-danger"> *</span></label>
                                            <div class="col-sm-6">
                                                <select name="supplier_id" id="supplier_id" onChange="supplierChange()" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($supplierList as $ls)
                                                        <option value="{{ $ls->supplier_id }}">{{ $ls->business_entity.". ".$ls->supplier_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Import From Excel</label>
                                            <div class="col-sm-6 input-group input-group-button">
                                                <input type="file" class="form-control" name="import_part" id="import_part" accept=".xls, .xlsx" placeholder="Choose File .xlsx">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" onClick="importExcelClick('#import_part')" type="button">Upload Excel</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="form-group row modal-footer">
                                        <label class="col-sm-6"></label>
                                        <div class="col-sm-6" style="text-align:right;">
                                            <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                            <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#partLabelForm')">Generate Label</button>
                                        </div>
                                    </div>
                                    <div class="dt-responsive table-responsive">
                                        <table id="partTable" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="5%">No</th>
                                                    <th>Part ID</th>
                                                    <th>Part Name</th>
                                                    <th>Qty Label</th>
                                                    <th>Standard Packing</th>
                                                    <th>Unit</th>
                                                    <th>Divisi</th>
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
<!--MODAL PRINT LABEL--->
<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Print Preview Part Label</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="form-group row">
                    <div class="col-sm-12 text-right">
                        <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                        <button type="button"
                                class="btn btn-danger waves-effect"
                                data-confirm="Are you sure|want to print part label"
                                data-id="#print_area"
                                form-id="#partLabelFormUpdate"
                                data-url="/generate-part-label/update-print"
                                onClick="printInit(this)"
                                id="print-label-now">Print</button>
                    </div>
                </div>
                <table id="print_area" style="width: 100%; border: 1px">
                    <thead><tr><th></th><th></th></tr></thead>
                    <tbody id="tableBodyPrint">
                        {{-- <tr><td><table class="table-label"><tr class="tr-label"><td colspan="2" class="td-label">PT. REKADAYA MULTI ADIPRIMA</td></tr><tr><td>OKE</td><td>OKE</td></tr></td></table><td><table class="table-label"><tr class="tr-label"><td colspan="2" class="td-label">PT. REKADAYA MULTI ADIPRIMA</td></tr><tr><td>OKE</td><td>OKE</td></tr></td></table></tr> --}}
                    </tbody>
                </table>
                {{-- <div id="print_area" class="text-center">


                </div> --}}
                <div class="clear-both"></div>
                <form action="/generate-part-label/update-print" method="post" enctype="multipart/form-data" id="partLabelFormUpdate">
                    @csrf
                    <input type="hidden" name="itemListUpdate" id="itemListUpdate">
                </form>
            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                <button type="button"
                    class="btn btn-danger waves-effect"
                    data-confirm="Are you sure|want to print part label"
                    data-id="#print_area"
                    form-id="#partLabelFormUpdate"
                    data-url="/generate-part-label/update-print"
                    onClick="printInit(this)"
                    id="print-label-now">Print</button>
            </div>
        </div>
    </div>
</div>
<!--MODAL PRINT LABEL--->
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        supplierChangeFilter();
        $("#input").hide();
        $("#table").show();
    } );

    function loadData(part_supplier_id){
        $('#searchTable').DataTable().destroy();
        $('#searchTable').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/generate-part-label/load-label/'+part_supplier_id,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="checkbox" class="checked_print">';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.part_label.toUpperCase();
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.part_number+' - '+row.part_name;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var renVal = '';
                        renVal += '<input type="hidden" value="'+row.part_number+'" class="part_number">';
                        renVal += '<input type="hidden" value="'+row.part_name+'" class="part_name">';
                        renVal += '<input type="hidden" value="'+row.part_label+'" class="part_label">';
                        renVal += '<input type="hidden" value="'+row.part_label_id+'" class="part_label_id">';
                        renVal += '<input type="hidden" value="'+row.unit_name+'" class="unit_name">';
                        renVal += '<input type="number" disabled min="0" value="'+row.standard_packing+'" class="standard_packing text-right" required>';
                        return renVal;
                    }
                },
                { "data": "unit_name" },
                { "data": "divisi_name" },
            ]
        });
    }
    function loadDataPart(supplier_id, items){
        $('#partTable').DataTable().destroy();
        $('#partTable').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "columnDefs": [
                { "targets": 1, "visible": false },
            ],
            "dom": 'Bfrtip',
            "buttons": [
                {extend:'excel',exportOptions: {
                    format: {
                        body: function ( data, row, column, node ) {
                            return $(data).is("input") ? $(data).val() : data;
                        }
                    },
                }}
            ],
            "ajax": '/generate-part-label/load/'+supplier_id,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="text" value="'+row.part_supplier_id+'">';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.part_number+' - '+row.part_name;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var qtyLabel = 0;
                        var item = items != '' ? items.find(a => a.part_supplier_id === row.part_supplier_id) : '';
                        qtyLabel = item.qtyLabel ? item.qtyLabel : 0;
                        return '<input type="number" min="0" value="'+qtyLabel+'" class="qtyLabel text-right" required>';
                    },
                },
                {  "mRender": function (data, type, row, num) {
                        var renVal = '';
                        renVal += '<input type="hidden" value="'+row.part_number+'" class="part_number">';
                        renVal += '<input type="hidden" value="'+row.part_supplier_id+'" class="part_supplier_id">';
                        renVal += '<input type="number" disabled min="0" value="'+row.standard_packing+'" class="standard_packing text-right" required>';
                        return renVal;
                    }
                },
                { "data": "unit_name" },
                { "data": "divisi_name" },
            ]
        });
    }

    function return_value(e, data){
        var table = $('#partTable').DataTable();
        table.clear().draw();

        $("#partLabelForm").trigger("reset");
        $("#partLabelForm").attr("action", "/generate-part-label/add");
        $("#defaultModalLabel").text("Generate Label")


        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;
    }

    function saveInit(form, modalId){
        var itemList = getItemList()
        document.getElementById('itemList').value = JSON.stringify(itemList);
        saveDataModal(form, modalId, function() {
            partSupplierChangeFilter();
            $('.closeForm').click();
        });
    }

    function getItemList(){
        $("#partTable").DataTable().search("").draw()
        var itemList = [];
        $('#partTable tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var part_supplier_id = $(this).find(".part_supplier_id").val();
            var part_number = $(this).find(".part_number").val();
            var qtyLabel = $(this).find(".qtyLabel").val();
            var standard_packing = $(this).find(".standard_packing").val();
            data = {
                part_supplier_id: part_supplier_id,
                part_number: part_number,
                qtyLabel: qtyLabel,
                standard_packing: standard_packing,
            };
            itemList.push(data);
        });

        return itemList;
    }

    function supplierChange(){
        var supplier_id = $("#supplier_id").val();
        if(supplier_id != ""){
            loadDataPart(supplier_id, '');
        }
    }
    function supplierChangeFilter(){
        var supplier_id = $("#supplier_id_filter").val();
        var arrPartSupplier = {!! json_encode($partList) !!};
        var partSupplier = arrPartSupplier.filter(e => e.supplier_id === supplier_id);
        var dataPart = [];

        partSupplier.forEach(function(part) {
            var part = {
                id: part.part_supplier_id,
                text: part.part_number+" - "+part.part_name
            };
            dataPart.push(part);
        });

        $("#part_supplier_id_filter").html('<option value="">--select--</option>');
        $("#part_supplier_id_filter").select2('destroy');
        $("#part_supplier_id_filter").select2({
            data: dataPart,
            placeholder: "--select--"
        });
    }

    function partSupplierChangeFilter(){
        var part_supplier_id = $("#part_supplier_id_filter").val();
        if(part_supplier_id !== ""){
            loadData(part_supplier_id);
            $("#check-all").attr("btn", "cek_all");
            $("#check-all").text("Select All Label");
        }
    }

    function checkAll(e){
        $("#searchTable").DataTable().search("").draw()
        var btn = $(e).attr("btn");
        if (btn == "cek_all"){
            $('#searchTable tr').each(function() {
                if (!this.rowIndex) return; // skip first row
                $(this).find(".checked_print").prop('checked', true);
            });

            $(e).attr("btn", "uncek_all");
            $(e).text("Unselect All Label");
        }else if (btn == "uncek_all"){
            $('#searchTable tr').each(function() {
                if (!this.rowIndex) return; // skip first row
                $(this).find(".checked_print").prop('checked', false);
            });

            $(e).attr("btn", "cek_all");
            $(e).text("Select All Label");
        }


    }

    function printLabel(){
        $("#tableBodyPrint").html('');

        var itemList = getItemPrint();
        if(itemList.length >= 1){
            var i = 1;
            var label = "";
            itemList.forEach(function(data){
                if(i % 2 !== 0 ){
                    label = '<tr>'
                }

                var labelCol = justCreateLabel(data, i);
                label += labelCol;
                console.log(i%2);
                if(i%2 === 0 || i === itemList.length){
                    label += '</tr>';
                    console.log(label);
                    $('#tableBodyPrint').append(label);
                }

                i++;

            });

            itemList.forEach(function(data){
                var classQr = ".qrClass"+data.part_label_id;
                $(classQr).qrcode({width: 100,height: 100,text: data.part_label});
            });
        }
    }

    function justCreateLabel(data, i){
        var label = "";
        var classQr = "qrClass"+data.part_label_id;
        label += '<td class="td-main"><table class="table-label">';
        label += '<tr class="tr-label"><td colspan="2" class="td-label">PT. REKADAYA MULTI ADIPRIMA</td></tr>';
        label += '<tr><td class="td-left">Part Number</td>';
        label += '<td class="td-right">'+data.part_number.toUpperCase()+'</td></tr>';
        label += '<tr><td class="td-left">Part Name</td>';
        label += '<td class="td-right">'+data.part_name.toUpperCase()+'</td></tr>';
        label += '<tr><td class="td-left">Packing Number</td>';
        label += '<td class="td-right">'+data.part_label+'</td></tr>';
        label += '<tr><td class="td-left">Unit Name</td>';
        label += '<td class="td-right">'+data.unit_name+'</td></tr>';
        label += '<tr><td class="td-left">Standard Packing</td>';
        label += '<td rowspan="2" class="td-right-qr"><div class="'+classQr+'"></td></tr>';
        label += '<tr class="tr-qrcode"><td class="td-left-qr">'+data.standard_packing+'</td></tr>';
        label += '</td></table>';
        // var mod = i % 8;

        // label += '<div class="box-label">';
        // label += '<div class="part-label-header text-center bd-t bd-r bd-l ft-bold">';
        // label += 'PT. REKADAYA MULTI ADIPRIMA';
        // label += '</div>';
        // label += '<div class="line1">';
        // label += '<div class="left-box text-left ft-11 bd-t bd-l bd-r">';
        // label += 'Part Number';
        // label += '</div>';
        // label += '<div class="right-box text-left ft-11 bd-t bd-r">';
        // label += data.part_number.toUpperCase();
        // label += '</div>';
        // label += '</div>';
        // label += '<div class="line2">';
        // label += '<div class="left-box text-left ft-11 bd-t bd-l bd-r">';
        // label += 'Part Name';
        // label += '</div>';
        // label += '<div class="right-box text-left ft-11 bd-t bd-r">';
        // label += data.part_name;
        // label += '</div>';
        // label += '</div>';
        // label += '<div class="line2">';
        // label += '<div class="left-box text-left ft-11 bd-t bd-l bd-r">';
        // label += 'Packing Number';
        // label += '</div>';
        // label += '<div class="right-box text-left ft-11 bd-t bd-r">';
        // label += data.part_label;
        // label += '</div>';
        // label += '</div>';
        // label += '<div class="line2">';
        // label += '<div class="left-box text-left ft-11 bd-t bd-l bd-r">';
        // label += 'Unit';
        // label += '</div>';
        // label += '<div class="right-box text-left ft-11 bd-t bd-r">';
        // label += data.unit_name;
        // label += '</div>';
        // label += '</div>';
        // label += '<div class="line2">';
        // label += '<div class="outer-box-qr-left">';
        // label += '<div class="left-box-qr1 text-left ft-11 bd-t bd-l bd-r">';
        // label += 'Standard Packing';
        // label += '</div>';
        // label += '<div class="left-box-qr2 text-center ft-18 ft-bold bd-t bd-l bd-r bd-b">';
        // label += data.standard_packing;
        // label += '</div>';
        // label += '</div>';
        // label += '<div class="outer-box-qr-right">';
        // label += '<div class="right-box-qr ft-11 bd-t bd-r bd-b">';
        // label += '<div class="'+classQr+'">';
        // label += '</div>';
        // label += '</div>';
        // label += '</div>';
        // label += '</div>';
        // label += '</div>';
        // if(mod === 0){
        //     label += '<div class="clear-both"></div>';
        //     label += '<div class="box-space"></div>';
        // }

        return label;
        // $('.qr-code').;
    }

    function getItemPrint(){
        $("#searchTable").DataTable().search("").draw()
        var itemList = [];
        $('#searchTable tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var checked_print = $(this).find(".checked_print").prop("checked") ? true : false;
            var part_label_id = $(this).find(".part_label_id").val();
            var part_label = $(this).find(".part_label").val();
            var part_number = $(this).find(".part_number").val();
            var part_name = $(this).find(".part_name").val();
            var unit_name = $(this).find(".unit_name").val();
            var standard_packing = $(this).find(".standard_packing").val();
            if(checked_print){
                data = {
                    part_label_id: part_label_id,
                    part_label: part_label,
                    part_number: part_number,
                    part_name: part_name,
                    unit_name: unit_name,
                    standard_packing: standard_packing,
                };
                itemList.push(data);
            }

        });

        return itemList;
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
                part_supplier_id: data['Part ID'],
                qtyLabel: data['Qty Label'],
            };

            itemList.push(item);
        })
        var supplier_id = $("#supplier_id").val();
        if(supplier_id != ""){
            loadDataPart(supplier_id, itemList);
        }else{
            swal('Info', 'Please select supplier.', 'info');
        }
    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function validateForm(){
        var is_error = 0;
        is_error += validateRequired('#divisi_name', '#divisi_name_error');

        return is_error === 0 ? true : false;
    }

    function printInit(e){
        var itemList = getItemPrint()
        document.getElementById('itemListUpdate').value = JSON.stringify(itemList);
        printConfirm(e, function() {
            $("#closeModal").click();
        });
    }

</script>
@endsection

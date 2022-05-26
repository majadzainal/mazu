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
                        <h5 class="m-b-10"> GENERATE LABEL PRODUCT TABLE</h5>
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
                        <li class="breadcrumb-item"><a href="#!">Generate Label LABEL</a>
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
                                    <h5>Generate Label Product</h5>
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
                                        <label class="col-sm-2 col-form-label">Product <span class="text-danger"> *</span></label>
                                        <div class="col-sm-10">
                                            <select name="product_id_filter_multi" id="product_id_filter_multi" onChange="productChangeFilterMulti()" class="js-example-basic-multiple col-sm-12" multiple="multiple">
                                                @foreach($productList as $ls)
                                                    <option value="{{ $ls->product_id }}">{{ $ls->category->category_code."-".$ls->product_code." ".$ls->product_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6" style="text-align:left;">
                                            <button type="button" class="btn btn-success btn-sm btn-round waves-effect waves-light" id="check-all" btn="cek_all" onClick="checkAll(this)"></i> Select All Label </button>
                                            {{-- <button class="btn btn-danger btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" onClick="printLabel()"> Print Selected Label</button> --}}
                                            <button type="button" class="btn btn-danger btn-sm btn-round waves-effect waves-light" onClick="printLabel()"> Print Selected Label</button>
                                            <form action="/master/generate-label-product/update" method="post" enctype="multipart/form-data" id="partLabelFormUpdate">
                                                @csrf
                                                <input type="hidden" name="itemListUpdate" id="itemListUpdate">
                                            </form>

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
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Unit</th>
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
                                    <form action="/master/generate-label-product/add" method="post" enctype="multipart/form-data" id="partLabelForm">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Product <span class="text-danger"> *</span></label>
                                            <div class="col-sm-6">
                                                <select name="product_id" id="product_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($productList as $ls)
                                                        <option value="{{ $ls->product_id }}">{{ $ls->category->category_code."-".$ls->product_code." ".$ls->product_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Qty Label <span class="text-danger"> *</span></label>
                                            <div class="col-sm-6">
                                                <input type="number" name="qty_generate" id="qty_generate" class="form-control" required>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="form-group row modal-footer">
                                        <label class="col-sm-6"></label>
                                        <div class="col-sm-6 text-left">
                                            <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                            <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#partLabelForm')">Generate Label</button>
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
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Print Preview Part Label</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <div class="form-group row text-center">
                    <div style="width:384px">
                        <table id="print_area" style="width: 100%; border: 1px solid;">
                            <tbody id="tableBodyPrint">

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="clear-both"></div>

            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                <button type="button"
                    class="btn btn-danger waves-effect"
                    data-confirm="Are you sure|want to print part label"
                    data-id="#print_area"
                    form-id="#partLabelFormUpdate"
                    data-url="/master/generate-label-product/update"
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
        productChangeFilterMulti();
        $("#input").hide();
        $("#table").show();
    } );

    function loadData(product_id){
        $('#searchTable').DataTable().destroy();
        $('#searchTable').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/generate-label-product/load/'+product_id,
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
                        return row.no_label.toUpperCase();
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return row.product.category.category_code+' - '+row.product.product_code;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var product_code = row.product.category.category_code+' - '+row.product.product_code;
                        var renVal = '';
                        renVal += '<input type="hidden" value="'+row.label_product_id+'" class="label_product_id">';
                        renVal += '<input type="hidden" value="'+row.no_label+'" class="no_label">';
                        renVal += '<input type="hidden" value="'+product_code+'" class="product_code">';
                        renVal += row.product.product_name;
                        return renVal;
                    }
                },
                { "data": "product.unit.unit_name" },
            ]
        });
    }

    function return_value(e, data){
        var table = $('#partTable').DataTable();
        table.clear().draw();

        $("#partLabelForm").trigger("reset");
        $("#partLabelForm").attr("action", "/master/generate-label-product/add");
        $("#defaultModalLabel").text("Generate Label")


        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;
    }

    function saveInit(form, modalId){
        saveDataModal(form, modalId, function() {
            productChangeFilterMulti();
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

    function productChangeFilter(){
        var product_id = $("#product_id_filter").val();
        if(product_id !== ""){
            loadData(product_id);
            $("#check-all").attr("btn", "cek_all");
            $("#check-all").text("Select All Label");
        }
    }

    function productChangeFilterMulti(){
        var product_id = $("#product_id_filter_multi").val();
        var arrStr = encodeURIComponent(JSON.stringify(product_id));
        if(product_id !== ""){
            loadData(arrStr);
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
            // var itemList = getItemPrint()
            document.getElementById('itemListUpdate').value = JSON.stringify(itemList);
            printData("#partLabelFormUpdate");
            // var i = 1;
            // var label = "";
            // itemList.forEach(function(data){
            //     var classQr = "qrClass"+data.label_product_id;
            //     label += '<tr style="width:384px; border:1px">';
            //     label += '<td>';
            //     label += '<table style="width:100%; border:none;">';
            //     label += '<tr>';
            //     label += '<td class="text-center">';
            //     label += '<div class="'+classQr+'"></div>';
            //     label += '</td>';
            //     label += '</tr>';
            //     label += '<tr>';
            //     label += '<td class="text-center">';
            //     label += data.no_label;
            //     label += '</td>';
            //     label += '</tr>';
            //     label += '<tr>';
            //     label += '<td class="text-center">';
            //     label += data.product_code;
            //     label += '</td>';
            //     label += '</tr>';
            //     label += '</table>';
            //     label += '</td>';
            //     label += '</tr>';

            // });

            // $('#tableBodyPrint').append(label);

            // itemList.forEach(function(data){
            //     var classQr = ".qrClass"+data.label_product_id;
            //     $(classQr).qrcode({width: 100,height: 100,text: data.no_label});
            // });
        }
    }

    function printData(form){

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        if (!$(form)[0].checkValidity()) {
            $(form)[0].reportValidity();
        } else {
            $.ajax({
                type: "POST",
                url: $(form).attr("action"),
                data: $(form).serialize(),
                success: function(res){
                    if(res.status.toLowerCase() === 'Success'.toLowerCase()){
                        swal(res.status, res.message, res.status.toLowerCase());
                        $(form).trigger("reset");
                        window.open('/master/print-generate-label-product/'+res.print_id, '_blank');
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status);
                    alert(thrownError);
                }
            });
        }

        }

    function getItemPrint(){
        $("#searchTable").DataTable().search("").draw()
        var itemList = [];
        $('#searchTable tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var checked_print = $(this).find(".checked_print").prop("checked") ? true : false;
            var label_product_id = $(this).find(".label_product_id").val();
            var no_label = $(this).find(".no_label").val();
            var product_code = $(this).find(".product_code").val();
            if(checked_print){
                data = {
                    label_product_id: label_product_id,
                    no_label: no_label,
                    product_code: product_code,
                };
                itemList.push(data);
            }

        });

        return itemList;
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

    function printInit(e){
        window.open('/master/print-generate-label-product/print', '_blank');
        // var itemList = getItemPrint()
        // document.getElementById('itemListUpdate').value = JSON.stringify(itemList);
        printConfirm(e, function() {
            $("#closeModal").click();
        });

        // productChangeFilter();
        // $("#closeModal").click();
    }

</script>
@endsection

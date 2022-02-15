@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">Delivery ORDER OUTLET TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Delivery Order Outlet</a></li>
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
                                    <h5>Delivery Order Outlet</h5>
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
                                        <button type="button" class="btn btn-primary btn-sm btn-round waves-effect waves-light" btn="add" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle"></i> Add</button>
                                    @endif
                                    <div class="form-group row">
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
                                                    <th>DO Number</th>
                                                    <th>Outlet Name</th>
                                                    <th>Description</th>
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
                                    <h5>Delivery Order Outlet</h5>
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
                                    <form action="/process/delivery-order-outlet/add" method="post" enctype="multipart/form-data" id="doOutletForm">
                                        @csrf
                                        <input type="hidden" name="do_outlet_id" id="do_outlet_id">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Delivery Order Outlet Number </label>
                                            <div class="col-sm-5">
                                                <input type="input" readonly name="do_number" id="do_number" class="form-control">
                                            </div>
                                            <label class="col-sm-5 col-form-label">Auto generate after save as process</label>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Outlet <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="outlet_id" id="outlet_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($outletList as $ls)
                                                        <option value="{{ $ls->outlet_id }}">{{ $ls->outlet_code." - ".$ls->outlet_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">DO. Date <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="date" name="do_date" id="do_date" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Description</label>
                                            <div class="col-sm-10">
                                                <input type="text" name="description" id="description" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Scan QR</label>
                                            <div class="col-sm-6 input-group input-group-button">
                                                <input type="text" class="form-control" name="product_label" id="product_label">
                                                <div class="input-group-append">
                                                    <button type="button" class="btn btn-primary" onClick="addScanClick()" type="button">Add</button>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <button type="button" class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal"><i class="icofont icofont-plus-circle"></i> Add Product</button>
                                            </div>
                                        </div>
                                        <div class="dt-responsive table-responsive">
                                            <table id="doItemTableList" width="100%" class="display table table-bordered table-striped nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Action</th>
                                                        <th width="60%">Product Name</th>
                                                        <th width="40%">Qty</th>
                                                        <th>Unit</th>
                                                        <th width="80%">Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyProduct">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <input type="hidden" name="is_process" id="is_process">
                                                <input type="hidden" name="is_draft" id="is_draft">
                                                <button type="button" id="btnCancelPO" class="btn btn-danger waves-effect waves-light" onClick="cancelInit('#btnCancelPO')">Batalkan Process</button>
                                                <button type="button" class="btn btn-secondary waves-effect waves-light" onClick="saveInit('#doOutletForm', 0)">Save as draft</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#doOutletForm', 1)">Save</button>
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
    @php
        $monthList = getMonth();
    @endphp
</div>
<div id="styleSelector"></div>
<div class="modal fade" id="large-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Select Product</h4>
                <button type="button" class="close" id="closeAddProduct" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="dt-responsive table-responsive">

                    {{-- <table id="soItemTableList" class="table table-striped table-bordered"> --}}
                    <table id="productTableList" width="100%" class="display table table-bordered table-striped nowrap">
                    {{-- <table id="soItemTableList" class="table table-striped table-bordered nowrap dt-responsive width-100"> --}}
                        <thead>
                            <tr>
                                <th width="2%">No</th>
                                <th width="30%">Foto</th>
                                <th>Product Code</th>
                                <th>Product Code</th>
                                <th>Product Name</th>
                                <th>Product Description</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="large-Modal1" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Product label number.</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table id="partLabelDetail" class="table table-striped">
                        <thead>
                            <tr>
                                <th>No. Product Label</th>
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
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        var date = new Date();
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

        $("#start_date").val(moment(firstDay).format('YYYY-MM-DD'));
        $("#end_date").val(moment(lastDay).format('YYYY-MM-DD'));

        btnLoadDataClick();

        loadProduct();
        $("#input").hide();
    } );

    function btnLoadDataClick(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        loadData(start_date, end_date);
    }

    function loadData(start_date, end_date){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/delivery-order-outlet/load/'+start_date+'/'+end_date,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "do_outlet_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete outlet "+ row.outlet.outlet_name +" ??' data-url='/process/delivery-order-outlet/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "do_number" },
                { "data": "outlet.outlet_name" },
                { "data": "description" },
            ]
        });
    }

    function loadProduct(){
        $('#productTableList').DataTable().destroy();
        var table = $('#productTableList').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/delivery-order-outlet/load-product',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var productImage = '{{ asset ("/uploads") }}' + "/" + row.images;
                        var img = "";
                        img += '<div class="row">';
                        img += '<img class="text-center" style="width:200px;" src="'+productImage+'"/>';
                        img += '</div>';
                        return img;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var item = "";
                        var btnAdd = "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='addProductToDO("+ JSON.stringify(row) +", "+item+")'>&nbsp;<i class='icofont icofont-plus'></i> </button>";
                        return btnAdd;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var productCode = row.category.category_code +"-"+row.product_code;
                        return productCode;
                    }
                },
                { "data": "product_name" },
                { "data": "product_description" },
            ]
        });
    }

    function addProductToDO(data, item){
        var qty = 0;
        var description = "";
        var product_label_list = "";
        if(item != undefined){
            qty = item.qty != undefined ? item.qty : qty;
            description = item.description != undefined ? item.description : description;
            product_label_list = item.product_label_list != undefined ? item.product_label_list : product_label_list;
        }


        var addProduct = '<tr>';

            addProduct += '<td>';
            addProduct += "<button type='button' class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete purchase order item ??' onClick='deleteInitPOItem(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
            addProduct += '<button type="button" class="btn waves-effect waves-light btn-info btn-icon" data-toggle="modal" data-target="#large-Modal1" btn="add-label" onClick="bntDetailReceiving(this)">&nbsp;<i class="icofont icofont-table"></i></button>'
            addProduct += '</td>';
            addProduct += '<td>';
            addProduct += "<input type='hidden' value='"+ product_label_list +"' name='product_label_list[]' class='product_label_list form-control'>";
            addProduct += '<input type="text" value="'+ data.product_name +'" readonly name="product_name[]"  class="product_name form-control" style="width:300px" required>';
            addProduct += '<input type="hidden" value="'+ data.product_id +'" readonly name="product_id[]"  class="product_id form-control" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="number" value="'+qty+'" name="qty[]"  class="qty form-control" style="width:150px; text-align:center;" required>';
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += data.unit.unit_name;
            addProduct += '</td>';

            addProduct += '<td>';
            addProduct += '<input type="text" value="'+description+'" name="description_item[]"  class="description_item form-control" style="width:500px">';
            addProduct += '</td>';

            addProduct += '</tr>';

        $("#bodyProduct").append(addProduct);

        $("#closeAddProduct").click();
    }


    async function return_value(e, data){
        $("#bodyProduct").html("");
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#doOutletForm").attr("action", "/process/delivery-order-outlet/update");
            $("#do_outlet_id").val(data.do_outlet_id);
            $("#outlet_id").val(data.outlet_id);
            $('#outlet_id').trigger('change');
            $("#do_number").val(data.do_number);
            $("#do_date").val(data.do_date);
            $("#description").val(data.description);

            data.items.forEach(item => {
                addProductToDO(item.product, item);
            });


        } else {
            $("#doOutletForm").trigger("reset");
            $("#btnCancelPO").hide();
            $("#doOutletForm").attr("action", "/process/delivery-order-outlet/add");

        }
        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;
    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form, is_process){
        $("#is_process").val(is_process === 1 ? 1 : 0);
        $("#is_draft").val(is_process === 0 ? 1 : 0);
        saveDataModal(form, '.closeForm', function() {
            btnLoadDataClick();
            loadSelect2();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            btnLoadDataClick();
        });
    }

    function deleteInitPOItem(e){
        confirmDeleteRow(e, function(){
            var row = e.parentNode.parentNode;
            row.parentNode.removeChild(row);
        });
    }

    function addScanClick(){
        var product_label = $("#product_label").val();
        addToReceivingTable(product_label);
    }

    $("#product_label").keypress(function (ev) {
        var keycode = (ev.keyCode ? ev.keyCode : ev.which);
        if (keycode == '13') {
            var product_label = $("#product_label").val();
            addToReceivingTable(product_label);
        }
    });

    async function addToReceivingTable(product_label){
        if(product_label !== ""){
            var data = await getData('/process/delivery-order-outlet/get-label/'+product_label);
            if(data != null){
                addToTableProductItem(data);
            }
            else{
                swal('Info', 'Product label ['+product_label+'] is not valid, please scan other product label', 'info');
            }
        }
    }

    function addToTableProductItem(data){
        var isAdded = false;
        $('#doItemTableList tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var product_id = $(this).find(".product_id").val();
            if(product_id === data.product_id){
                isAdded = true;
                var qty = $(this).find(".qty").val();
                var product_label_list = $(this).find(".product_label_list").val();
                var dataLabel = product_label_list !== "" ? jQuery.parseJSON(product_label_list) : [];

                if(dataLabel.length >= 1){
                    var isScanned = dataLabel.find(x => x.label_product_id === data.label_product_id);
                    if(isScanned){
                        swal('Info', 'Product label ['+data.no_label+']is scanned, please scan other product label', 'info');
                    }else{
                        var finQty = parseInt(qty) + parseInt(1);
                        $(this).find(".qty").val(finQty);
                        var dataLabelAdded = justAddToProductLabel(dataLabel, data);
                        $(this).find(".product_label_list").val(dataLabelAdded);
                    }
                }else{
                    var finQty = parseInt(qty) + parseInt(1);
                    $(this).find(".qty").val(finQty);
                    var dataLabelAdded = justAddToProductLabel(dataLabel, data);
                    $(this).find(".product_label_list").val(dataLabelAdded);
                }
            }
        });

        if(!isAdded){
            var labelList = [];
            labelList.push(data);
            var productList = {!! json_encode($productList) !!};
            var productData = productList.find(x => x.product_id === data.product_id);
            var item = {
                qty: 1,
                description: "",
                product_label_list: JSON.stringify(labelList),
            };
            addProductToDO(productData, item);
        }

        $("#product_label").val("");
    }

    function justAddToProductLabel(dataLabel, data){
        dataLabel.push(data);
        return JSON.stringify(dataLabel);
    };

    function confirmDeleteRow(e, callback){
        var text = $(e).attr("data-confirm").split('|');
        swal({
            title: text[0],
            text: text[1],
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "Yes",
            cancelButtonText: "No",
            closeOnConfirm: true,
            closeOnCancel: true,
        },
        function(){
            callback();
        });
    }

    function bntDetailReceiving(e){
        var btn = $(e).attr("btn");
        var labelList = $(e).parent().parent().find(".product_label_list").val();
        var dataLabel = labelList !== undefined ? jQuery.parseJSON(labelList) : [];
        $("#partLabelDetailBody").html('');
        dataLabel.forEach(function(data) {
            justFillToBodyMaterialDetail(data, btn);
        });
    }

    function justFillToBodyMaterialDetail(data, btn){
        var material = '<tr>';
        material +='<td>';
        material += '<input type="hidden" class="no_label" value="'+data.no_label+'"/>';
        material += '<input type="hidden" class="product_id" value="'+data.product_id+'"/>';
        material += data.no_label.toUpperCase();
        material +='</td>';
        material +='<td>';
        material += "<button type='disabled' class='btn waves-effect waves-light btn-warning btn-icon' btn='"+btn+"' data-confirm='Are you sure|want to delete "+ data.no_label.toUpperCase() +" product label??' data-url='#' onClick='deleteInitLabel(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
        material +='</td>';
        material +='</tr>';

        $('#partLabelDetailBody').append(material);
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
                var no_label = $(e).parent().parent().find(".no_label").val()
                var product_id = $(e).parent().parent().find(".product_id").val()
                changeQtyItem(no_label, product_id);
                swal.close()
                $('#closeModal').click();
            });
        }
    }

    function changeQtyItem(no_label, product_id){
        $('#doItemTableList tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var product_id_row = $(this).find(".product_id").val();
            if(product_id === product_id_row){

                var label_list = $(this).find(".product_label_list").val();
                var dataLabel = label_list !== "" ? jQuery.parseJSON(label_list) : [];
                if(dataLabel.length >= 1){
                    var qty = 0;
                    var labelListNew = [];
                    dataLabel.forEach(function(item){
                        if(item.no_label !== no_label){
                            qty += 1;
                            labelListNew.push(item);
                        }
                    })

                    $(this).find(".qty").val(qty);
                    $(this).find(".product_label_list").val(JSON.stringify(labelListNew));
                }
            }
        });
    }
</script>
@endsection

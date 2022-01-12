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
                        <h5 class="m-b-10">ADDITIONAL PURCHASE ORDER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Additional Purchase Order</a></li>
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
                                    <h5>Additional Purchase Order</h5>
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
                                                    <th width="25%">No. PO</th>
                                                    <th width="25%">Supplier</th>
                                                    <th width="25%">Tanggal PO</th>
                                                    <th width="12%">Kebutuhan Bulan</th>
                                                    <th width="12%">Status</th>
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
                                    <form action="/process/additional-purchase-order/add" method="post" enctype="multipart/form-data" id="purchaseForm">
                                        @csrf
                                        <input type="hidden" name="po_id" id="po_id">
                                        <input type="hidden" name="Htotal" id="Htotal">
                                        <input type="hidden" name="Hppn" id="Hppn">
                                        <input type="hidden" name="Htotal_ppn" id="Htotal_ppn">
                                        <input type="hidden" name="status_process" id="status_process">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">No PO *</label>
                                            <div class="col-sm-5">
                                                <input type="text" name="po_number" id="po_number" class="form-control" placeholder="auto generate after save" readonly>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Supplier *</label>
                                            <div class="col-sm-10">
                                                <select name="supplier_id" id="supplier_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($supplierList as $ls)
                                                        <option value="{{ $ls->supplier_id }}">{{ $ls->business_entity.". ".$ls->supplier_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">PO Date *</label>
                                            <div class="col-sm-5">
                                                <input type="date" name="po_date" id="po_date" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Period *</label>
                                            <div class="col-sm-3">
                                                <select name="month_periode" id="month_periode" class="so_get js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach(getMonth() as $ls)
                                                        <option value="{{ $ls['month'] }}">{{ $ls['month_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <label class="col-form-label">Year</label>
                                            <div class="col-sm-3">
                                                <select name="year_periode" id="year_periode" class="so_get js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach(getYearPeriode() as $ls)
                                                        <option value="{{ $ls['year'] }}">{{ $ls['year'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-12 col-form-label"><b>Material</b></label>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <button type="button" class="btn btn-primary btn-sm btn-round waves-effect waves-light" onClick="addMaterial('')">Add Material</button>
                                            </div>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-striped table-bordered nowrap">
                                                <thead>
                                                    <tr>
                                                        <th>Material&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;</th>
                                                        <th>Qty</th>
                                                        <th>Stock</th>
                                                        <th>Standar Packing</th>
                                                        <th>Order</th>
                                                        <th>Price</th>
                                                        <th>Total</th>
                                                        <th>PPN (0,1)</th>
                                                        <th>Total + PPN</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyMaterial">
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <label class="col-sm-8"></label>
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" id="logProcess" class="btn btn-success waves-effect" style="display:none" data-toggle='modal' data-target='#log-Modal'>Log</button>
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <button type="button" class="save btn btn-info waves-effect waves-light" onClick="saveInit('#purchaseForm', 1)">Save Draft</button>
                                                <button type="button" class="save btn btn-primary waves-effect waves-light" onClick="saveInit('#purchaseForm', 2)">Process</button>
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
                <h4 class="modal-title" id="defaultModalLabel" >Sending Purchase Order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/process/purchase-order/send" method="post" enctype="multipart/form-data" id="sendForm">
                @csrf
                <input type="hidden" name="poID" id="poID">
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
<div class="modal fade" id="log-Modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Log Purchase Order</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="dt-responsive table-responsive">
                    <table id="logTable" class="table table-striped table-bordered nowrap" width="100%">
                        <thead>
                            <tr>
                                <th>Process</th>
                                <th>Noted</th>
                                <th>Date</th>
                                <th>User</th>
                            </tr>
                        </thead>

                    </table>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@include('layouts.footerIn')
<script>
    var dataPart = [];
    var itemPO = [];
    var prev_part = "";
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

    $('#supplier_id').change(function(e) {
        
        if($('#bodyMaterial tr').length == 0){
            changeSupplier(this);
        } else {
            var thiss = this;
            
            swal({
                title: 'Warning',
                text: 'if you change supplier, material list will be deleted!',
                type: "warning",
                showDenyButton: true,
                showCancelButton: true,
                confirmButtonClass: "btn-danger",
                confirmButtonText: "OK",
                cancelButtonText: "No",
                closeOnConfirm: true
            },
            function(isConfirm){
                
                if (isConfirm == true){
                    $("#bodyMaterial").html("");
                    changeSupplier(thiss);
                } else if (isConfirm == false){
                    $('#supplier_id').select2('destroy');
                    $(thiss).val(prev_part);
                    $('#supplier_id').select2();
                }
                
            });
            
        }
    
    });
    
    function changeSupplier(e){
        var arrPart = {!! json_encode($partList) !!};
        var material = arrPart.filter(a => a.supplier_id === $(e).val() || a.customer_id === $(e).val() );
        dataPart = [];

        if(material != ""){
            material.forEach(function(part) {
                if (part.part_customer_id !== undefined){
                    var part = {
                        id: part.part_customer_id,
                        text: part.part_name+"+Part Number : "+part.part_number+"+Divisi : "+part.divisi.divisi_name
                    };
                } else {
                    var part = {
                        id: part.part_supplier_id,
                        text: part.part_name+"+Part Number : "+part.part_number+"+Divisi : "+part.divisi.divisi_name
                    };
                }

                dataPart.push(part);
            });

        }
        
        prev_part = $(e).val();
    }

    function loadData(month_periode, year_periode){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "ajax": '/process/additional-purchase-order/load/'+month_periode+'/'+year_periode,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "po_number" },
                {  "mRender": function (data, type, row, num) {

                        return row.supplier.business_entity+" "+row.supplier.supplier_name;
                    }
                },
                { "data": "po_date" },
                {  "mRender": function (data, type, row, num) {
                        var arrMonth = {!! json_encode(getMonth()) !!};
                        var month = arrMonth.filter(e => e.month === row.month_periode);
                        return month[0].month_name+" "+row.year_periode;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var arrColor = {!! json_encode(getColor()) !!};
                        var status = "<span class='btn-"+ arrColor[row.status_process] +" btn-sm' > "+ row.status_po.status_process_name +" </span>";
                        return status;
                    }
                },
                {
                    "mData": "po_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        if(row.status_process < 5 ){
                            @if(isAccess('delete', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.po_number +" PO number??' data-url='/process/purchase-order/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                            @endif
                        }
                        if(row.status_process >= 5 ){
                            @if(isAccess('update', $MenuID))
                                button += "<button class='btn waves-effect waves-light btn-inverse btn-icon' onClick=printPurchaseOrder('"+ data +"')>&nbsp;<i class='icofont icofont-download'></i></button>";
                                button += "<button class='btn waves-effect waves-light btn-success btn-icon' onClick='send_value("+ JSON.stringify(row) +")' data-toggle='modal' data-target='#mail-Modal'>&nbsp;<i class='icofont icofont-email'></i></button>";
                            @endif
                        }
                        return button;
                    }
                }

            ]
        });
    }
    

    async function return_value(e, data){
        var btn = $(e).attr("btn");
        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;

        if (btn == "edit"){
            $("#purchaseForm").attr("action", "/process/additional-purchase-order/update");
            await getData('/process/additional-purchase-order/get/'+data.po_id).then(function(result){
                fillToForm(result);
                fillToLog(result.log_po);
                $("#logProcess").show();
            });
        } else {
            itemPO = [];
            $("#logProcess").hide();
            $("#purchaseForm").trigger("reset");
            $("#purchaseForm").attr("action", "/process/additional-purchase-order/add");
            $('.js-example-placeholder').select2('destroy');
            $('.js-example-placeholder').select2();
            $('#partTable tbody').html('');
        }

    }

    function fillToForm(data){
        $(".save").show();
        if(data.status_process > 3){
            $(".save").hide();
        }

        itemPO = data.po_items;
        $('.js-example-placeholder').select2('destroy');
        $("#po_id").val(data.po_id);
        $("#po_number").val(data.po_number);
        $("#supplier_id").val(data.supplier_id).select2();
        $("#po_date").val(data.po_date);
        $("#month_periode").val(data.month_periode);
        $("#year_periode").val(data.year_periode);
        $('.js-example-placeholder').select2();
        $("#supplier_id").trigger('change');
        $("#Htotal").val(data.price);
        $("#Hppn").val(data.ppn);
        $("#Htotal_ppn").val(data.total_price);
        itemPO.forEach(function(part) {
            addMaterial(part);
        });
        $('.part_id').each(function(i) {
            getDataPart(this);
            countTotal(this);
        });
    }

    function fillToLog(log){
        $('#logTable').DataTable().destroy();
        var table = $('#logTable').DataTable({
            "data": log,
            "aoColumns": [
                { "data": "log_status.status_process_name" },
                { "data": "comment" },
                {  "mRender": function (data, type, row, num) {

                        return row.created_at.substr(0, 10)+" "+row.created_at.substr(11, 8);
                    }
                },
                { "data": "created_user" }
            ]
        });
    }

    $('.closeForm').click(function(e) {
        $("#input").hide();
        $("#table").show();
    })

    function saveInit(form, status){
        if ($('select[name="part_id[]"]').length > 0){
            $("#status_process").val(status);
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

    function addMaterial(item){
        
        var part_id = item ? item.part_supplier_id : "";
        var qty = item ? item.qty : 0;
        var stock = item ? item.stock : "";
        var standard_packing = item ? item.part_supplier.standard_packing : "";

        if($("#supplier_id").val() == "") {
            swal('Error', 'supplier cannot be empty', 'error');
        } else {
            var material = '';    
            material += '<tr>';
            material +='<td>';
            material +=  '<select name="part_id[]" class="part_id select-style col-md-12" onChange="getDataPart(this)" required>';
            material +=      '<option value="">--Select--</option>';
                dataPart.forEach(function(item) {
                    var selected = item.id == part_id ? "selected" : "";
                    material +='<option value="'+ item.id +'" '+ selected +'>'+ item.text +'</option>';
                });
            material +=  '</select>';
            material +='</td>';
            material +='<td> <input type="number" name="qty[]" class="qty form-control tright" style="width:80px" value="'+ qty +'" onInput="countTotal(this)" required></td>';
            material +='<td> <input type="number" name="stock[]" class="stock form-control tright" style="width:80px" value="'+ stock +'" readonly></td>';
            material +='<td> <input type="number" name="standard_packing[]" class="standard_packing form-control tright" style="width:90px"  value="'+ standard_packing +'" readonly></td>';
            material +='<td> <input type="number" name="order[]" class="order form-control tright" style="width:80px" readonly></td>';
            material +='<td> <input type="number" name="price[]" class="price form-control tright" style="width:90px" readonly></td>';
            material +='<td> <input type="number" name="total[]" class="total form-control tright" style="width:100px" readonly></td>';
            material +='<td> <input type="number" name="ppn[]" class="ppn form-control tright" style="width:100px" readonly></td>';
            material +='<td> <input type="number" name="total_ppn[]" class="total_ppn form-control tright" style="width:100px" readonly></td>';
            material +='<td><button type="button" onClick="removeMaterial(this)" class="btn waves-effect waves-light btn-warning btn-icon">&nbsp;<i class="icofont icofont-trash"></i></button></td>'; 
            
            material +='</tr>';
            
            $('#bodyMaterial').append(material);
            loadSelect2();
            
            //$('#partTable_wrapper .row').first().find('div:first').remove();
            //$('#partTable_filter').css("text-align", "left");
            //countHeaderTotal();
        }
    }

    function removeMaterial(e){
        $(e).parent().parent().remove();
    }

    async function getDataPart(a){
        var price = 0;
        var partID = $(a).val();
        var tr = $(a).parent().parent();

        if(partID != ""){
            var today = getDateNow();
            var arrPartList = {!! json_encode($partList) !!};
            var material = arrPartList.filter(a => a.part_supplier_id === partID || a.part_customer_id === partID );
            if(material != ""){
                //$("#divisi").val(material[0].divisi.divisi_name);
                $("#unit").val(material[0].unit.unit_name);
                tr.find(".stock").val(material[0].stock);
                tr.find(".standard_packing").val(material[0].standard_packing);

                if (material[0].part_customer_id !== undefined){
                    var priceBom = await getData('/bill-material/getCostBOM/'+ material[0].part_customer_id);
                    var priceBop = await getData('/bill-process/getCostBOP/'+ material[0].part_customer_id);
                    price = priceBom + priceBop;

                } else {
                    var partPrice = material[0].part_price.filter(a => a.effective_date <= today);
                    partPrice = sortDescDateEffective(partPrice);
                    if(partPrice != "")
                    price = partPrice[0].price;
                }
            }
        }

        tr.find(".price").val(price);

    }

    function countTotal(e){
        
        var supplierList = {!! json_encode($supplierList) !!};
        var supplier = $("#supplier_id").val();
        var supplierData = supplierList.find(a => a.supplier_id === supplier);
        
        var tr = $(e).parent().parent();
        var qty = tr.find(".qty").val();
            qty = qty ? qty : 0;

        var stock = tr.find(".stock").val();
            stock = stock ? stock : 0;

        var packing = tr.find(".standard_packing").val();
            packing = packing ? packing : 0;

        var price = tr.find(".price").val();
            price = price ? price : 0;
        var order = qty;
        var total = 0;
        var ppn = 0;
        var total_ppn = 0;
        var addPacking = 0;

        if(qty != ""){

            //order = qty - stock;
            var sisa = qty % packing;
            order = sisa > 0 ? parseFloat(qty) + (parseFloat(packing) - parseFloat(sisa)) : parseFloat(qty);
            order = order < 0 ? 0 : order;
            total = parseFloat(price) * parseFloat(order);
            if(supplierData.is_ppn == 1)
                ppn = parseFloat(total) * 0.1;
            total_ppn = parseFloat(total) + parseFloat(ppn);
        }
        
        tr.find(".order").val(order);
        tr.find(".total").val(total);
        tr.find(".ppn").val(ppn);
        tr.find(".total_ppn").val(total_ppn);

        countHeaderTotal();
    }

    function countHeaderTotal(){
        var total = 0;
        var ppn = 0;
        var total_ppn = 0;
        $('input[name="total[]"]').each(function(index) {

            if($(this).val() != ""){
                total = total + parseFloat($(this).val());
                ppn = ppn + parseFloat($('input[name="ppn[]"]:eq('+ index +')').val());
                total_ppn = parseFloat(total_ppn + parseFloat($('input[name="total_ppn[]"]:eq('+ index +')').val()));
            }
        });

        $("#Htotal").val(total);
        $("#Hppn").val(ppn);
        $("#Htotal_ppn").val(total_ppn);
    }

    function printPurchaseOrder(poID){
        window.open('/process/purchase-order/print/'+poID, '_blank');
    }

    async function send_value(data){

        $('.mails').tagsinput('removeAll');
        $("#sendForm").trigger("reset");
        var ccMailList = await getData('/master/mail/load');
        ccMailList.forEach(function(mail) {
            $('#cc').tagsinput('add', mail.ccmail_email);
        });
        $("#poID").val(data.po_id);
        $('#to').tagsinput('add', data.supplier.supplier_email);
    }

    function sendInit(form){
        saveData(form, function() {
            periodeChangeFilter();
            $("#closeModal").click();
        });
    }
</script>
@endsection
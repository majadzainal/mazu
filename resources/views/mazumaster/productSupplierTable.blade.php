@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">PRODUCT SUPPLIER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Master</a></li>
                        <li class="breadcrumb-item"><a href="#!">Product Supplier</a></li>
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
                                    <h5>Product Supplier</h5>
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
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th width="11%">Action</th>
                                                    <th>Category</th>
                                                    <th>Product Code</th>
                                                    <th>Product Name</th>
                                                    <th>Description</th>
                                                    <th>Price</th>
                                                    <th>Unit</th>
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
                <h4 class="modal-title" id="defaultModalLabel" >Product Supplier</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <input type="hidden" name="product_supplier_id" id="product_supplier_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Category <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="product_category_id" id="product_category_id" onchange="changeProductCategory()" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($productCategoryList as $ls)
                                    <option value="{{ $ls->product_category_id }}">{{ $ls->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Product Supplier Code <span class="text-danger"> *</span></label>
                        <div class="col-sm-2">
                            <input type="text" readonly name="category_code" id="category_code" class="form-control">
                        </div>
                        <div class="col-sm-4">
                            <input type="text" name="product_code" id="product_code" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Product Supplier Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="product_name" id="product_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="product_description" id="product_description" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Stock</label>
                        <div class="col-sm-4">
                            <input type="number" step="1" value="0" name="stock" id="stock" readonly class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Unit <span class="text-danger"> *</span></label>
                        <div class="col-sm-4">
                            <select name="unit_id" id="unit_id" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($unitList as $ls)
                                    <option value="{{ $ls->unit_id }}">{{ $ls->unit_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Product Jasa</label>
                        <div class="col-sm-10">
                            <div class="checkbox-color checkbox-primary">
                                <input name="is_service"  id="is_service" type="hidden">
                                <input name="is_serviceCHK" id="is_serviceCHK" type="checkbox" >
                                <label for="is_serviceCHK">
                                    Jasa / Service
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Price <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <div class="input-group">
                                <span class="input-group-prepend" id="basic-addon2">
                                    <label class="input-group-text">Rp.</label>
                                </span>
                                <input type="text" name="price-form" id="price-form" class="form-control currency" placeholder="Price">
                                <input type="hidden" name="price" id="price" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Image</label>
                        <div class="col-sm-6">
                            <div class="input-group input-group-button">
                                <input type="hidden" name="images_before" id="images_before" class="form-control">
                                <input type="file" name="images" id="images" accept=".jpg, .jpeg, .png" class="form-control">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" onclick="loadImage()" type="button">Load Image</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label"></label>
                        <img id="img-input" class="col-sm-6" src="{{ asset ('/assets/files/assets/images/no-image.jpg') }}"/>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Stock </th>
                                    <th>Add Qty Stock <span class="text-danger"> *</span></th>
                                    <th width="50%">Warehouse <span class="text-danger"> *</span></th>
                                </tr>
                            </thead>
                            <tbody id="bodyStock">
                                <tr>
                                    <td><input type="number" step="1" name="stock_exist" value="0" id="stock_exist" readonly class="stock_exist form-control" required></td>
                                    <td><input type="number" step="1" name="stock_warehouse" id="stock_warehouse" value="0" class="stock form-control" required></td>
                                    <td>
                                        <select name="warehouse_id" id="warehouse_id" class="warehouse_id js-example-disabled col-sm-12" required>
                                            <option value="">--Select--</option>
                                            @foreach($warehouseList as $ls)
                                                <option value="{{ $ls->warehouse_id }}">{{ $ls->warehouse_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#uploadForm')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
        loadSelect2();

    } );

    function loadSelect2(){
        $(".js-example-placeholder").select2({
            placeholder: "--select--"
        });
    }

    function loadData(){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/master/product-supplier/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "product_supplier_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete product supplier "+ row.product_name +" ??' data-url='/master/product-supplier/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "category.category_name" },
                { "data": "product_code" },
                { "data": "product_name" },
                { "data": "product_description" },
                {  "mRender": function (data, type, row, num) {
                        var price = "Rp. " + row.price;
                        return price;
                    }
                },
                { "data": "unit.unit_name" },

            ]
        });
    }


    function return_value(e, data){
        var btn = $(e).attr("btn");

        if (btn == "edit"){
            $("#uploadForm").attr("action", "/master/product-supplier/update");
            $("#defaultModalLabel").text("Edit Product Supplier")
            $("#product_supplier_id").val(data.product_supplier_id);
            $("#product_category_id").val(data.product_category_id);
            $('#product_category_id').trigger('change');
            $("#product_name").val(data.product_name);
            $("#product_code").val(data.product_code);
            $("#product_description").val(data.product_description);
            $("#stock").val(data.stock);
            $("#unit_id").val(data.unit_id);
            $('#unit_id').trigger('change');
            $("#price-form").val(data.price);
            $("#is_serviceCHK").prop('checked', data.is_service === 1 ? true : false);
            $("#images_before").val(data.images);
            $("#stock_exist").val(data.stock_warehouse.stock);
            $("#warehouse_id").val(data.stock_warehouse.warehouse.warehouse_id);
            $('#warehouse_id').trigger('change');

            var srcImg = '{{ asset ("/assets/files/assets/images/no-image.jpg") }}';
            if(data.images){
                srcImg = '{{ asset ("/uploads") }}' + "/" + data.images;
            }
            $("#img-input").attr("src", srcImg);

            loadSelect2()

        } else {
            $("#uploadForm").trigger("reset");
            $("#uploadForm").attr("action", "/master/product-supplier/add");
            $("#defaultModalLabel").text("Add Product Supplier");
            $("#stock").val(0);
            var srcImg = '{{ asset ("/assets/files/assets/images/no-image.jpg") }}';
            $("#img-input").attr("src", srcImg);
            $(".js-example-disabled").prop("disabled", false);
            $('#warehouse_id').trigger('change');
            loadSelect2();
        }

    }

    function saveInit(form){
        var price = $("#price-form").val();
        $("#price").val(price.split(",").join(""));
        $(".js-example-disabled").prop("disabled", false);
        $("#is_service").val($("#is_serviceCHK").prop("checked") ? 1 : 0);
        saveDataUploadForm(form, function() {
            loadData();
            $("#closeModal").click();
            loadSelect2();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }

    function changeProductCategory(){
        var categoryArr = {!! json_encode($productCategoryList) !!};
        var product_category_id = $("#product_category_id").val();
        var product_category = categoryArr.find(e => e.product_category_id === parseInt(product_category_id));
        $("#category_code").val(product_category.category_code);
    }

    function loadImage(){
        const file = document.querySelector("#images").files[0];
        if(!file) return;

        const reader = new FileReader();

        reader.readAsDataURL(file);

        reader.onload = function(event){
            const imgElm = document.createElement("img");
            imgElm.src = event.target.result;
            document.querySelector("#img-input").src = event.target.result;
        }
    }

</script>
@endsection

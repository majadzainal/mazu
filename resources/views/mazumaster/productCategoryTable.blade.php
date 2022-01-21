@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">PRODUCT CATEGORY TABLE</h5>
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
                        <li class="breadcrumb-item"><a href="#!">Product Category</a>
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
                                    <h5>Product Category</h5>
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
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="card-block">
                                            @if(isAccess('create', $MenuID))
                                                <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#modal_default" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                            @endif
                                            <div class="dt-responsive table-responsive">
                                                <table id="searchTable" class="table table-striped table-bordered nowrap">
                                                    <thead>
                                                        <tr>
                                                            <th width="5%">No</th>
                                                            <th width="11%">Action</th>
                                                            <th>Category Code</th>
                                                            <th>Category Name</th>
                                                            <th>Description</th>
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
    </div>
</div>
<div id="styleSelector"></div>

<!--MODAL CUSTOMER STATUS-->
<div class="modal fade" id="modal_default"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="modal_default" >Add Product Category</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="productCategoryForm">
                @csrf
                <input type="hidden" name="product_category_id" id="product_category_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Product Category Code <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="category_code" id="category_code" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Product Category Name <span class="text-danger"> *</span> </label>
                        <div class="col-sm-8">
                            <input type="text" name="category_name" id="category_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Description</label>
                        <div class="col-sm-8">
                            <input type="text" name="category_description" id="category_description" class="form-control">
                        </div>
                    </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#productCategoryForm', '#closeModal')">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!---END MODAL CUSTOMER STATUS--->
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
    } );

    function loadData(){
        $('#searchTable').DataTable().destroy();
        $('#searchTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "bAutoWidth": false,
            "ajax": '/master/product-category/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "product_category_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#modal_default' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete product category "+ row.category_name +" ??' data-url='/master/product-category/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "category_code" },
                { "data": "category_name" },
                { "data": "category_description" },
            ]
        });
    }

    function return_value(e, data){

        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#productCategoryForm").attr("action", "/master/product-category/update");
            $("#defaultModalLabel").text("Edit Product Category")
            $("#product_category_id").val(data.product_category_id);
            $("#category_code").val(data.category_code);
            $("#category_code").prop('disabled', true);
            $("#category_name").val(data.category_name);
            $("#category_description").val(data.category_description);

        } else {
            $("#productCategoryForm").trigger("reset");
            $("#productCategoryForm").attr("action", "/master/product-category/add");
            $("#defaultModalLabel").text("Add Product Category");
            $("#category_code").prop('disabled', false);
        }
    }

    function saveInit(form, modalId){
        saveDataModal(form, modalId, function() {
            loadData();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }
</script>
@endsection

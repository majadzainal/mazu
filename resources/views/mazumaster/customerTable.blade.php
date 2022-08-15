@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">CUSTOMER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Master</a></li>
                        <li class="breadcrumb-item"><a href="#!">Customer</a></li>
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
                                    <h5>Customer</h5>
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
                                                    <th>Customer Name</th>
                                                    <th>Date Of Birth</th>
                                                    <th>Description</th>
                                                    <th>Address</th>
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
                <h4 class="modal-title" id="defaultModalLabel" >Product</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="customerForm">
                @csrf
                <input type="hidden" name="customer_id" id="customer_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Category <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <select name="customer_category_id" id="customer_category_id" class="js-example-placeholder col-sm-12" required>
                                <option value="">--Select--</option>
                                @foreach($custCategoryList as $ls)
                                    <option value="{{ $ls->customer_category_id }}">{{ $ls->cust_category_code." - ".$ls->cust_category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Customer Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="customer_name" id="customer_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Description</label>
                        <div class="col-sm-10">
                            <input type="text" name="description" id="description" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Address</label>
                        <div class="col-sm-10">
                            <input type="text" name="address" id="address" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input type="text" name="email" id="email" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Date Of Birth</label>
                        <div class="col-sm-4">
                            <input type="date" name="date_of_birth" id="date_of_birth" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#customerForm', '#closeModal')">Save</button>
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
            "ajax": '/master/customer/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "customer_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete customer "+ row.customer_name +" ??' data-url='/master/customer/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "category.cust_category_name" },
                { "data": "customer_name" },
                { "data": "date_of_birth" },
                { "data": "description" },
                { "data": "address" },
            ]
        });
    }

    function return_value(e, data){
        var btn = $(e).attr("btn");

        if (btn == "edit"){
            $("#customerForm").attr("action", "/master/customer/update");
            $("#defaultModalLabel").text("Edit Customer")
            $("#customer_id").val(data.customer_id);
            $("#customer_category_id").val(data.customer_category_id);
            $('#customer_category_id').trigger('change');
            $("#customer_name").val(data.customer_name);
            $("#date_of_birth").val(data.date_of_birth);
            $("#description").val(data.description);
            $("#address").val(data.address);
            $("#email").val(data.email);
            loadSelect2()

        } else {
            $("#customerForm").trigger("reset");
            $("#customerForm").attr("action", "/master/customer/add");
            $("#defaultModalLabel").text("Add Customer");
            $('#customer_category_id').trigger('change');
            loadSelect2();
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

@extends('layouts.headerIn')
@section('content')
<link rel="stylesheet" type="text/css" href="{{ asset ('/assets/files/bower_components/morris.js/css/morris.css') }}">
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">DASHBOARD</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Dashboard</a>
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
                        <div class="col-sm-12 col-md-8 col-lg-8">
                            <div class="row">
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col-8">
                                                    <h4 class="text-c-purple">Rp. {{ number_format($totalSalesOrder ? $totalSalesOrder : 0) }}</h4>
                                                    <h6 class="text-muted m-b-0">Sales Order This Month</h6>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <i class="fa fa-bar-chart f-28"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-c-purple">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <a href="/process/sales-order/table" ><p class="text-white m-b-0">Go To Detail Sales</p></a>
                                                </div>
                                                <div class="col-3 text-right">
                                                    <i class="fa fa-line-chart text-white f-16"></i>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-md-6 col-sm-12">
                                    <div class="card">
                                        <div class="card-block">
                                            <div class="row align-items-center">
                                                <div class="col-8">
                                                    <h4 class="text-c-red">Rp. {{ number_format($decRemain ? $decRemain : 0) }}</h4>
                                                    <h6 class="text-muted m-b-0">Outstanding Payment</h6>
                                                </div>
                                                <div class="col-4 text-right">
                                                    <i class="fa fa-bell-o f-28"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer bg-c-red">
                                            <div class="row align-items-center">
                                                <div class="col-9">
                                                    <a href="/process/sales-order/table" ><p class="text-white m-b-0">Go To Payment</p></a>
                                                </div>
                                                <div class="col-3 text-right">
                                                    <i class="fa fa-bullhorn text-white f-16"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Incoming Birtdays</h5>
                                </div>
                                <div class="card-block">
                                    @php
                                        $is_first = true;
                                    @endphp
                                    @foreach($birthdaysList as $ls)

                                        <div class="row m-b-5">
                                            <div class="col-auto p-r-0">
                                                @if ($is_first)
                                                <button type="button" class='btn btn-sm waves-effect waves-light btn-danger btn-icon'>&nbsp;<i class='fa fa-bell'></i></button>
                                                @else
                                                <button type="button" class='btn btn-sm waves-effect waves-light btn-warning btn-icon'>&nbsp;<i class='fa fa-bell'></i></button>
                                                @endif
                                            </div>
                                            <div class="col p-t-8">
                                                <p><strong>{{ $ls->customer_name }} </strong> - {{ date('d-m-Y', strtotime($ls->date_of_birth))}}
                                                    <button type="button" class="btn btn-info btn-sm p-1" data-toggle="modal" data-target="#modal_default" onClick="sendEmailBirthday({{$ls}})" btn="edit">send email</button>
                                                </p>
                                            </div>
                                        </div>

                                    @php
                                        $is_first = false;
                                    @endphp
                                    @endforeach

                                </div>

                            </div>
                        </div>
                        <!-- task, page, download counter  start -->
                        <div class="col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Sales Order</h5>
                                </div>
                                <div class="card-block">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Start Date <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="date" name="start_date_so" id="start_date_so" class="form-control" required>
                                        </div>
                                        <label class="col-sm-2 col-form-label" stye="text-align:center;">End Date<span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="date" name="end_date_so" id="end_date_so" class="form-control" required>
                                        </div>
                                        <button class="col-sm-2 btn btn-primary btn-sm btn-round waves-effect waves-light" onClick="btnLoadDataSalesTotalClick()">Load Data</button>

                                    </div>
                                    <div id="sales_order_total"></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Product Sales Order</h5>
                                </div>
                                <div class="card-block">
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Start Date <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="date" name="start_date" id="start_date" class="form-control" required>
                                        </div>
                                        <label class="col-sm-2 col-form-label" stye="text-align:center;">End Date<span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="date" name="end_date" id="end_date" class="form-control" required>
                                        </div>
                                        <button class="col-sm-2 btn btn-primary btn-sm btn-round waves-effect waves-light" onClick="btnLoadDataSalesClick()">Load Data</button>

                                    </div>
                                    <div id="sales_order"></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Sales Order By Product Category</h5>
                                </div>
                                <div class="card-block">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Product Category <span class="text-danger"> *</span></label>
                                        <div class="col-sm-6">
                                            <select name="product_category_id" id="product_category_id" class="js-example-placeholder col-sm-12" required>
                                                @foreach($productCategoryList as $ls)
                                                    <option value="{{ $ls->product_category_id }}">{{ $ls->category_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Start Date <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="date" name="start_date_category" id="start_date_category" class="form-control" required>
                                        </div>
                                        <label class="col-sm-2 col-form-label" stye="text-align:center;">End Date<span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="date" name="end_date_category" id="end_date_category" class="form-control" required>
                                        </div>
                                        <button class="col-sm-2 btn btn-primary btn-sm btn-round waves-effect waves-light" onClick="btnLoadDataSalesByCategoryClick()">Load Data</button>

                                    </div>
                                    <div id="sales_order_by_category"></div>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Sales Order By Product</h5>
                                </div>
                                <div class="card-block">
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Product <span class="text-danger"> *</span></label>
                                        <div class="col-sm-6">
                                            <select name="product_id" id="product_id" class="js-example-placeholder col-sm-12" required>
                                                @foreach($productList as $ls)
                                                    <option value="{{ $ls->product_id }}">{{ $ls->product_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Start Date <span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="date" name="start_date_product" id="start_date_product" class="form-control" required>
                                        </div>
                                        <label class="col-sm-2 col-form-label" stye="text-align:center;">End Date<span class="text-danger"> *</span></label>
                                        <div class="col-sm-3">
                                            <input type="date" name="end_date_product" id="end_date_product" class="form-control" required>
                                        </div>
                                        <button class="col-sm-2 btn btn-primary btn-sm btn-round waves-effect waves-light" onClick="btnLoadDataSalesByProductClick()">Load Data</button>

                                    </div>
                                    <div id="sales_order_by_product"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--MODAL CUSTOMER STATUS-->
<div class="modal fade" id="modal_default"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="modal_default" >Send Email</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/dashboard/send-email-birthday" method="post" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <input type="hidden" name="broadcast_email_id" id="broadcast_email_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">To <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="email" name="email_birthday" id="email_birthday" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Subject <span class="text-danger"> *</span></label>
                        <div class="col-sm-6">
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Header</label>
                        <div class="col-sm-10">
                            <input type="text" name="header_text" id="header_text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Opening Text</label>
                        <div class="col-sm-10">
                            <input type="text" name="opening_text" id="opening_text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Content Text</label>
                        <div class="col-sm-10">
                            <textarea name="content_text" id="content_text" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Regards Text</label>
                        <div class="col-sm-10">
                            <input type="text" name="regards_text" id="regards_text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Regards Value</label>
                        <div class="col-sm-10">
                            <input type="text" name="regards_value_text" id="regards_value_text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Footer Text</label>
                        <div class="col-sm-10">
                            <input type="text" name="footer_text" id="footer_text" class="form-control">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Banner_Image</label>
                        <div class="col-sm-6">
                            <div class="input-group input-group-button">
                                <input type="hidden" name="banner_before" id="banner_before" class="form-control">
                                <input type="file" name="banner" id="banner" accept=".jpg, .jpeg, .png" class="form-control">
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
                </div>

                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" onclick="closeInit()"data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#uploadForm')">Save And Send Email</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!---END MODAL CUSTOMER STATUS--->
@include('layouts.footerIn')
<script src="{{ asset ('/assets/files/bower_components/raphael/js/raphael.min.js') }}"></script>
<script src="{{ asset ('/assets/files/bower_components/morris.js/js/morris.js') }}"></script>
<script>
    $(document).ready(function() {
        loadData();
        var date = new Date();
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        $("#start_date_so").val(moment(firstDay).format('YYYY-MM-DD'));
        $("#end_date_so").val(moment(lastDay).format('YYYY-MM-DD'));
        $("#start_date").val(moment(firstDay).format('YYYY-MM-DD'));
        $("#end_date").val(moment(lastDay).format('YYYY-MM-DD'));
        $("#start_date_category").val(moment(firstDay).format('YYYY-MM-DD'));
        $("#end_date_category").val(moment(lastDay).format('YYYY-MM-DD'));
        $("#start_date_product").val(moment(firstDay).format('YYYY-MM-DD'));
        $("#end_date_product").val(moment(lastDay).format('YYYY-MM-DD'));

        btnLoadDataSalesTotalClick()
        btnLoadDataSalesClick();
        btnLoadDataSalesByCategoryClick();
        btnLoadDataSalesByProductClick();
    } );

    async function sendEmailBirthday(data){
        $("#uploadForm").trigger("reset");
        $("#email_birthday").val(data.email);
        await getData('/dashboard/get-email-birthday').then(function(result){
            justFillToFormEmail(result);
        });
    }

    function justFillToFormEmail(data){

        $("#uploadForm").attr("action", "/dashboard/send-email-birthday");
        if(data !== null){
            $("#broadcast_email_id").val(data.broadcast_email_id);
            $("#subject").val(data.subject);
            $("#header_text").val(data.header_text);
            $("#opening_text").val(data.opening_text);
            $("#content_text").val(data.content_text);
            $("#regards_text").val(data.regards_text);
            $("#regards_value_text").val(data.regards_value_text);
            $("#footer_text").val(data.footer_text);
            $("#banner_before").val(data.banner);

            var srcImg = '{{ asset ("/uploads") }}' + "/" + data.banner;
            $("#img-input").attr("src", srcImg);
        }
    }
    function saveInit(form){
        saveDataUploadForm(form, function() {
            $("#closeModal").click();
        });
    }

    function loadData(){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "ajax": '/dashboard/load-part-supplier',
            "rowsGroup": [1],
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "supplier.supplier_name" },
                { "data": "divisi.divisi_name" },
                { "data": "part_name" },
                { "data": "minimum_stock" },
                { "data": "stock" },
                {  "mRender": function (data, type, row, num) {
                        var deficit = row.minimum_stock - row.stock;
                        return deficit;
                    }
                }
            ]
        });
    }

    function btnLoadDataSalesTotalClick(){
        getSalesOrderTotal();
    }
    function btnLoadDataSalesClick(){
        getSalesOrder();
    }
    function btnLoadDataSalesByCategoryClick(){
        getSalesOrderByCategory();
    }
    function btnLoadDataSalesByProductClick(){
        getSalesOrderByProduct();
    }
    async function getSalesOrderTotal() {
        var startDate = $("#start_date_so").val();
        var endDate = $("#end_date_so").val();
        if(start_date !== "" && endDate !== ""){
            await getData('/dashboard/sales-order/load/'+startDate+'/'+endDate).then(function(result){
                if (result){
                    mappingDataSoTotal(result);
                } else {
                    swal('Info','data not found', 'error');
                }
            });
        }
    }

    async function getSalesOrder() {
        var startDate = $("#start_date").val();
        var endDate = $("#end_date").val();
        if(start_date !== "" && endDate !== ""){
            await getData('/dashboard/sales-order-product/load/'+startDate+'/'+endDate).then(function(result){
                if (result){
                    mappingData(result);
                } else {
                    swal('Info','data not found', 'error');
                }
            });
        }
    }

    async function getSalesOrderByCategory() {
        var category_id = "none";
        category_id = $("#product_category_id").val() !== "" ? $("#product_category_id").val() : "none";
        var startDate = $("#start_date_category").val();
        var endDate = $("#end_date_category").val();
        if(start_date !== "" && endDate !== ""){
            await getData('/dashboard/sales-order-product-category/load/'+startDate+'/'+endDate+'/'+category_id).then(function(result){
                if (result){
                    mappingDataByCategory(result);
                } else {
                    swal('Info','data not found', 'error');
                }
            });
        }
    }
    async function getSalesOrderByProduct() {
        var product_id = $("#product_id").val() !== "" ? $("#product_id").val() : "none";
        var startDate = $("#start_date_product").val();
        var endDate = $("#end_date_product").val();
        if(start_date !== "" && endDate !== ""){
            await getData('/dashboard/sales-order-by-product/load/'+startDate+'/'+endDate+'/'+product_id).then(function(result){
                if (result){
                    mappingDataByProduct(result);
                } else {
                    swal('Info','data not found', 'error');
                }
            });
        }
    }

    function mappingDataSoTotal(list){
        var productSales = [];
        var product = [];
        list.forEach(function(item) {
            var index = productSales.findIndex(a => a['date'] == item.date);
            if (index == -1){
                var data = {};
                data['date'] = item.date;
                data[item.name] = item.qty;

                productSales.push(data);

            } else {
                productSales[index][item.name] = item.qty;
            }

            if(product.includes(item.name) == false){
                product.push(item.name);
            }
        });

        $("#sales_order_total").empty();
        $("#sales_order_total svg").remove();
        lineChartSalesTotal(productSales, product);
    }
    function mappingData(list){
        var productSales = [];
        var product = [];
        list.forEach(function(item) {
            var index = productSales.findIndex(a => a['date'] == item.date);
            if (index == -1){
                var data = {};
                data['date'] = item.date;
                data[item.name] = item.qty;

                productSales.push(data);

            } else {
                productSales[index][item.name] = item.qty;
            }

            if(product.includes(item.name) == false){
                product.push(item.name);
            }
        });

        $("#sales_order").empty();
        $("#sales_order svg").remove();
        lineChartSales(productSales, product);
    }

    function mappingDataByCategory(list){
        var productSales = [];
        var product = [];
        list.forEach(function(item) {
            var index = productSales.findIndex(a => a['date'] == item.date);
            if (index == -1){
                var data = {};
                data['date'] = item.date;
                data[item.name] = item.qty;

                productSales.push(data);

            } else {
                productSales[index][item.name] = item.qty;
            }

            if(product.includes(item.name) == false){
                product.push(item.name);
            }
        });

        $("#sales_order_by_category").empty();
        $("#sales_order_by_category svg").remove();
        lineChartSalesByCategory(productSales, product);
    }
    function mappingDataByProduct(list){
        var productSales = [];
        var product = [];
        list.forEach(function(item) {
            var index = productSales.findIndex(a => a['date'] == item.date);
            if (index == -1){
                var data = {};
                data['date'] = item.date;
                data[item.name] = item.qty;

                productSales.push(data);

            } else {
                productSales[index][item.name] = item.qty;
            }

            if(product.includes(item.name) == false){
                product.push(item.name);
            }
        });

        $("#sales_order_by_product").empty();
        $("#sales_order_by_product svg").remove();
        lineChartSalesByProduct(productSales, product);
    }

    function lineChartSalesTotal(productSales, product) {
        window.lineChart = Morris.Line({
            element: 'sales_order_total',
            data: productSales,
            xkey: 'date',
            ykeys: product,
            labels: product,
            lineColors: ['#B4C1D7', '#FF9F55', '#6e4e4c', '#34d5eb', '#7d8f91', '#9cb32d', '#4f2c73', '#4f2c73', '#e6d7ca', '#de6c09', '#dede09', '#212117', '#9a989c', '#8f608a'],
            resize: true,
            parseTime: false
        });

    }

    function lineChartSales(productSales, product) {
        window.lineChart = Morris.Line({
            element: 'sales_order',
            data: productSales,
            xkey: 'date',
            ykeys: product,
            labels: product,
            lineColors: ['#B4C1D7', '#FF9F55', '#6e4e4c', '#34d5eb', '#7d8f91', '#9cb32d', '#4f2c73', '#4f2c73', '#e6d7ca', '#de6c09', '#dede09', '#212117', '#9a989c', '#8f608a'],
            resize: true,
            parseTime: false
        });

    }
    function lineChartSalesByCategory(productSales, product) {
        window.lineChart = Morris.Line({
            element: 'sales_order_by_category',
            data: productSales,
            xkey: 'date',
            ykeys: product,
            labels: product,
            lineColors: ['#B4C1D7', '#FF9F55', '#6e4e4c', '#34d5eb', '#7d8f91', '#9cb32d', '#4f2c73', '#4f2c73', '#e6d7ca', '#de6c09', '#dede09', '#212117', '#9a989c', '#8f608a'],
            resize: true,
            parseTime: false
        });

    }
    function lineChartSalesByProduct(productSales, product) {
        window.lineChart = Morris.Line({
            element: 'sales_order_by_product',
            data: productSales,
            xkey: 'date',
            ykeys: product,
            labels: product,
            lineColors: ['#B4C1D7', '#FF9F55', '#6e4e4c', '#34d5eb', '#7d8f91', '#9cb32d', '#4f2c73', '#4f2c73', '#e6d7ca', '#de6c09', '#dede09', '#212117', '#9a989c', '#8f608a'],
            resize: true,
            parseTime: false
        });

    }

    function loadImage(){
        const file = document.querySelector("#banner").files[0];
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

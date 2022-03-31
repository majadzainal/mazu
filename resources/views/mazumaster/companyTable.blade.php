@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">COMPANY TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Master</a></li>
                        <li class="breadcrumb-item"><a href="#!">Company</a></li>
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
                        <div class="col-sm-12" id="input">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Company</h5>
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
                                    <form action="/master/company/update" method="post" enctype="multipart/form-data" id="uploadForm">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Instagram <span class="text-danger"> </span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="instagram" id="instagram" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Facebook <span class="text-danger"> </span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="facebook" id="facebook" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Youtube <span class="text-danger"> </span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="youtube" id="youtube" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Email <span class="text-danger"> </span></label>
                                            <div class="col-sm-10">
                                                <input type="email" name="email_company" id="email_company" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Whatsapp <span class="text-danger"> </span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="whatsapp" id="whatsapp" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Website <span class="text-danger"> </span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="website" id="website" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Address <span class="text-danger"> </span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="address" id="address" class="form-control" >
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Logo</label>
                                            <div class="col-sm-6">
                                                <div class="input-group input-group-button">
                                                    <input type="hidden" name="logo_before" id="logo_before" class="form-control">
                                                    <input type="file" name="logo" id="logo" accept=".jpg, .jpeg, .png" onchange="loadImage()" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <img id="img-input" class="col-sm-6" src="{{ asset ('/assets/files/assets/images/no-image.jpg') }}"/>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Banner Invoice</label>
                                            <div class="col-sm-6">
                                                <div class="input-group input-group-button">
                                                    <input type="hidden" name="banner_invoice_before" id="banner_invoice_before" class="form-control">
                                                    <input type="file" name="banner_invoice" id="banner_invoice" accept=".jpg, .jpeg, .png" onchange="loadImageBanner()" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <img id="img-input_banner" class="col-sm-6" src="{{ asset ('/assets/files/assets/images/no-image.jpg') }}"/>
                                        </div>
                                        <div class="form-group row text-left">
                                            <label class="col-sm-2 col-form-label"></label>
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#uploadForm')">Update</button>
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
@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
    });

    async function loadData(){
        await getData('/master/company/load').then(function(result){
            fillToForm(result);
        });
    }

    function fillToForm(data){
        console.log(data);
        if(data !== null){
            $("#instagram").val(data.instagram);
            $("#facebook").val(data.facebook);
            $("#youtube").val(data.youtube);
            $("#email_company").val(data.email);
            $("#whatsapp").val(data.whatsapp);
            $("#website").val(data.website);
            $("#address").val(data.address);
            $("#logo_before").val(data.logo);
            $("#banner_invoice_before").val(data.banner_invoice);

            var logo = '{{ asset ("/uploads") }}' + "/" + data.logo;
            $("#img-input").attr("src", logo);
            var banner_invoice = '{{ asset ("/uploads") }}' + "/" + data.banner_invoice;
            $("#img-input_banner").attr("src", banner_invoice);
        }
    }

    function saveInit(form){
        saveDataUploadForm(form, function() {
            loadData();
        });
    }

    function loadImage(){
        const file = document.querySelector("#logo").files[0];
        if(!file) return;

        const reader = new FileReader();

        reader.readAsDataURL(file);

        reader.onload = function(event){
            const imgElm = document.createElement("img");
            imgElm.src = event.target.result;
            document.querySelector("#img-input").src = event.target.result;
        }
    }

    function loadImageBanner(){
        const file = document.querySelector("#banner_invoice").files[0];
        if(!file) return;

        const reader = new FileReader();

        reader.readAsDataURL(file);

        reader.onload = function(event){
            const imgElm = document.createElement("img");
            imgElm.src = event.target.result;
            document.querySelector("#img-input_banner").src = event.target.result;
        }
    }

</script>
@endsection

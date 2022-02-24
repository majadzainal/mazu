@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">BROADCAST EMAIL TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Master</a></li>
                        <li class="breadcrumb-item"><a href="#!">Broadcast email</a></li>
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
                                    <h5>Product</h5>
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
                                    <div class="dt-responsive table-responsive">
                                        <table id="newTables" class="table table-striped table-bordered nowrap">
                                            <thead>
                                                <tr>
                                                    <th width="2%">No.</th>
                                                    <th width="11%">Action</th>
                                                    <th>Subject</th>
                                                    <th>Content Text</th>
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
                                    <h5>Broadcast Email</h5>
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
                                    <form action="/master/broadcast-email/add" method="post" enctype="multipart/form-data" id="uploadForm">
                                        @csrf
                                        <input type="hidden" name="broadcast_email_id" id="broadcast_email_id">
                                        <div class="modal-body">
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
                                            <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#uploadForm')">Save</button>
                                            <button type="button" class="btn btn-danger waves-effect waves-light" onclick="brodcastClick()" data-toggle='modal' data-target='#mail-Modal'>Broadcast Email</button>
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
                <h4 class="modal-title" id="defaultModalLabel" >Sending Broadcast</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="/master/broadcast-email/do-broadcast" method="post" enctype="multipart/form-data" id="sendForm">
                @csrf
                <input type="hidden" name="broadcast_email_id_send" id="broadcast_email_id_send">
                <input type="hidden" name="email_list" id="email_list">
                <div class="modal-body">
                    <div class="col-sm-6" style="text-align:left;">
                        <button type="button" class="btn btn-success btn-sm btn-round waves-effect waves-light" id="check-all" btn="cek_all" onClick="checkAll(this)"></i> Select All</button>
                    </div>
                    <table id="bcTables" class="table table-striped table-bordered nowrap">
                        <thead>
                            <tr>
                                <th width="2%">No.</th>
                                <th width="11%">Mark</th>
                                <th>Name</th>
                                <th>Email</th>
                            </tr>
                        </thead>

                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalEmail" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="sendInit('#sendForm')">Send</button>
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
        loadData();
        $("#input").hide();

    } );

    function loadData(){
        $('#newTables').DataTable().destroy();
        var table = $('#newTables').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/master/broadcast-email/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "broadcast_email_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete product "+ row.subject +" ??' data-url='/master/product/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                { "data": "subject" },
                { "data": "content_text" },
            ]
        });
    }

    function loadDataBc(){
        $('#bcTables').DataTable().destroy();
        var table = $('#bcTables').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": true,
            "ajax": '/master/broadcast-email/load-customer',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        console.log(row);
                        return num.row+1;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return '<input type="checkbox" class="checked_print">';
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var name = "";
                        name = row.name ? row.name : name;
                        return name;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var email = "";
                        email += row.email ? row.email : email;
                        email += '<input type="hidden" value="'+row.email+'" class="email_checked"/>';
                        return email;
                    }
                },
            ]
        });
    }

    function return_value(e, data){
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#uploadForm").attr("action", "/master/broadcast-email/update");
            $("#broadcast_email_id").val(data.broadcast_email_id);
            $("#subject").val(data.subject);
            $("#header_text").val(data.header_text);
            $("#opening_text").val(data.opening_text);
            $("#content_text").val(data.content_text);
            $("#regards_text").val(data.regards_text);
            $("#regards_value_text").val(data.regards_value_text);
            $("#footer_text").val(data.footer_text);
            $("#banner_before").val(data.banner);
            $(".js-example-disabled").prop("disabled", true);

            var srcImg = '{{ asset ("/uploads") }}' + "/" + data.banner;
            $("#img-input").attr("src", srcImg);

            loadSelect2()

        } else {
            $("#uploadForm").trigger("reset");
            $("#uploadForm").attr("action", "/master/broadcast-email/add");
            $("#defaultModalLabel").text("Add Broadcast Email");
            var srcImg = '{{ asset ("/assets/files/assets/images/no-image.jpg") }}';
            $("#img-input").attr("src", srcImg);
            $(".js-example-disabled").prop("disabled", false);
            loadSelect2();
        }

        $("#input").show();
        $("#table").hide();
        document.documentElement.scrollTop = 0;

    }

    function brodcastClick(){
        var broadcast_email_id = $("#broadcast_email_id").val();
        $("#broadcast_email_id_send").val(broadcast_email_id);

        loadDataBc();
    }

    function checkAll(e){
        var btn = $(e).attr("btn");
        if (btn == "cek_all"){
            $('#bcTables tr').each(function() {
                if (!this.rowIndex) return; // skip first row
                $(this).find(".checked_print").prop('checked', true);
            });

            $(e).attr("btn", "uncek_all");
            $(e).text("Unselect All Label");
        }else if (btn == "uncek_all"){
            $('#bcTables tr').each(function() {
                if (!this.rowIndex) return; // skip first row
                $(this).find(".checked_print").prop('checked', false);
            });

            $(e).attr("btn", "cek_all");
            $(e).text("Select All Label");
        }
    }

    function loadSelect2(){
        $(".js-example-placeholder").select2({
            placeholder: "--select--"
        });
    }

    function saveInit(form){
        saveDataUploadForm(form, function() {
            loadData();
            $("#input").hide();
            $("#table").show();
        });
    }
    function sendInit(form){
        var emailList = getEmailIsChecked();
        $("#email_list").val(JSON.stringify(emailList));
        saveData(form, function() {
            loadData();
            $("#closeModalEmail").click();
            $("#input").hide();
            $("#table").show();
        });
    }

    function getEmailIsChecked(){
        $("#bcTables").DataTable().search("").draw()
        var itemList = [];
        $('#bcTables tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var checked_print = $(this).find(".checked_print").prop("checked") ? true : false;
            var email = $(this).find(".email_checked").val();
            if(checked_print){
                data = {
                    email: email,
                };
                itemList.push(data);
            }

        });

        return itemList;
    }

    function closeInit(){
        $("#input").hide();
        $("#table").show();
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

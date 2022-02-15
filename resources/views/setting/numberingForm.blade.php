@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">NUMBERING FORM & COUNTER</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Setting</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Numbering Form & Counter</a>
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
                        <div class="col-sm-8">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Numbering Form</h5>
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
                                <div class="tab-content card-block">
                                    <form action="/setting/numbering-form-counter/update" method="post" enctype="multipart/form-data" id="numberingForm">
                                        @csrf
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Numbering Format</label>
                                            <div class="col-sm-8">
                                                <label class="col-form-label"><strong>[text] [year] [month] [day] [counter]</strong></label>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Numbering Type</label>
                                            <div class="col-sm-8">
                                                <select onchange="numberingFormChanged(this)" name="numbering_form_type" id="numbering_form_type" class="js-example-placeholder col-sm-12">
                                                    <option value="">--Select--</option>
                                                    @foreach(getNumberingFormType() as $ls)
                                                        <option value="{{ $ls['numbering_form_type'] }}">{{ $ls['numbering_form_type'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Numbering Name</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="numbering_form_name" id="numbering_form_name" class="form-control" />
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Text Field</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="string_val" id="string_val" class="form-control  m-b-5" />
                                                <div class="checkbox-color checkbox-primary">
                                                    <input type="hidden" name="string_used" id="string_used">
                                                    <input name="string_used_chk" id="string_used_chk" type="checkbox" checked="">
                                                    <label for="string_used_chk">
                                                        Use text field
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Year Field</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly name="year_input_val" id="year_input_val" value="12" class="form-control m-b-5" />
                                                <div class="form-radio">
                                                    <div class="radio radiofill radio-inline">
                                                        <label>
                                                            <input onchange="yearchanged()" type="radio" name="year_val" id="year_val_2" value="2" checked="checked">
                                                            <i class="helper"></i>2 Digit
                                                        </label>
                                                    </div>
                                                    <div class="radio radiofill radio-inline">
                                                        <label>
                                                            <input onchange="yearchanged()" type="radio" name="year_val" id="year_val_4" value="4">
                                                            <i class="helper"></i>4 Digit
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="checkbox-color checkbox-primary">
                                                    <input type="hidden" name="year_used" id="year_used">
                                                    <input name="year_used_chk" id="year_used_chk" type="checkbox" checked="">
                                                        <label for="year_used_chk">
                                                            Use year field
                                                        </label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Month Field</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly name="month_val" id="month_val" value="12" class="form-control m-b-5" />
                                                <div class="checkbox-color checkbox-primary">
                                                    <input type="hidden" name="month_used" id="month_used">
                                                    <input name="month_used_chk" id="month_used_chk" type="checkbox" checked="">
                                                        <label for="month_used_chk">
                                                            Use month field
                                                        </label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Day Field</label>
                                            <div class="col-sm-8">
                                                <input type="text" readonly name="day_val" id="day_val" value="30" class="form-control m-b-5" />
                                                <div class="checkbox-color checkbox-primary">
                                                    <input type="hidden" name="day_used" id="day_used">
                                                    <input name="day_used_chk" id="day_used_chk" type="checkbox" checked="">
                                                        <label for="day_used_chk">
                                                            Use day field
                                                        </label>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Counter</label>
                                            <div class="col-sm-8">
                                                <select onchange="counterChanged(this)" name="counter_id_form" id="counter_id_form" class="js-example-placeholder col-sm-12 m-b-5">
                                                    <option value="">--Select--</option>
                                                    @foreach($counterList as $ls)
                                                        <option value="{{ $ls['counter_id'] }}">{{ $ls['counter_name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <label class="col-sm-12 col-form-label">Counter</label>
                                                        <input type="text" readonly name="counter_form" id="counter_form"  class="form-control col-sm-12" />
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <label class="col-sm-12 col-form-label">Length</label>
                                                        <input type="text" readonly name="counter_length_form" id="counter_length_form"  class="form-control col-sm-12" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="form-group row">
                                            <label class="col-sm-6"></label>
                                            <div class="col-sm-6" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm" id="closeForm" onclick="resetForm()">Cancel</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInitNumberingForm('#numberingForm', '#closeForm')">Save</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div><!--END COL-SM-6-->

                        <div class="col-sm-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Counter</h5>
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
                                <div class="tab-content card-block">
                                    @if(isAccess('create', $MenuID))
                                    <button class="btn btn-primary btn-sm btn-round waves-effect waves-light" data-toggle="modal" data-target="#large-Modal" onClick="return_value(this, '')"><i class="icofont icofont-plus-circle" btn="add"></i> Add</button>
                                    @endif
                                    <div class="table-responsive">
                                        <table id="newTablesCounter" class="table table-striped table-bordered nowrap dt-responsive width-100">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Counter</th>
                                                    <th>Length</th>
                                                    <th width="11%">Action</th>
                                                </tr>
                                            </thead>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div><!--END COL-SM-6-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="styleSelector"></div>
<!---MODAL COUNTER-->
<div class="modal fade" id="large-Modal"  role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:lightblue">
                <h4 class="modal-title" id="defaultModalLabel" >Add Counter</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data" id="formCounter">
                @csrf
                <input type="hidden" name="counter_id" id="counter_id">
                <div class="modal-body">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Counter Name <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="text" name="counter_name" id="counter_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Length <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="number" name="length" id="length" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Starting Counter <span class="text-danger"> *</span></label>
                        <div class="col-sm-10">
                            <input type="number" name="counter" id="counter" class="form-control" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModal" class="btn btn-default waves-effect" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#formCounter', '#closeModal')">Save</button>
                </div>
            </form>

        </div>
    </div>
</div>
<!---END MODAL COUNTER-->


@include('layouts.footerIn')
<script>
    $(document).ready(function() {
        loadData();
        resetForm();
    } );

    function loadData(){
        $('#newTablesCounter').DataTable().destroy();
        var table = $('#newTablesCounter').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": true,
            "ajax": '/setting/counter/load',
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                { "data": "counter_name" },
                { "data": "counter" },
                { "data": "length" },
                {
                    "mData": "counter_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' data-toggle='modal' data-target='#large-Modal' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                        @endif
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete counter "+ row.counter_name +" ?' data-url='/setting/counter/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },

            ],
            "columnDefs": [
                { className: "txt-primary", "targets": [ 0 ] }
            ]
        });
    }

    async function numberingFormChanged(e){
        if(e.value !== ""){
            // await getData('/setting/numbering-form-counter/get/'+e.value).then(function(result){
            //     fillDataToForm(result);
            // });
            console.log(e.value);
            await getData('/setting/numbering-form-counter/get-by-type/'+e.value).then(function(result){
                fillDataToForm(result);
            });
        }
        // var dataList = {!! json_encode($numFormList->toArray()) !!};
        // let data = dataList.find(x => x.numbering_form_id == e.value);
        // fillDataToForm(data);
    }

    function fillDataToForm(data){
        if(data){
            $("#numbering_form_name").val(data.numbering_form_name);
            $("#string_val").val(data.string_val);
            $("#string_used_chk").prop('checked', data.string_used === 1 ? true : false);
            $("#year_val_2").prop('checked', data.year_val === 2 ? true : false);
            $("#year_val_4").prop('checked', data.year_val === 4 ? true : false);
            $("#year_used_chk").prop('checked', data.year_used === 1 ? true : false);
            $("#month_used_chk").prop('checked', data.month_used === 1 ? true : false);
            $("#day_used_chk").prop('checked', data.day_used === 1 ? true : false);
            $("#counter_id_form").val(data.counter_id);
            $("#counter_id_form").trigger('change');
        }else{
            resetForm();
        }
    }

    function counterChanged(e){
        var dataList = {!! json_encode($counterList->toArray()) !!};
        let data = dataList.find(x => x.counter_id == e.value);
        if(data){
            $("#counter_form").val(data.counter);
            $("#counter_length_form").val(data.length);
        }
    }

    function resetForm(){
        var tdate = new Date();
        var dayStr = String(tdate.getDate()).padStart(2, '0');
        var monthStr = String(tdate.getMonth() + 1).padStart(2, '0');
        var yearStr = String(tdate.getFullYear());

        $("#numbering_form_name").val('');
        $("#string_val").val('');
        $("#string_used_chk").prop('checked', true);
        $("#year_input_val").val(yearStr);
        $("#year_val_2").prop('checked', true);
        $("#year_val_4").prop('checked', false);
        $("#year_used_chk").prop('checked', true);
        $("#month_val").val(monthStr);
        $("#month_used_chk").prop('checked', true);
        $("#day_val").val(dayStr);
        $("#day_used_chk").prop('checked', true);
        $("#numbering_form_id").val('');
        $("#numbering_form_id").trigger('change');
        $("#counter_id_form").val('');
        $("#counter_id_form").trigger('change');
    }

    function yearchanged(){
        console.log("OKE");
    }

    function return_value(e, data){
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#formCounter").attr("action", "/setting/counter/update");
            $("#defaultModalLabel").text("Edit Counter");
            $("#counter_id").val(data.counter_id);
            $("#counter_name").val(data.counter_name);
            $("#counter").val(data.counter);
            $("#length").val(data.length);

        } else {
            $("#formCounter").trigger("reset");
            $("#formCounter").attr("action", "/setting/counter/add");
            $("#defaultModalLabel").text("Add Counter");

        }
    }

    function saveInit(form, closeModalId){
        saveDataModal(form, closeModalId, function() {
            loadData();
        });
    }

    function deleteInit(e){
        deleteConfirm(e, function() {
            loadData();
        });
    }

    function saveInitNumberingForm(form){
        completeForm();
        saveData(form, function() {
            loadData();
            resetForm();
            $("#numbering_form_type").val('');
            $("#numbering_form_type").trigger('change');
        });
    }

    function completeForm(){
        $("#string_used").val($("#string_used_chk").prop("checked") ? 1 : 0);
        $("#year_used").val($("#year_used_chk").prop("checked") ? 1 : 0);
        $("#month_used").val($("#month_used_chk").prop("checked") ? 1 : 0);
        $("#day_used").val($("#day_used_chk").prop("checked") ? 1 : 0);
    }


</script>
@endsection

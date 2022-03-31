@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">SALES ORDER MEDSOS TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Sales Order Medsos</a></li>
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
                                    <h5>Sales Order Medsos</h5>
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
                                        {{-- <table id="salesOrderTable" class="table table-striped table-bordered nowrap"> --}}
                                        <table id="soTable" class="table table-striped table-bordered nowrap dt-responsive width-100">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Action</th>
                                                    <th>Sales Order No.</th>
                                                    <th>Medsos Description</th>
                                                    <th>SO. Date</th>
                                                    <th>Total Price (Rp.)</th>
                                                    <th>Total Price After Discount (Rp.)</th>
                                                    <th>Grand Total (Rp.)</th>
                                                    <th>Total Paid (Rp.)</th>
                                                    <th>Paid Remain (Rp.)</th>
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
                                    <h5>Sales Order</h5>
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
                                    <form action="/process/sales-order-sosmed/add" method="post" enctype="multipart/form-data" id="soForm">
                                        @csrf
                                        <input type="hidden" name="so_id" id="so_id">
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Sales Order Number </label>
                                            <div class="col-sm-5">
                                                <input type="input" readonly name="so_number" id="so_number" class="form-control">
                                            </div>
                                            <label class="col-sm-5 col-form-label">Auto generate after save as process</label>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">Medsos Gift Away <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <input type="text" name="medsos_description" id="medsos_description" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-2 col-form-label">SO. Date <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <input type="date" name="so_date" id="so_date" class="form-control" required>
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
                                                        <th width="40%">Qty Order</th>
                                                        <th>Unit</th>
                                                        <th>Product Price (Rp.)</th>
                                                        <th>Percent Discount (%)</th>
                                                        <th>Total Price (Rp.)</th>
                                                        <th>Total Discount (Rp.)</th>
                                                        <th>Total Price After Discount (Rp.)</th>
                                                        <th width="80%">Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="bodyProduct">

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Total </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp.</label>
                                                    </span>
                                                    <input type="text" readonly name="total_price-form" id="total_price-form" class="form-control currency text-right" placeholder="Total Price">
                                                    <input type="hidden" readonly name="total_price" id="total_price" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Discount </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">%</label>
                                                    </span>
                                                    <input type="text" value="0" name="percent_discount" id="percent_discount" oninput="inputDiscPercent()" class="form-control text-right" placeholder="Discount (%)">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp.</label>
                                                    </span>
                                                    <input type="text" readonly value="0" readonly name="discount-form" id="discount-form" class="form-control currency text-right" placeholder="Discount (Rp.)">
                                                    <input type="hidden" readonly value="0" name="discount" id="discount" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Total After Discount </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" readonly name="total_price_after_discount-form" id="total_price_after_discount-form" class="form-control currency text-right" placeholder="Total After Discount">
                                                    <input type="hidden" readonly name="total_price_after_discount" id="total_price_after_discount" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-8 col-form-label text-right">
                                                <div class="checkbox-color checkbox-primary">
                                                    <input name="is_ppn"  id="is_ppn" type="hidden">
                                                    <input name="is_ppnCHK" id="is_ppnCHK" oninput="oninputPPN()" type="checkbox" >
                                                    <label for="is_ppnCHK">
                                                        <strong>PPN 10%</strong>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" readonly name="ppn-form" id="ppn-form" class="form-control currency text-right" placeholder="PPN 10%">
                                                    <input type="hidden" readonly name="ppn" id="ppn" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-8 col-form-label text-right">
                                                <div class="checkbox-color checkbox-primary">
                                                    <input name="is_shipping_cost"  id="is_shipping_cost" type="hidden">
                                                    <input name="is_shipping_costCHK" id="is_shipping_costCHK" oninput="oninputShipping()" type="checkbox" >
                                                    <label for="is_shipping_costCHK">
                                                        <strong>Shipping Cost</strong>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text"  readonly name="shipping_cost-form" id="shipping_cost-form" class="form-control currency text-right" placeholder="0">
                                                    <input type="hidden" readonly name="shipping_cost" id="shipping_cost" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Grand Total </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" readonly name="grand_total-form" id="grand_total-form" class="form-control currency text-right" placeholder="Grand Total">
                                                    <input type="hidden" readonly name="grand_total" id="grand_total" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Total Paid </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" value="0" readonly name="total_paid-form" id="total_paid-form" class="form-control currency text-right" placeholder="">
                                                    <input type="hidden" value="0" readonly name="total_paid" id="total_paid" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label text-right"><strong>Paid Type </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <select name="paid_type_id" id="paid_type_id" onchange="paidTypeChange()" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($paidTypeList as $ls)
                                                        <option value="{{ $ls->paid_type_id }}">{{ $ls->type_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" value="0" name="dec_paid-form" id="dec_paid-form" class="form-control currency text-right" placeholder="Discount (%)">
                                                    <input type="hidden" value="0" name="dec_paid" id="dec_paid" class="form-control">
                                                    <input type="hidden" value="0" name="dec_paid_fin" id="dec_paid_fin" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>The Return </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" value="0" readonly name="money_changes-form" id="money_changes-form" class="form-control currency text-right" placeholder="">
                                                    <input type="hidden" value="0" readonly name="money_changes" id="money_changes" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-8 col-form-label text-right"><strong>Paid Remain </strong> <span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <span class="input-group-prepend" id="basic-addon2">
                                                        <label class="input-group-text">Rp. </label>
                                                    </span>
                                                    <input type="text" value="0" readonly name="dec_remain-form" id="dec_remain-form" class="form-control currency text-right" placeholder="">
                                                    <input type="hidden" value="0" readonly name="dec_remain" id="dec_remain" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group row modal-footer">
                                            <div class="col-sm-12" style="text-align:right;">
                                                <button type="button" class="btn btn-default waves-effect closeForm">Close</button>
                                                <input type="hidden" name="is_process" id="is_process">
                                                <input type="hidden" name="is_draft" id="is_draft">
                                                {{-- <button type="button" id="btnCancelPO" class="btn btn-danger waves-effect waves-light" onClick="cancelInit('#btnCancelPO')">Batalkan Process</button> --}}
                                                <button type="button" class="btn btn-secondary waves-effect waves-light" onClick="saveInit('#soForm', 0)" id="saveAsDraft">Save as draft</button>
                                                <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#soForm', 1)" id="saveInitProccess">Save</button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-12" id="payment">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Sales Order Payment</h5>
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
                                <div id="payment_form">
                                    <form action="/process/sales-order-sosmed-payment/add" method="post" enctype="multipart/form-data" id="paymentForm">
                                        @csrf
                                        <div class="row text-center">
                                            <div class="col-sm-8">
                                                <input type="hidden" name="so_id_payment" id="so_id_payment">
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label text-right"><strong>Grand Total </strong> <span class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <span class="input-group-prepend" id="basic-addon2">
                                                                <label class="input-group-text">Rp. </label>
                                                            </span>
                                                            <input type="text" readonly name="grand_total_payment-form" id="grand_total_payment-form" class="form-control currency text-right" placeholder="Grand Total">
                                                            <input type="hidden" readonly name="grand_total_payment" id="grand_total_payment" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label text-right"><strong>Total Paid </strong> <span class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <span class="input-group-prepend" id="basic-addon2">
                                                                <label class="input-group-text">Rp. </label>
                                                            </span>
                                                            <input type="text" value="0" readonly name="total_paid_payment-form" id="total_paid_payment-form" class="form-control currency text-right" placeholder="">
                                                            <input type="hidden" value="0" readonly name="total_paid_payment" id="total_paid_payment" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label text-right"><strong>Paid Type </strong> <span class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                        <select name="paid_type_id_payment" id="paid_type_id_payment" onchange="paidTypeChangePayment()" class="js-example-placeholder col-sm-12" required>
                                                            <option value="">--Select--</option>
                                                            @foreach($paidTypeList as $ls)
                                                                <option value="{{ $ls->paid_type_id }}">{{ $ls->type_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label text-right"><strong>Paid</strong> <span class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <span class="input-group-prepend" id="basic-addon2">
                                                                <label class="input-group-text">Rp. </label>
                                                            </span>
                                                            <input type="text" value="0" name="dec_paid_payment-form" id="dec_paid_payment-form" oninput="calculatePaidPayment()" class="form-control currency text-right" placeholder="Discount (%)">
                                                            <input type="hidden" value="0" name="dec_paid_payment" id="dec_paid_payment" class="form-control">
                                                            <input type="hidden" value="0" name="dec_paid_fin_payment" id="dec_paid_fin_payment" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label text-right"><strong>The Return </strong> <span class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <span class="input-group-prepend" id="basic-addon2">
                                                                <label class="input-group-text">Rp. </label>
                                                            </span>
                                                            <input type="text" value="0" readonly name="money_changes_payment-form" id="money_changes_payment-form" class="form-control currency text-right" placeholder="">
                                                            <input type="hidden" value="0" readonly name="money_changes_payment" id="money_changes_payment" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-4 col-form-label text-right"><strong>Paid Remain </strong> <span class="text-danger">*</span></label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group">
                                                            <span class="input-group-prepend" id="basic-addon2">
                                                                <label class="input-group-text">Rp. </label>
                                                            </span>
                                                            <input type="text" value="0" readonly name="dec_remain_payment-form" id="dec_remain_payment-form" class="form-control currency text-right" placeholder="">
                                                            <input type="hidden" value="0" readonly name="dec_remain_payment" id="dec_remain_payment" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group row modal-footer">
                                                    <div class="col-sm-12" style="text-align:right;">
                                                        <button type="button" class="btn btn-default waves-effect closeFormPayment" onclick="closePayment()">Close</button>
                                                        <button type="button" class="btn btn-primary waves-effect waves-light" onClick="saveInit('#paymentForm')">Add Payment</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div id="payment_form_close">
                                    <div class="form-group row modal-footer">
                                        <div class="col-sm-12" style="text-align:right;">
                                            <button type="button" class="btn btn-default waves-effect closeFormPayment" onclick="closePayment()">Close</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-block">
                                    <div class="dt-responsive table-responsive">
                                        {{-- <table id="salesOrderTable" class="table table-striped table-bordered nowrap"> --}}
                                        <table id="paymentTable" class="table table-striped table-bordered nowrap dt-responsive width-100">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Payment Type.</th>
                                                    <th>Account Name</th>
                                                    <th>Account Number</th>
                                                    <th>Total Paid</th>
                                                    <th>Paid Remain</th>
                                                    <th>Datetime Payment</th>
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

        $("#input").hide();
        $("#payment").hide();
    });

    function loadData(start_date, end_date){
        $('#soTable').DataTable().destroy();
        var table = $('#soTable').DataTable({
            "bPaginate": true,
            "bLengthChange": true,
            "bFilter": true,
            "bInfo": true,
            "ajax": '/process/sales-order-sosmed/load/'+start_date+'/'+end_date,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        return num.row+1;
                    }
                },
                {
                    "mData": "so_id",
                    "mRender": function (data, type, row) {
                        var button = "";
                        @if(isAccess('update', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-edit'></i></button>";
                            button += "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='return_value_payment(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-table'></i></button>";
                        @endif
                        button += "<button class='btn waves-effect waves-light btn-danger btn-icon' onClick='return_print(this, "+ JSON.stringify(row) +")' btn='edit'>&nbsp;<i class='icofont icofont-printer'></i></button>";
                        @if(isAccess('delete', $MenuID))
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.so_number +"("+ row.medsos_description+") sales order??' data-url='/process/sales-order-sosmed/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
                        @endif
                        return button;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var so_number = "";
                        so_number += row.so_number;
                        so_number += row.dec_remain <= 0 ? " <span class='btn-success btn-sm' > -paid off- </span>" : "<span class='btn-warning btn-sm' > -not yet paid off- </span>";
                        return so_number;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var customerStatus = "";
                        customerStatus += row.medsos_description;
                        customerStatus += row.is_process === 1 ? "<span class='btn-success btn-sm' > -procesed- </span>" : "";
                        customerStatus += row.is_draft === 1 ? "<span class='btn-secondary btn-sm' > -draft- </span>" : "";
                        customerStatus += row.is_void === 1 ? "<span class='btn-danger btn-sm' > -void- </span>" : "";
                        return customerStatus;
                    }
                },
                { "data": "so_date" },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + row.total_price.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + row.total_price_after_discount.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + row.grand_total.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + row.dec_paid.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + row.dec_remain.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                    }
                },
                { "data": "description" },
            ]
        });
    }

    function return_value(e, data){
        $("#bodyProduct").html("");
        var btn = $(e).attr("btn");
        if (btn == "edit"){
            $("#soForm").attr("action", "/process/sales-order-sosmed/update");
            $("#so_id").val(data.so_id);
            $("#so_number").val(data.so_number);
            $("#so_date").val(data.so_date);
            $("#medsos_description").val(data.medsos_description);
            $("#description").val(data.description);

            $("#total_price-form").val(data.total_price);
            $("#total_price").val(data.total_price);

            $("#percent_discount").val(data.percent_discount);

            var discountPrice = (parseInt(data.total_price) - parseInt(data.total_price_after_discount));
            $("#discount-form").val(discountPrice);
            $("#discount").val(discountPrice);

            $("#total_price_after_discount-form").val(data.total_price_after_discount);
            $("#total_price_after_discount").val(data.total_price_after_discount);

            $("#is_ppnCHK").prop('checked', parseInt(data.ppn) > 0 ? true : false);


            $("#ppn-form").val(data.ppn);

            $("#ppn").val(data.ppn);

            $("#grand_total-form").val(data.grand_total);
            $("#grand_total").val(data.grand_total);

            $("#total_paid-form").val(data.dec_paid);
            $("#total_paid").val(data.dec_paid);

            $("#dec_paid-form").val(0);
            $("#dec_paid").val(0);

            data.items.forEach(item => {
                addProductToSO(item.product, item);
            });

            $("#total_price-form").trigger("focusout");
            $("#discount-form").trigger("focusout");
            $("#total_price_after_discount-form").trigger("focusout");
            $("#ppn-form").trigger("focusout");
            $("#grand_total-form").trigger("focusout");
            $("#total_paid-form").trigger("focusout");
            $("#dec_paid-form").trigger("focusout");

            if(data.is_process){
                $("#saveInitProccess").hide();
                $("#saveAsDraft").hide();
            }

        } else {
            $("#soForm").trigger("reset");
            $("#btnCancelPO").hide();
            $("#soForm").attr("action", "/process/sales-order-sosmed/add");

            $("#saveInitProccess").show();
            $("#saveAsDraft").show();

        }
        $("#input").show();
        $("#table").hide();
        $("#payment").hide();
        document.documentElement.scrollTop = 0;
    }

    function paidTypeChangePayment(){
        var paidTypeList = {!! json_encode($paidTypeList) !!};
        var paidTypeId = $("#paid_type_id_payment").val();
        var paidType = paidTypeList.find(x => x.paid_type_id == paidTypeId);

        if(parseInt(paidType.is_credit) == 1){
            $("#dec_paid_payment").val(0);
            $("#dec_paid_fin_payment").val(0);
            $("#dec_paid_payment-form").val(0);
            $("#dec_paid_payment-form").attr('readonly', true);
        }else{
            $("#dec_paid_payment").attr('readonly', false);
            $("#dec_paid_payment-form").attr('readonly', false);
        }
        calculatePaidPayment();
    }

    function paidTypeChange(){
        var paidTypeList = {!! json_encode($paidTypeList) !!};
        var paidTypeId = $("#paid_type_id").val();
        var paidType = paidTypeList.find(x => x.paid_type_id == paidTypeId);

        if(parseInt(paidType.is_credit) == 1){
            $("#dec_paid").val(0);
            $("#dec_paid_fin").val(0);
            $("#dec_paid-form").val(0);
            $("#dec_paid-form").attr('readonly', true);
        }else{
            $("#dec_paid").attr('readonly', false);
            $("#dec_paid-form").attr('readonly', false);
        }
        calculatePaid();
    }

    function addToTableProductItem(data){

        var isAdded = false;
        $('#doItemTableList tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var product_id = $(this).find(".product_id").val();
            if(product_id === data.product_id){
                isAdded = true;

                var qty = $(this).find(".qty_order_item").val();
                var product_label_list = $(this).find(".product_label_list").val();
                var dataLabel = product_label_list !== "" ? jQuery.parseJSON(product_label_list) : [];

                if(dataLabel.length >= 1){
                    var isScanned = dataLabel.find(x => x.label_product_id === data.label_product_id);
                    if(isScanned){
                        swal('Info', 'Product label ['+data.no_label+']is scanned, please scan other product label', 'info');
                    }else{
                        var finQty = parseInt(qty) + parseInt(1);
                        $(this).find(".qty_order_item").val(finQty);
                        var dataLabelAdded = justAddToProductLabel(dataLabel, data);
                        $(this).find(".product_label_list").val(dataLabelAdded);
                    }
                }else{
                    var finQty = parseInt(qty) + parseInt(1);
                    $(this).find(".qty_order_item").val(finQty);
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
                qty_order: 1,
                description: "",
                product_label_list: JSON.stringify(labelList),
            };
            addProductToSO(productData, item);
        }

        $("#product_label").val("");
        calculateTotal();
    }

    function return_print(e, data){
        window.open('/process/sales-order-sosmed-print/'+data.so_id, '_blank');
    }

    function btnLoadDataClick(){
    var start_date = $("#start_date").val();
    var end_date = $("#end_date").val();
    if(start_date !== "" && end_date !== ""){
        loadData(start_date, end_date);
    }

}
function countCost(e){
    var qtyOrder = $(e).parent().parent().find(".qty_order_item").val();
    var price = $(e).parent().parent().find(".price_item").val();
    var percentDiscount = $(e).parent().parent().find(".percent_discount_item").val();
    var totalPrice = parseInt(qtyOrder) * parseInt(price);
    var totalDiscount = parseFloat(totalPrice) * (parseFloat(percentDiscount) / 100 );
    var totalPriceAfterDiscount = parseFloat(totalPrice) - parseFloat(totalDiscount);
    $(e).parent().parent().find(".total_price_item").val(totalPrice);
    $(e).parent().parent().find(".total_discount_item").val(totalDiscount);
    $(e).parent().parent().find(".total_price_after_discount_item").val(totalPriceAfterDiscount);
    calculateTotal();
}

function countCostAfterAdd(e){
    var qtyOrder = $(e).find(".qty_order_item").val();
    var price = $(e).find(".price_item").val();
    var percentDiscount = $(e).find(".percent_discount_item").val();
    var totalPrice = parseInt(qtyOrder) * parseInt(price);
    var totalDiscount = parseFloat(totalPrice) * (parseFloat(percentDiscount) / 100 );
    var totalPriceAfterDiscount = parseFloat(totalPrice) - parseFloat(totalDiscount);
    $(e).find(".total_price_item").val(totalPrice);
    $(e).find(".total_discount_item").val(totalDiscount);
    $(e).find(".total_price_after_discount_item").val(totalPriceAfterDiscount);
}

function inputDiscPercent(){
    calculateTotal();
}

function oninputPPN(){
    calculateTotal();
}

$("#dec_paid_payment-form").bind('keyup', function (e) {
    calculatePaidPayment();
});

$("#dec_paid-form").bind('keyup', function (e) {
    calculatePaid();
});

$("#shipping_cost-form").bind('keyup', function (e) {
    calculateTotal();
});

function oninputShipping(){
    calculateTotal();
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

                $(this).find(".qty_order_item").val(qty);
                $(this).find(".product_label_list").val(JSON.stringify(labelListNew));
            }
        }
    });
}

function calculatePaid(){
    var grandTotal = $("#grand_total").val();
    var totalPaid = $("#total_paid").val();
    var decPaidForm = $("#dec_paid-form").val();
    var decPaidFormFin = parseFloat(decPaidForm.split(",").join(""));
    $("#dec_paid").val(decPaidFormFin);

    var decPaid = $("#dec_paid").val();

    var moneyChanges = parseFloat(decPaid) - (parseFloat(grandTotal) - parseFloat(totalPaid));
    var decRemain = (parseFloat(grandTotal) - parseFloat(totalPaid)) - parseFloat(decPaid);
    var decRemainFin = decRemain > parseFloat(0) ? decRemain : 0;
    var moneyChangesFin = moneyChanges > parseFloat(0) ? moneyChanges : 0;
    var decPaidFin = moneyChangesFin <= parseFloat(0) ? parseFloat(decPaid) : (parseFloat(decPaid) - parseFloat(moneyChangesFin));

    $("#dec_paid_fin").val(decPaidFin);
    $("#dec_remain").val(decRemainFin);
    $("#money_changes").val(moneyChangesFin);
    $("#money_changes-form").val(moneyChangesFin);
    $("#dec_remain-form").val(decRemainFin);
    $("#money_changes-form").trigger("focusout");
    $("#dec_remain-form").trigger("focusout");
}

function calculatePaidPayment(){
    var grandTotal = $("#grand_total_payment").val();
    var totalPaid = $("#total_paid_payment").val();
    var decPaidForm = $("#dec_paid_payment-form").val();
    var decPaidFormFin = parseFloat(decPaidForm.split(",").join(""));
    $("#dec_paid_payment").val(decPaidFormFin);

    var decPaid = $("#dec_paid_payment").val();

    var moneyChanges = parseFloat(decPaid) - (parseFloat(grandTotal) - parseFloat(totalPaid));
    var decRemain = (parseFloat(grandTotal) - parseFloat(totalPaid)) - parseFloat(decPaid);
    var decRemainFin = decRemain > parseFloat(0) ? decRemain : 0;
    var moneyChangesFin = moneyChanges > parseFloat(0) ? moneyChanges : 0;
    var decPaidFin = moneyChangesFin <= parseFloat(0) ? parseFloat(decPaid) : (parseFloat(decPaid) - parseFloat(moneyChangesFin));

    $("#dec_paid_fin_payment").val(decPaidFin);
    $("#dec_remain_payment").val(decRemainFin);
    $("#money_changes_payment").val(moneyChangesFin);
    $("#money_changes_payment-form").val(moneyChangesFin);
    $("#dec_remain_payment-form").val(decRemainFin);
    $("#money_changes_payment-form").trigger("focusout");
    $("#dec_remain_payment-form").trigger("focusout");
}

function calculateTotal(){

    var totalPrice = parseFloat(0);
    var discountPercent = parseFloat(0);
    var discountPrice = parseFloat(0);
    var totalPriceAfterDiscount = parseFloat(0);
    var ppnPrice = parseFloat(0);
    var grandTotal = parseFloat(0);
    var is_ppn = $("#is_ppnCHK").prop("checked");
    var is_shipping = $("#is_shipping_costCHK").prop("checked");
    if(is_shipping){
        $("#shipping_cost-form").prop('readonly', false);
    }else{
        $("#shipping_cost-form").val(0);
        $("#shipping_cost").val(0);
        $("#shipping_cost-form").prop('readonly', true);
    }


    $('#bodyProduct tr').each(function() {
        if (!this.rowIndex) return; // skip first row
        countCostAfterAdd(this);
        var total = $(this).find(".total_price_after_discount_item").val();
        totalPrice += parseFloat(total);
    });

    var discountPercentForm = $("#percent_discount").val();
    discountPercent = parseFloat(discountPercentForm.split(",").join(""));
    discountPrice = (parseFloat(totalPrice) * (parseFloat(discountPercent) / 100 ));
    totalPriceAfterDiscount = (parseFloat(totalPrice) - parseFloat(discountPrice));

    $("#total_price-form").val(totalPrice);
    $("#total_price-form").trigger("focusout");
    $("#total_price").val(totalPrice);

    $("#discount-form").val(discountPrice);
    $("#discount-form").trigger("focusout");
    $("#discount").val(discountPrice);

    $("#total_price_after_discount-form").val(totalPriceAfterDiscount);
    $("#total_price_after_discount-form").trigger("focusout");
    $("#total_price_after_discount").val(totalPriceAfterDiscount);

    if(is_ppn){
        ppnPrice = (parseFloat(totalPriceAfterDiscount) * (10 / 100 ));
    }

    var shippingCostForm = $("#shipping_cost-form").val();
    var shippingCost = parseFloat(shippingCostForm.split(",").join(""));

    $("#ppn-form").val(ppnPrice);
    $("#ppn-form").trigger("focusout");
    $("#ppn").val(ppnPrice);
    $("#shipping_cost-form").trigger("focusout");
    $("#shipping_cost").val(shippingCost);

    grandTotal = (totalPriceAfterDiscount + ppnPrice) + shippingCost;
    $("#grand_total-form").val(grandTotal);
    $("#grand_total-form").trigger("focusout");
    $("#grand_total").val(grandTotal);
}

function addProductToSO(data, item){
    var qty_order = 0;
    var price = data.price != undefined ? data.price : 0;
    var percent_discount = 0;
    var total_price = 0;
    var total_discount = 0
    var total_price_after_discount = 0;
    var description = "";
    var product_label_list = "";
    console.log(item.qty_order);
    if(item != undefined){
        qty_order = item.qty_order != undefined ? item.qty_order : qty_order;
        price = item.price != undefined ? item.price : price;
        percent_discount = item.percent_discount != undefined ? item.percent_discount : percent_discount;
        total_price = item.total_price != undefined ? item.total_price : total_price;
        total_discount = item.total_discount != undefined ? item.total_discount : total_discount;
        total_price_after_discount = item.total_price_after_discount != undefined ? item.total_price_after_discount : total_price_after_discount;
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
        addProduct += '<input type="number" readonly oninput="countCost(this)" value="'+qty_order+'" name="qty_order_item[]"  class="qty_order_item form-control" style="width:150px; text-align:center;" required>';
        addProduct += '</td>';

        addProduct += '<td>';
        addProduct += data.unit.unit_name;
        addProduct += '</td>';

        addProduct += '<td>';
        addProduct += '<input type="number" readonly oninput="countCost(this)" value="'+price+'" name="price_item[]"  class="price_item form-control" style="width:150px; text-align:right;" required>';
        addProduct += '</td>';

        addProduct += '<td>';
        addProduct += '<input type="number" oninput="countCost(this)" value="'+percent_discount+'" name="percent_discount_item[]"  class="percent_discount_item form-control" style="width:150px; text-align:center;">';
        addProduct += '</td>';

        addProduct += '<td>';
        addProduct += '<input type="number" readonly value="'+total_price+'" name="total_price_item[]"  class="total_price_item form-control" style="width:150px; text-align:right;" required>';
        addProduct += '</td>';

        addProduct += '<td>';
        addProduct += '<input type="number" readonly value="'+total_discount+'" name="total_discount_item[]"  class="total_discount_item form-control" style="width:150px; text-align:right;" required>';
        addProduct += '</td>';

        addProduct += '<td>';
        addProduct += '<input type="number" readonly value="'+total_price_after_discount+'" name="total_price_after_discount_item[]"  class="total_price_after_discount_item form-control" style="width:200px; text-align:right;" required>';
        addProduct += '</td>';

        addProduct += '<td>';
        addProduct += '<input type="text" value="'+description+'" name="description_item[]"  class="description_item form-control" style="width:500px">';
        addProduct += '</td>';

        addProduct += '</tr>';

    $("#bodyProduct").append(addProduct);

    $("#closeAddProduct").click();
}

$('.closeForm').click(function(e) {
    $("#input").hide();
    $("#table").show();
    $("#payment").hide();
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
        var data = await getData('/process/sales-order-sosmed/get-label/'+product_label);
        if(data != null){
            addToTableProductItem(data);
        }
        else{
            swal('Info', 'Product label ['+product_label+'] is not valid, please scan other product label', 'info');
        }
    }
}
function justAddToProductLabel(dataLabel, data){
    dataLabel.push(data);
    return JSON.stringify(dataLabel);
};

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

function return_value_payment(e, data){
    $("#table").hide();
    $("#input").hide();
    $("#payment").show();
    if(parseFloat(data.dec_remain) === parseFloat(0)){
        $("#payment_form").hide();
        $("#payment_form_close").show();
    }else{
        $("#payment_form").show();
        $("#payment_form_close").hide();
    }
    $("#so_id_payment").val(data.so_id);
    $("#grand_total_payment-form").val(data.grand_total);
    $("#grand_total_payment").val(data.grand_total);
    $("#total_paid_payment-form").val(data.dec_paid);
    $("#total_paid_payment").val(data.dec_paid);
    $("#dec_paid_payment-form").val(0);
    $("#dec_paid_payment").val(0);
    $("#dec_paid_fin_payment").val(0);

    $("#grand_total_payment-form").trigger("focusout");
    $("#total_paid_payment-form").trigger("focusout");
    $("#dec_paid_payment-form").trigger("focusout");

    calculatePaidPayment();

    loadDataPayment(data.so_id);
}

function closePayment(){
    $("#table").show();
    $("#input").hide();
    $("#payment").hide();
}

function loadDataPayment(so_id){
    $('#paymentTable').DataTable().destroy();
    var table = $('#paymentTable').DataTable({
        "bPaginate": true,
        "bLengthChange": true,
        "bFilter": true,
        "bInfo": true,
        "ajax": '/process/sales-order-sosmed-payment/load/'+so_id,
        "aoColumns": [
            {  "mRender": function (data, type, row, num) {
                    return num.row+1;
                }
            },
            { "data": "paid_type.type_name" },
            { "data": "paid_type.account_name" },
            { "data": "paid_type.account_number" },
            {  "mRender": function (data, type, row, num) {
                    return "Rp. " + row.dec_paid.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                }
            },
            {  "mRender": function (data, type, row, num) {
                    return "Rp. " + row.dec_remain.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                }
            },
            {  "mRender": function (data, type, row, num) {
                    var dateString = moment(row.created_at).format('YYYY-MM-DD HH:mm:ss');
                    return dateString;
                }
            },
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
        "ajax": '/process/sales-order-sosmed/load-product',
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
                    var btnAdd = "<button class='btn waves-effect waves-light btn-info btn-icon' onClick='addProductToPO("+ JSON.stringify(row) +", "+item+")'>&nbsp;<i class='icofont icofont-plus'></i> </button>";
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

</script>
@endsection

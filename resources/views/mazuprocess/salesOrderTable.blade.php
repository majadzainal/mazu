@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">SALES ORDER TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Process</a></li>
                        <li class="breadcrumb-item"><a href="#!">Sales Order</a></li>
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
                                                    <th>Customer</th>
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
                                    <form action="/process/sales-order/add" method="post" enctype="multipart/form-data" id="soForm">
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
                                            <label class="col-sm-2 col-form-label">Customer <span class="text-danger">*</span></label>
                                            <div class="col-sm-10">
                                                <select name="customer_id" id="customer_id" class="js-example-placeholder col-sm-12" required>
                                                    <option value="">--Select--</option>
                                                    @foreach($customerList as $ls)
                                                        <option value="{{ $ls->customer_id }}">{{ $ls->customer_name }}</option>
                                                    @endforeach
                                                </select>
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
                                    <form action="/process/sales-order-payment/add" method="post" enctype="multipart/form-data" id="paymentForm">
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
<script type="text/javascript" src="{{ asset ('/assets/files/assets/js/salesorder.js') }}"></script>
<script>
    $(document).ready(function() {
        var date = new Date();
        var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
        var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);

        $("#start_date").val(moment(firstDay).format('YYYY-MM-DD'));
        $("#end_date").val(moment(lastDay).format('YYYY-MM-DD'));

        btnLoadDataClick();

        // loadProduct();
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
            "ajax": '/process/sales-order/load/'+start_date+'/'+end_date,
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
                            button += "<button class='btn waves-effect waves-light btn-warning btn-icon' data-confirm='Are you sure|want to delete "+ row.so_number +"("+ row.customer.customer_name+") sales order??' data-url='/process/sales-order/delete/" + data + "' onClick='deleteInit(this)'>&nbsp;<i class='icofont icofont-trash'></i></button>";
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
                        customerStatus += row.customer.customer_name;
                        customerStatus += row.is_process === 1 ? "<span class='btn-primary btn-sm' > -procesed- </span>" : "";
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
            $("#soForm").attr("action", "/process/sales-order/update");
            $("#so_id").val(data.so_id);
            $("#so_number").val(data.so_number);
            $("#so_date").val(data.so_date);
            $("#customer_id").val(data.customer_id);
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
            $("#is_shipping_costCHK").prop('checked', parseInt(data.shipping_cost) > 0 ? true : false);


            $("#ppn-form").val(data.ppn);
            $("#ppn").val(data.ppn);

            $("#shipping_cost-form").val(data.shipping_cost);
            $("#shipping_cost").val(data.shipping_cost);

            $("#grand_total-form").val(data.grand_total);
            $("#grand_total").val(data.grand_total);

            $("#total_paid-form").val(data.dec_paid);
            $("#total_paid").val(data.dec_paid);

            $("#dec_paid-form").val(0);
            $("#dec_paid").val(0);

            data.items.forEach(item => {
                addProductToSO(item.product, item);
            });

            $("#customer_id").trigger("change");
            $("#total_price-form").trigger("focusout");
            $("#discount-form").trigger("focusout");
            $("#total_price_after_discount-form").trigger("focusout");
            $("#ppn-form").trigger("focusout");
            $("#grand_total-form").trigger("focusout");
            $("#total_paid-form").trigger("focusout");
            $("#dec_paid-form").trigger("focusout");

            calculateTotal();
            calculatePaid();

            if(data.is_process){
                $("#saveInitProccess").hide();
                $("#saveAsDraft").hide();
            }

        } else {
            $("#soForm").trigger("reset");
            $("#customer_id").trigger('change');
            $("#btnCancelPO").hide();
            $("#soForm").attr("action", "/process/sales-order/add");

            $("#saveInitProccess").show();
            $("#saveAsDraft").show();

        }
        $("#input").show();
        $("#table").hide();
        $("#payment").hide();
        document.documentElement.scrollTop = 0;
    }

    function addToTableProductItem(data){

        var isAdded = false;
        $('#doItemTableList tr').each(function() {
            if (!this.rowIndex) return; // skip first row
            var product_id = $(this).find(".product_id").val();
            console.log(data);
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

    function return_print(e, data){
        window.open('/process/sales-order-print/'+data.so_id, '_blank');
    }

</script>
@endsection

@extends('layouts.headerIn')
@section('content')
<div class="pcoded-content">
    <!-- Page-header start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="page-header-title">
                        <h5 class="m-b-10">REPORT SALES ORDER SOSMED TABLE</h5>
                        <p class="m-b-0">&nbsp;</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="index-2.html"> <i class="fa fa-home"></i> </a>
                        </li>
                        <li class="breadcrumb-item"><a href="#!">Report</a></li>
                        <li class="breadcrumb-item"><a href="#!">Sales Order Social Media</a></li>
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
                                    <h5>Report Sales Order Social Media</h5>
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
                                        <table id="soTable" class="table table-striped table-bordered nowrap width-100">
                                            <thead>
                                                <tr>
                                                    <th>No.</th>
                                                    <th>Sales Order No.</th>
                                                    <th>Social Media</th>
                                                    <th>SO. Date</th>
                                                    <th>HPP (Rp.)</th>
                                                    <th>Shippimg Cost (Rp.)</th>
                                                    <th>Grand Total (Rp.)</th>
                                                    <th>Total Paid (Rp.)</th>
                                                    <th>Paid Remain (Rp.)</th>
                                                    <th>HPP (Rp.)</th>
                                                    <th>Shippimg Cost (Rp.)</th>
                                                    <th>Grand Total (Rp.)</th>
                                                    <th>Total Paid (Rp.)</th>
                                                    <th>Paid Remain (Rp.)</th>
                                                    <th>Note / Description</th>
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

    function btnLoadDataClick(){
        var start_date = $("#start_date").val();
        var end_date = $("#end_date").val();
        if(start_date !== "" && end_date !== ""){
            document.getElementById("soTable").deleteTFoot();
            loadData(start_date, end_date);
        }

    }

    function loadData(start_date, end_date){
        var totalHPP = 0;
        var totalShipping = 0;
        var totalGrandTotal = 0;
        var totalPaid = 0;
        var totalPaidRemain = 0;
        var totalMargin = 0;
        var export_name = start_date +" s-d "+end_date;
        $('#soTable').DataTable().destroy();
        var table = $('#soTable').DataTable({
            "bPaginate": false,
            "bLengthChange": false,
            "bFilter": false,
            "bInfo": true,
            "columnDefs": [
                {  className: "text-right", targets: [4, 5, 6, 7, 8] },
                { "targets": 9, "visible": false },
                { "targets": 10, "visible": false },
                { "targets": 11, "visible": false },
                { "targets": 12, "visible": false },
                { "targets": 13, "visible": false },
            ],
            "dom": 'Bfrtip',
            "buttons": [
                {
                    extend:'excelHtml5', footer: true, exportOptions: {
                        format: {
                            body: function ( data, row, column, node ) {
                                return $(data).is("input") ? $(data).val() : data;
                            }
                        },
                        columns: [ 0, 1, 2, 3, 9, 10, 11, 12, 13]
                    },
                    className: 'btn btn-block',
                    text: 'Export To Excel',
                    title: 'Report-Sales-Order-Exc-Reseller-' + export_name,
                },
            ],
            "ajax": '/report/sales-order-medsos/load/'+start_date+'/'+end_date,
            "aoColumns": [
                {  "mRender": function (data, type, row, num) {
                        console.log(row);
                        var numbering = "";
                        numbering += num.row+1;
                        return numbering;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var so_number = "";
                        so_number += row.so_number;
                        return so_number;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var customerStatus = "";
                        customerStatus += row.medsos_description !== null ? row.medsos_description+" - "+row.medsos_description : "";
                        return customerStatus;
                    }
                },
                { "data": "so_date" },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + formatRupiah(parseInt(row.total_hpp));
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + formatRupiah(parseInt(row.shipping_cost));
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + formatRupiah(parseInt(row.grand_total));
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + formatRupiah(parseInt(row.dec_paid));
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        return "Rp. " + formatRupiah(parseInt(row.dec_remain));
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var hpp = '<input type="text" value="'+"Rp. " + formatRupiah(parseInt(row.total_hpp))+'">';
                        return hpp;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var shipping_cost = '<input type="text" value="'+"Rp. " + formatRupiah(parseInt(row.shipping_cost))+'">';
                        return shipping_cost;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var grand_total = '<input type="text" value="'+"Rp. " + formatRupiah(parseInt(row.grand_total))+'">';
                        return grand_total;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var dec_paid = '<input type="text" value="'+"Rp. " + formatRupiah(parseInt(row.dec_paid))+'">';
                        return dec_paid;
                    }
                },
                {  "mRender": function (data, type, row, num) {
                        var dec_remain = '<input type="text" value="'+"Rp. " + formatRupiah(parseInt(row.dec_remain))+'">';
                        return dec_remain;
                    }
                },
                { "data": "description" },
            ],
            "initComplete": function(settings, json) {
                json.data.forEach(item => {
                    totalHPP = parseInt(totalHPP) + parseInt(item.total_hpp);
                    totalShipping = parseInt(totalShipping) + parseInt(item.shipping_cost);
                    totalGrandTotal = parseInt(totalGrandTotal) + parseInt(item.grand_total);
                    totalPaid = parseInt(totalPaid) + parseInt(item.dec_paid);
                    totalPaidRemain = parseInt(totalPaidRemain) + parseInt(item.dec_remain);
                });
                totalMargin = parseInt(totalGrandTotal) - parseInt(totalHPP);
                var totalMarginFin = 'Rp. '+ parseInt(totalMargin).toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                var totalHPPFin = 'Rp. '+ parseInt(totalHPP).toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                var totalGrandTotalFin = 'Rp. '+ parseInt(totalGrandTotal).toFixed(2).toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
                var row = '<tr>';
                row += '<td colspan="4">TOTAL (Rp.)</td>';
                row += '<td class="text-right">Rp. '+ formatRupiah(parseInt(totalHPP)); +'</td>';
                row += '<td class="text-right">Rp. '+ formatRupiah(parseInt(totalShipping)); +'</td>';
                row += '<td class="text-right">Rp. '+ formatRupiah(parseInt(totalGrandTotal)); +'</td>';
                row += '<td class="text-right">Rp. '+ formatRupiah(parseInt(totalPaid)); +'</td>';
                row += '<td class="text-right">Rp. '+ formatRupiah(parseInt(totalPaidRemain)); +'</td>';
                row += '</tr>';
                row += '<tr>';
                row += '<td colspan="4">TOTAL MARGIN (Rp.)</td>';
                row += '<td colspan="5" class="text-right text-bold">'+totalGrandTotalFin+' - '+totalHPPFin+' = '+totalMarginFin+' </td>';
                row += '</tr>';

                $("#soTable").append(
                    $('<tfoot/>').append(row)
                );
            }
        });
    }

</script>
@endsection

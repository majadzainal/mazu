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

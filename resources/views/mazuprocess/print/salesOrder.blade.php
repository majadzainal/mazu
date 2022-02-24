<!DOCTYPE html>
<html>
  <head>
    <title>Purchase Order</title>
    <style>

table {
            border-collapse: collapse;
            width: 100%;
            padding-bottom:3px;
        }

        td {
            /* border: 1px solid #000; */
            padding : 5px 2px;
            /* text-align:center; */
        }

        /* th {
            border: 1px solid #000;
            padding : 1px 3px;
            background-color:#dfebf0;
        } */

        .tbl_item{
            margin-top: 50px;
        }

        .border_item{
            border: 1px solid #363534;
        }

        .margin_table{
            padding: 2px;
        }

        .header_item{
            /* padding-top: 5px;
            padding-bottom: 5px; */
            background-color: #86f7ad;
        }

        .col1{
            width: 2%;
        }
        .col2{
            width: 8%;
        }
        .col3{
            width: 8%;
        }
        .col4{
            width: 8%;
        }
        .col5{
            width: 8%;
        }
        .col6{
            width: 5%;
        }
        .col7{
            width: 5%;
        }
        .col8{
            width: 6%;
        }
        .col9{
            width: 12%;
        }
        .col10{
            width: 10%;
        }
        .col11{
            width: 14%;
        }
        .col12{
            width: 14%;
        }
        /* .f_arial{
            font-family: "Arial Black", "Arial Regular", Gadget, sans-serif;
            font-size: 10px;
        } */
        .f_arial{
            font-family: "Arial Unicode MS", "Arial Regular", Gadget, sans-serif;
            font-size: 10px;
        }

        .f_arial_header{
            font-family: "Arial Unicode MS", "Arial Regular", Gadget, sans-serif;
            font-size: 10px;
            font-weight: bold;
        }
        .f_arial_summary{
            font-family: "Arial Unicode MS", "Arial Regular", Gadget, sans-serif;
            font-size: 10px;
            font-weight: bold;
        }

        .f_arial_bottom{
            font-family: "Arial Unicode MS", "Arial Regular", Gadget, sans-serif;
            font-size: 8px;
            padding: 3px !important;
        }

        .item{
            vertical-align:top;
        }
        .txt-ctr{
            text-align:center;
        }
        .txt-lft{
            text-align:left;
        }
        .txt-rgt{
            text-align:right;
        }

        .color1{
            background-color: #e8f7be;
        }
        .color2{
            background-color: #bef7da;
        }
        .color3{
            background-color:#cdbef7;
        }
        .color4{
            background-color:#fff4d4;
        }

    </style>
  </head>
  <body>
      <table style="width:100%;border:0">
        <tr>
            <td class="f_arial col1"></td>
            <td class="f_arial col2"></td>
            <td class="f_arial col3"></td>
            <td class="f_arial col4"></td>
            <td class="f_arial col5"></td>
            <td class="f_arial col6"></td>
            <td class="f_arial col7"></td>
            <td class="f_arial col8"></td>
            <td class="f_arial col9"></td>
            <td class="f_arial col10"></td>
            <td class="f_arial col11"></td>
            <td class="f_arial col12"></td>
        </tr>
        <tr class="color4">
            <td colspan="12" style="text-align:center;vertical-align:middle;">
                <img src="uploads/MAZU-LABEL-BANNER.png" style="width:100%" />
            </td>
        </tr>
        <tr class="color4">
            <td colspan="2" class="f_arial" style="text-align:left;vertical-align:middle;">
                No. Invoice
            </td>
            <td colspan="2" class="f_arial" style="text-align:left;vertical-align:middle;">
                {{ ": ".$data->so_number }}
            </td>
            <td colspan="3" class="f_arial" style="text-align:left;vertical-align:middle;">

            </td>
            <td colspan="2" class="f_arial" style="text-align:right;vertical-align:middle;">
                To
            </td>
            <td colspan="3" class="f_arial" style="text-align:left;vertical-align:middle;">
                {{ ": ".$data->customer->customer_name }}
            </td>
        </tr>
        <tr class="color4">
            <td colspan="2" class="f_arial" style="text-align:left;vertical-align:middle;">
                Date
            </td>
            <td colspan="2" class="f_arial" style="text-align:left;vertical-align:middle;">
                {{ ": ".$data->so_date }}
            </td>
            <td colspan="3" class="f_arial" style="text-align:left;vertical-align:middle;">

            </td>
            <td colspan="2" class="f_arial" style="text-align:right;vertical-align:top;">
                Address
            </td>
            <td colspan="3" class="f_arial" style="text-align:left;vertical-align:top;">
                {{ ": ".$data->customer->address }}
            </td>
        </tr>
        <tr class="header_item">
            <td class="f_arial_header col-1 txt-ctr border_item">No</td>
            <td colspan="6" class="f_arial_header col-2 txt-ctr border_item" >Product</td>
            <td class="f_arial_header col-8 txt-ctr border_item">Qty</td>
            <td class="f_arial_header col-9 txt-ctr border_item">Price (Rp.)</td>
            <td class="f_arial_header col-10 txt-ctr border_item">Discount (%)</td>
            <td colspan="2" class="f_arial_header col-11 txt-ctr border_item">Total Price (Rp.)</td>
        </tr>
            @php
                $no = 1;
                $totalQty = 0;
                $total = 0;
                $totalDiscount = (($data->percent_discount / 100) * $data->total_price);
                $grandTotal = 0;
            @endphp
            @foreach ($data->items as $ls)
            @php
                $odd = $no % 2;
            @endphp
            <tr class="color4">
                <td class="f_arial txt-ctr border_item">
                    {{ $no }}
                </td>
                <td colspan="6" class="f_arial txt-lft border_item">
                    {{ $ls->product->product_name }}
                </td>
                <td class="f_arial txt-ctr border_item">
                    {{ $ls->qty_order }}
                </td>
                <td class="f_arial txt-rgt border_item">
                    {{ "Rp. ".number_format($ls->price) }}
                </td>
                <td class="f_arial txt-ctr border_item">
                    {{ number_format($ls->percent_discount)."%" }}
                </td>
                <td colspan="2" class="f_arial txt-rgt border_item">
                    {{ "Rp. ".number_format($ls->total_price_after_discount) }}
                </td>
            </tr>

            @php
                $no += 1;
                $totalQty += (int)$ls->qty_order;
                $total += (int)$ls->total_price_after_discount;
            @endphp
            @endforeach

        <tr class="color4">
            <td colspan="7" class="f_arial_summary txt-rgt">
                Total
            </td>
            <td colspan="1" class="f_arial_summary txt-ctr">
                {{ number_format($totalQty) }}
            </td>
            <td colspan="2" class="f_arial_summary" >

            </td>
            <td colspan="2" class="f_arial_summary txt-rgt">
                {{ "Rp. ".number_format($total) }}
            </td>
        </tr>
        <tr class="color4">
            <td colspan="7" class="f_arial_summary txt-rgt">
                Discount
            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary txt-ctr">
                {{ number_format($data->percent_discount)."%" }}
            </td>
            <td colspan="2" class="f_arial_summary txt-rgt">
                {{ "Rp. ".number_format($totalDiscount) }}
            </td>
        </tr>
        <tr class="color4">
            <td colspan="7" class="f_arial_summary txt-rgt">
                PPN
            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary txt-ctr">
                10 %
            </td>
            <td colspan="2" class="f_arial_summary txt-rgt">
                {{ "Rp. ".number_format($data->ppn) }}
            </td>
        </tr>
        <tr class="color1">
            <td colspan="7" class="f_arial_summary txt-rgt">
                Total After Discount
            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary txt-ctr">

            </td>
            <td colspan="2" class="f_arial_summary txt-rgt">
                {{ "Rp. ".number_format($data->total_price_after_discount) }}
            </td>
        </tr>
        <tr class="color2">
            <td colspan="7" class="f_arial_summary txt-rgt">
                Grand Total
            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary txt-ctr">

            </td>
            <td colspan="2" class="f_arial_summary txt-rgt">
                {{ "Rp. ".number_format($data->grand_total) }}
            </td>
        </tr>
        <tr class="color3">
            <td colspan="7" class="f_arial_summary txt-rgt">
                Cash In
            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary">

            </td>
            <td colspan="1" class="f_arial_summary txt-ctr">

            </td>
            <td colspan="2" class="f_arial_summary txt-rgt">
                {{ "Rp. ".number_format($data->dec_paid) }}
            </td>
        </tr>
        <tr class="color4">
            <td colspan="12">
                &nbsp;
            </td>
        </tr>
        <tr class="color4">
            <td colspan="12">
                &nbsp;
            </td>
        </tr>
        <tr class="color4">
            <td colspan="12" class="txt-ctr f_arial_bottom">
                www.mazushopping.com
            </td>
        </tr>
        <tr class="color4">
            <td colspan="12" class="txt-ctr f_arial_bottom">
                &#64;mazulabel | &#64;mazuscraf | 0857 1699 8249 | mazuscarf@gmail.com
            </td>
        </tr>
        <tr class="color4">
            <td colspan="12" class="txt-ctr f_arial_bottom">

            </td>
        </tr>


      </table>
  </body>
</html>

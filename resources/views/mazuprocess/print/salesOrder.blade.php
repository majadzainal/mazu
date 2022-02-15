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
            border: 1px solid #000;
            padding : 1px 3px;
            /* text-align:center; */
        }

        th {
            border: 1px solid #000;
            padding : 1px 3px;
            background-color:#dfebf0;
        }

        .heading_item{
            border: 2px solid #000;
            padding : 2px 3px;
            background-color:#dfebf0;
        }
        .tbl_item{
            margin-top: 50px;
        }

        .border_item{
            border: 1px solid #000;
            padding : 2px 2px;
        }

        .spacer{
            padding: 3px 0px;
        }
        .spacer2{
            padding: 6px 0px;
        }
        .margin_table{
            padding: 2px;
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
        .f_arial{
            font-family: "Arial Black", "Arial Regular", Gadget, sans-serif;
            font-size: 10px;
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

    </style>
  </head>
  <body>
      <table style="width:100%;border:1">
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
        <tr>
            <td class="f_arial col-1" style="text-align:center;vertical-align:middle;">No</td>
            <td colspan="6" class="f_arial col-2" style="text-align:center;vertical-align:middle;">Product</td>
            <td class="f_arial col-8" style="text-align:center;vertical-align:middle;">Qty</td>
            <td class="f_arial col-9" style="text-align:center;vertical-align:middle;">Price (Rp.)</td>
            <td class="f_arial col-10" style="text-align:center;vertical-align:middle;">Discount (%)</td>
            <td colspan="2" class="f_arial col-11" style="text-align:center;vertical-align:middle;">Total Price (Rp.)</td>
        </tr>
            @php
                $no = 1;
                $total = 0;
                $grandTotal = 0;
            @endphp
            @foreach ($data->items as $ls)
            <tr>
                <td class="f_arial txt-ctr">
                    {{ $no }}
                </td>
                <td colspan="6" class="f_arial txt-lft">
                    {{ $ls->product->product_name }}
                </td>
                <td class="f_arial txt-ctr">
                    {{ $ls->qty_order }}
                </td>
                <td class="f_arial txt-rgt">
                    {{ "Rp. ".number_format($ls->price) }}
                </td>
                <td class="f_arial txt-ctr">
                    {{ number_format($ls->percent_discount)."%" }}
                </td>
                <td colspan="2" class="f_arial txt-rgt">
                    {{ "Rp. ".number_format($ls->total_price_after_discount) }}
                </td>
            </tr>
            @endforeach

      </table>
  </body>
</html>

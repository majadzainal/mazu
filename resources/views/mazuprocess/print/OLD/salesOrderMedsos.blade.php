<!DOCTYPE html>
<html>
  <head>
    <title>Sales Order Exclusive Reseller</title>
    <style>

table {
            border-collapse: collapse;
            width: 100%;
            padding-bottom:3px;
        }

        td {
            /* border: 1px solid #000; */
            padding : 2px 2px;
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
            /* border: 1px solid #363534; */
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
            background-color: #000;
            color: #fff;
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

        .imageProduct{
            width: 10%;
            border-radius: 50px;
        }

        .bottom_border{
             border-bottom: 1px solid #0f0f0f;
        }

    </style>
  </head>
  <body>
      <table style="width:100%;border:0">
        <tr>
            <td colspan="12" style="text-align:center;vertical-align:middle;">
                <img src="uploads/{{ getBanner() }}" style="width:100%" />
            </td>
        </tr>
        <tr>
            <td colspan="9" class="f_arial" style="text-align:left;vertical-align:top;font-size:16 !important;">
                Order #{{ $data->so_number }}
            </td>
            <td colspan="9" class="f_arial" style="text-align:right;vertical-align:top;">
                @php
                    $today =  date_format( $data->updated_at, '\o\n l jS F Y g:ia');
                @endphp
                Placed on {{ $today }}
            </td>
        </tr>
        <tr>
            <td colspan="12" class="f_arial bottom_border" style="text-align:left;vertical-align:bottom;">
                @php
                    $medsos = $data->medsos_description ? $data->medsos_description : " - ";
                @endphp
                {{ $medsos }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
            </td>
        </tr>

        @foreach ($data->items as $ls)
            <tr>
                <td rowspan="3" colspan="3" style="text-align:center;vertical-align:middle;padding:0px !important;">
                    @php
                        $prodImg = $ls->product->images !== "" ? $ls->product->images : getLogo();
                    @endphp
                    <img src="uploads/{{ $prodImg }}" style="width:11%; border-radius:50px;" />
                </td>

                <td colspan="5">
                </td>

                <td>
                </td>

                <td>
                </td>
                <td colspan="2">
                </td>
            </tr>
            <tr>
                <td colspan="5"  class="f_arial" style="text-align:left;vertical-align:top;">
                    {{ $ls->product->product_name }}<br/>SKU :
                </td>

                <td  class="f_arial" style="text-align:right;vertical-align:top;">
                    {{ "Rp. ".number_format($ls->price) }}
                </td>

                <td  class="f_arial" style="text-align:center;vertical-align:top;">
                    {{ "x ".$ls->qty_order }}
                </td>
                <td colspan="2"  class="f_arial" style="text-align:right;vertical-align:top;">
                    {{ "Rp. ".number_format($ls->total_price_after_discount) }}
                </td>
            </tr>
            <tr>
                <td colspan="5">
                </td>

                <td>
                </td>

                <td>
                </td>
                <td colspan="2">
                </td>
            </tr>
            <tr>
                <td colspan="12" class="bottom_border"></td>
            </tr>
        @endforeach

        <tr>
            <td colspan="8"  class="f_arial" style="text-align:left;vertical-align:top;">

            </td>

            <td colspan="2" class="f_arial" style="text-align:left;vertical-align:top;">

            </td>

            <td colspan="2"  class="f_arial" style="text-align:right;vertical-align:top;">
                {{ "Rp. ".number_format($data->total_price) }}
            </td>
        </tr>
        <tr>
            <td colspan="8"  class="f_arial" style="text-align:left;vertical-align:top;">

            </td>

            <td colspan="2" class="f_arial" style="text-align:left;vertical-align:top;">
                Discount
            </td>

            <td colspan="1"  class="f_arial" style="text-align:center;vertical-align:top;">
                {{ (int)$data->percent_discount."%" }}
            </td>
            <td colspan="1"  class="f_arial" style="text-align:right;vertical-align:top;">
                @php
                    $discount = (floatval($data->percent_discount) / 100) * floatval($data->total_price);
                @endphp
                {{ "Rp. ".number_format((int)$discount) }}
            </td>
        </tr>
        <tr>
            <td colspan="8"  class="f_arial" style="text-align:left;vertical-align:top;">

            </td>

            <td colspan="2" class="f_arial" style="text-align:left;vertical-align:top;">
                Subtotal
            </td>

            <td colspan="2"  class="f_arial" style="text-align:right;vertical-align:top;">
                {{ "Rp. ".number_format($data->total_price_after_discount) }}
            </td>
        </tr>
        <tr>
            <td colspan="8"  class="f_arial" style="text-align:left;vertical-align:top;">

            </td>

            <td colspan="2" class="f_arial" style="text-align:left;vertical-align:top;">
                Tax
            </td>

            <td colspan="2"  class="f_arial" style="text-align:right;vertical-align:top;">
                @php
                    $tax = $data->ppn ? $data->ppn : 0;
                @endphp
                {{ "Rp. ".number_format($tax) }}
            </td>
        </tr>
        <tr>
            <td colspan="8"  class="f_arial" style="text-align:left;vertical-align:top;">

            </td>

            <td colspan="2" class="f_arial bottom_border" style="text-align:left;vertical-align:top;">
                Shipping
            </td>

            <td colspan="2"  class="f_arial bottom_border" style="text-align:right;vertical-align:top;">
                @php
                    $shipping_cost = $data->shipping_cost ? $data->shipping_cost : 0;
                @endphp
                {{ "Rp. ".number_format($shipping_cost) }}
            </td>
        </tr>
        <tr>
            <td colspan="8"  class="f_arial" style="text-align:left;vertical-align:top;">

            </td>

            <td colspan="2" class="f_arial" style="text-align:left;vertical-align:top;">
                Total
            </td>

            <td colspan="2"  class="f_arial" style="text-align:right;vertical-align:top;">
                @php
                    $grand_total = (int)$shipping_cost + (int)$data->grand_total;
                @endphp
                {{ "Rp. ".number_format($grand_total) }}
            </td>
        </tr>
        <tr>
            <td colspan="12"  class="f_arial" style="text-align:left;vertical-align:top;font-size:14 !important;">
                Customer Info
            </td>
        </tr>
        <tr>
            <td colspan="12"  class="f_arial" style="text-align:left;vertical-align:top;font-size:12 !important;">
                Billing Address
            </td>
        </tr>
        <tr>
            <td colspan="12"  class="f_arial" style="text-align:left;vertical-align:top;">
                {{ $medsos }}
            </td>
        </tr>

        <tr>
            <td colspan="12"  class="f_arial" style="text-align:left;vertical-align:top;">

            </td>
        </tr>
        <tr>
            @php
                $companyData = getCompany();
                $sosmed = "";
                $contact = "";
                $address = "";
                if($companyData !== null ){
                    $sosmed .= $companyData->instagram !== null ? " IG : ".$companyData->instagram : "";
                    $sosmed .= $companyData->facebook !== null ? " FB : ".$companyData->facebook : "";
                    $sosmed .= $companyData->youtube !== null ? " Youtube : ".$companyData->youtube : "";

                    $contact .= $companyData->whatsapp !== null ? "WA : ".$companyData->whatsapp : "";
                    $contact .= $companyData->email !== null ? " email : ".$companyData->email : "";
                    $contact .= $companyData->website !== null ? " ".$companyData->website : "";

                    $address .= $companyData->address !== null ? "".$companyData->address : "";
                }
            @endphp
            <td colspan="12"  class="f_arial_bottom" style="text-align:center;vertical-align:top;">
                {{ $sosmed }}
                <br>
                {{ $contact }}
                <br>
                {{ $address }}
            </td>
        </tr>
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
      </table>
  </body>
</html>

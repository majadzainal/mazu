<!DOCTYPE html>
<html>
  <head>
    <title>Print Label</title>
    <style>
        @page { margin: 0px; }
        body { margin: 0px; }

        table {
            border-collapse: collapse;
            width: 100%;
            padding-bottom:3px;
        }
        tr{
            padding: 15px !important;
        }

        td {
            border: 1px solid #000;

            /* padding : 10px 10px; */
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
            width: 10%;
            border: none;
        }
        .col2{
            width: 10%;
            border: none;
        }
        .col3{
            width: 10%;
            border: none;
        }
        .col4{
            width: 10%;
            border: none;
        }
        .col5{
            width: 10%;
            border: none;
        }
        .col6{
            width: 10%;
            border: none;
        }
        .col7{
            width: 10%;
            border: none;
        }
        .col8{
            width: 10%;
            border: none;
        }
        .col9{
            width: 10%;
            border: none;
        }
        .col10{
            width: 10%;
            border: none;
        }
        /* .f_arial{
            font-family: "Arial Black", "Arial Regular", Gadget, sans-serif;
            font-size: 10px;
        } */
        .f_arial{
            font-family: "Arial Unicode MS", "Arial Regular", Gadget, sans-serif;
            font-size: 14px;
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
        </tr>
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
            // dd($dataList);
            $odd = 1;
        @endphp

        @foreach ($dataList as $ls)
            @if ($odd % 2 != 0){
                <tr>
                    <td colspan="5" class="f_arial" style="text-align:left;vertical-align:middle;padding:8px 8px 8px 8px;">
                        <div class="txt-ctr">{!! DNS2D::getBarcodeHTML($ls->no_label, 'QRCODE', 4, 4) !!}</div>
                        <span style="font-size:10 !important;">
                            {{ $ls->no_label }}
                            <br/>
                            {{ $ls->product->category->category_name }}
                            <br/>
                            {{ $ls->product->product_name }}
                        </span>
                    </td>
            @else
                    <td colspan="5" class="f_arial" style="text-align:left;vertical-align:middle;padding:8px 8px 8px 8px;">
                        <div class="txt-ctr">{!! DNS2D::getBarcodeHTML($ls->no_label, 'QRCODE', 4, 4) !!}</div>
                        <span style="font-size:10 !important;">
                            {{ $ls->no_label }}
                            <br/>
                            {{ $ls->product->category->category_name }}
                            <br/>
                            {{ $ls->product->product_name }}
                        </span>
                    </td>
                </tr>
            @endif

            @php
                $odd += 1;
            @endphp
        @endforeach
      </table>
  </body>
</html>

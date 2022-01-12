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
        padding : 3px;
        text-align:center;
      }

      th {
        border: 1px solid #000;
        padding : 3px;
        background-color:#dfebf0;
      }

    </style>
  </head>
  <body>
    <table style="width:100%;border:0">
      <tr>
        <td style="width:50%;border:0;">
        <td style="width:50%;border:0;vertical-align:top">
          <table style="width:100%;border:0">
            <tr>
              <td style="border:0;text-align:center;font-size:17px">
                <b>PT. REKADAYA MULTI  ADIPRIMA</b>
              </td>
            </tr>
            <tr>
              <td style="border:0;text-align:center">
                Automotive Component Manufacturers
              </td>
            </tr>
            <tr>
              <td style="border:0;text-align:center;font-size:12px">
                ALT. CIBUBUR - CILEUNGSI,  CIANGSANA RAYA
              </td>
            </tr>
            <tr>
              <td style="border:0;text-align:center;font-size:12px">
                JL.  NUSA INDAH  NO. 55,  DS. NAGRAK, GN. PUTRI
              </td>
            </tr>
            <tr>
              <td style="border:0;text-align:center;font-size:12px">
                TELP. 021- 82496046, 82498152, FAX:  021 8231774,  82498153
              </td>
            </tr>
          </table> 
        </td>
      </tr>
      
    </table>  
    <br>
    <table style="width:100%;border:1;font-size:15px;">
      <tr>
        <td style="width:10%;border:0;text-align:left;">
          Plant
        </td>
        <td style="width:1%;border:0;text-align:left;">
          :
        </td>
        <td style="border:0;text-align:left;">
          {{ ($plant)? "-": $plant->plant_name}}
        </td>
      
      </tr>
      <tr>
        <td style="width:10%;border:0;text-align:left;">
          Tanggal
        </td>
        <td style="width:1%;border:0;text-align:left;">
          :
        </td>
        <td style="border:0;text-align:left;">
          {{ ($datefrom == $dateto ? $datefrom : $datefrom." s/d ".$dateto) }}
        </td>
      </tr>
    </table>
    
    <span style="font-size:15px;">Part Production</span>
    <table style="width:100%;border:1;font-size:13px;">
      <tr>
        <th style="width:5%;border:1;text-align:center;"><b>No</b></th>
        <th style="width:30%;border:1;text-align:center;"><b>Part Customer</b></th>
        <th style="width:30%;border:1;text-align:center;"><b>Qty</b></th>
        <th style="width:30%;border:1;text-align:center;"><b>Date</b></th>
      </tr>
      @foreach($report['data']['partProductionList'] as $key=>$ls)
        <tr>
          <td style="border:1;">{{ ($key+1) }}</td>
          <td style="border:1;text-align:left;">{{ $ls->part_customer->part_name }}</td>
          <td style="border:1;">{{ $ls->qty }}</td>
          <td style="border:1;">{{ $ls->schedule_date }}</td>
        </tr>
      @endforeach
    </table>
    <br>
    <span style="font-size:15px;">Raw Material</span>
    <table style="width:100%;border:1;font-size:13px;">
      <tr>
        <th style="width:5%;border:1;text-align:center;"><b>No</b></th>
        <th style="width:30%;border:1;text-align:center;"><b>Material</b></th>
        <th style="width:20%;border:1;text-align:center;"><b>Qty</b></th>
        <th style="width:20%;border:1;text-align:center;"><b>Unit</b></th>
        <th style="width:25%;border:1;text-align:center;"><b>Warehouse</b></th>
      </tr>
      @foreach($report['data']['rawMatList'] as $key=>$ls)
        <tr>
          <td style="border:1;">{{ ($key+1) }}</td>
          <td style="border:1;text-align:left;">{{ ($ls->part_supplier != null ? $ls->part_supplier->part_name : $ls->part_customer->part_name) }}</td>
          <td style="border:1;text-align:center;">{{ $ls->qty }}</td>
          <td style="border:1;text-align:center;">{{ $ls->units['unit_name'] }}</td>
          <td style="border:1;text-align:left;">{{ $ls->warehouse['warehouse_name'] }}</td>
        </tr>
      @endforeach
    </table>
      
  </body>
</html>
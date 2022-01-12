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
        padding : 4px;
        text-align:center;
      }

      th {
        border: 1px solid #000;
        padding : 5px;
        background-color:#dfebf0;
      }

    </style>
  </head>
  <body>
    
    <table style="width:100%;border:0">
      <tr>
        <td style="width:2%;border:0;">
          <img src="assets/files/assets/images/rekadaya.png" style="width:50" />
        </td>
        <td style="width:98%;border:0;vertical-align:top">
          <table style="width:100%;border:0">
            <tr>
              <td style="border:0;text-align:left;font-size:17px">
                <b>PT. REKADAYA MULTI ADIPRIMA</b>
              </td>
            </tr>
            <tr>
              <td style="border:0;text-align:left">
                Automotive Parts Manufacturers
              </td>
            </tr>
          </table> 
        </td>
      </tr>
    </table>  
    <br>
    <table border="0">
      <tr>
        <td style="border:0;text-align:left;padding:1px">Tanggal : {{ $report->report_date }}</td>
        <td style="border:0;text-align:right;padding:1px">Plant : {{ $report->plant->plant_name }}</td>
      </tr>
    </table>
    <table style="width:100%;border:1;">
      <tr>
        <th style="width:5%;border:1;text-align:center;"><b>NO.</b></th>
        <th style="width:20%;border:1;text-align:center;"><b>NAMA PART</b></th>
        <th style="width:10%;border:1;text-align:center;"><b>PRODUCTION SCHEDULE/PLAN</b></th>
        <th style="width:10%;border:1;text-align:center;"><b>ACTUAL</b></th>
        <th style="width:10%;border:1;text-align:center;"><b>OVER TIME</b></th>
        <th style="width:10%;border:1;text-align:center;"><b>TOTAL</b></th>
      </tr>
      @foreach($report->report_item as $key=>$ls)
        <tr>
          <td style="border:1;">{{ ($key+1) }}</td>
          @if($ls->is_wip == 0)
            <td style="border:1;text-align:left;">{{ $ls->part_customer->part_name }}</td>
          @else
            <td style="border:1;text-align:left;padding-left:50px;">&bull; WIP : {{ $ls->part_customer->part_name }}</td>
          @endif
          <td style="border:1;">{{ $ls->production_plan }}</td>
          <td style="border:1;">{{ $ls->actual }}</td>
          <td style="border:1;">{{ $ls->over_time }}</td>
          <td style="border:1;">{{ $ls->total }}</td>
        </tr>
      @endforeach
    </table>
    <br>
    
    <table align="right" style="width:50%;border:1;font-size:14px;">
      <tr>
        <td style="width:33%;border:1;text-align:center;"><b>Di Buat,</b></td>
        <td style="width:33%;border:1;text-align:center;"><b>Di Cek,</b></td>
        <td style="width:33%;border:1;text-align:center;"><b>Di Ketahui,</b></td>
      </tr>
      <tr>
        <td style="border:1;padding-top:25px">&nbsp;</td>
        <td style="border:1;padding-top:25px">&nbsp;</td>
        <td style="border:1;padding-top:25px">&nbsp;</td>
      </tr>
      <tr>
        <td style="border:1">&nbsp;</td>
        <td style="border:1">&nbsp;</td>
        <td style="border:1">&nbsp;</td>
      </tr>
      <tr>
        <td style="width:33%;border:1;text-align:center;"><b>Group Leader</b></td>
        <td style="width:33%;border:1;text-align:center;"><b>Supervisor</b></td>
        <td style="width:33%;border:1;text-align:center;"><b>PPIC/Ka. Produksi</b></td>
      </tr>
    </table>    
  </body>
</html>
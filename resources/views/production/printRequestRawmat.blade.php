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
        <td style="border:0;text-align:left;padding:1px">Tanggal : {{ $req->request_date }}</td>
        <td style="border:0;text-align:right;padding:1px">Plant : {{ $req->plant->plant_name }}</td>
      </tr>
    </table>
    <table style="width:100%;border:1;font-size:15px;">
      <tr>
        <th style="width:5%;border:1;text-align:center;"><b>No</b></th>
        <th style="width:35%;border:1;text-align:center;"><b>NAMA MATERIAL</b></th>
        <th style="width:10%;border:1;text-align:center;"><b>QUANTITY</b></th>
        <th style="width:20%;border:1;text-align:center;"><b>WAREHOUSE</b></th>
        <th style="width:30%;border:1;text-align:center;"><b>KETERANGAN</b></th>
      </tr>
      @foreach($req->request_item as $key=>$ls)
        <tr>
          <td style="border:1;">{{ ($key+1) }}</td>
          <td style="border:1;text-align:left;">{{ ($ls->part_supplier == "" ? $ls->part_customer->part_name : $ls->part_supplier->part_name) }}</td>
          <td style="border:1;">{{ $ls->qty }}</td>
          <td style="border:1;">{{ $ls->warehouse->warehouse_name }}</td>
          <td style="border:1;">{{ $ls->note }}</td>
        </tr>
      @endforeach
    </table>
    <br>
    
    <table align="right" style="width:50%;border:1;font-size:15px;">
      <tr>
        <td style="width:33%;border:1;text-align:center;"><b>Dibuat,</b></td>
        <td style="width:33%;border:1;text-align:center;"><b>Diketahui,</b></td>
        <td style="width:33%;border:1;text-align:center;"><b>Disetujui,</b></td>
      </tr>
      <tr>
        <td style="border:1;padding-top:25px">&nbsp;</td>
        <td style="border:1;padding-top:25px">&nbsp;</td>
        <td style="border:1;padding-top:25px">&nbsp;</td>
      </tr>
      <tr>
        <td style="border:1">{{ $req->log_request->where('status_process', '2')->first()->created_user }}</td>
        <td style="border:1">{{ $req->log_request->where('status_process', '3')->first()->created_user }}</td>
        <td style="border:1">{{ $req->log_request->where('status_process', '4')->first()->created_user }}</td>
      </tr>
      <tr>
        <td style="width:33%;border:1;text-align:center;"><b>Leader</b></td>
        <td style="width:33%;border:1;text-align:center;"><b>Supervisor</b></td>
        <td style="width:33%;border:1;text-align:center;"><b>PPIC/Gudang</b></td>
      </tr>
    </table>    
  </body>
</html>
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
        <td style="width:10%;border:0;">
        <td style="width:40%;border:0;vertical-align:top">
          <table style="width:100%;border:0;font-size:15px;">
            <tr>
              <td style="width:10%;border:0;text-align:left;vertical-align:top">
                Kepada 
              </td>
              <td style="width:1%;border:0;text-align:left;vertical-align:top">
                :
              </td>
              <td style="border:0;text-align:left;vertical-align:top">
                {{ $po->supplier->business_entity.". ".$po->supplier->supplier_name }}
              </td>
            </tr>
            <tr>
              <td style="width:10%;border:0;text-align:left;vertical-align:top">
                Alamat 
              </td>
              <td style="width:1%;border:0;text-align:left;vertical-align:top">
                :
              </td>
              <td style="border:0;text-align:left;vertical-align:top">
                {{ $po->supplier->supplier_address }}
              </td>
            </tr>
            <tr>
              <td style="width:10%;border:0;text-align:left;vertical-align:top">
                Telp 
              </td>
              <td style="width:1%;border:0;text-align:left;vertical-align:top">
                :
              </td>
              <td style="border:0;text-align:left;vertical-align:top">
                {{ $po->supplier->supplier_telephone }}
              </td>
              <tr>
              <td style="width:10%;border:0;text-align:left;vertical-align:top">
                Up 
              </td>
              <td style="width:1%;border:0;text-align:left;vertical-align:top">
                :
              </td>
              <td style="border:0;text-align:left;vertical-align:top">
                
              </td>
            </tr>
          </table> 
        </td>
      </tr>
      
    </table>  
    <table style="width:100%;border:1">
      <tr>
        <td style="border:1;text-align:center;font-size:20px">
          <b>PURCHASE ORDER</b>
        </td>
      </tr>
    </table>
    <table style="width:100%;border:1;font-size:13px;">
      <tr>
        <td style="width:10%;border:0;text-align:left;">
          No. PO
        </td>
        <td style="width:1%;border:0;text-align:left;">
          :
        </td>
        <td style="border:0;text-align:left;">
          {{ $po->po_number }}
        </td>
        <td style="width:46%;border:0;" rowspan="2"></td>
        <td style="border:1;text-align:center" rowspan="2">Revisi : </td>
      </tr>
      <tr>
        <td style="width:10%;border:0;text-align:left;">
          Tanggal
        </td>
        <td style="width:1%;border:0;text-align:left;">
          :
        </td>
        <td style="border:0;text-align:left;">
          {{ $po->po_date }}
        </td>
      </tr>
    </table>
    
    <table style="width:100%;border:1;font-size:13px;">
      <tr>
        <td style="width:3%;border:1;text-align:center;">No</td>
        <td style="width:30%;border:1;text-align:center;">Nama Barang</td>
        <td style="width:26%;border:1;text-align:center;">Type</td>
        <td style="width:6%;border:1;text-align:center;">Qty</td>
        <td style="width:7%;border:1;text-align:center;">Unit</td>
        <td style="width:10%;border:1;text-align:center;">Amount</td>
        <td style="width:13x%;border:1;text-align:center;">Total Price</td>
      </tr>
      @php $total = 0; @endphp
      @foreach($po->po_items as $i=>$ls)
        @php $totalPrice = $ls->price * $ls->order; @endphp
        <tr>
          <td style="border:1;text-align:center;">{{ ($i+1) }}</td>
          <td style="border:1;text-align:left;">{{ $ls->partSupplier->part_name }}</td>
          <td style="border:1;text-align:left;">{{ $ls->partSupplier->part_number }}</td>
          <td style="border:1;text-align:right;">{{ $ls->order }}</td>
          <td style="border:1;text-align:left;">{{ $ls->partSupplier->unit->unit_name }}</td>
          <td style="border:1;text-align:right;">{{ number_format($ls->price,0,',','.') }}</td>
          <td style="border:1;text-align:right;">{{ number_format($totalPrice,0,',','.') }}</td>
        </tr>
        @php $total = $total + $totalPrice; @endphp
      @endforeach
      <tr>
        <td colspan="7" style="border:1;text-align:left;">
          Schedule Delivery Ke RMA :
        </td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;">1.</td>
        <td style="border:0;text-align:left;" colspan="2">No PO, material ID dan Description harus dicantumkan dengan jelas</td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;font-size:13px;">Total Price</td>
        <td style="border:1;text-align:right;font-size:13px;">Rp. {{ number_format($total,0,',','.') }}</td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2">di <b>SURAT JALAN INVOICE.</b></td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;font-size:13px;">Discount</td>
        <td style="border:1;text-align:right;font-size:13px;"></td>
      </tr>
      @php 
        $ppn = (10 * $total) / 100; 
        $totalppn = $total + $ppn;
      @endphp
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;">2</td>
        <td style="border:0;text-align:left;" colspan="2">100% pengiriman tepat waktu, konfirmasikan segera jika tidak</td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;font-size:13px;">PPN 10 % </td>
        <td style="border:1;text-align:right;font-size:13px;">Rp. {{ number_format($ppn,0,',','.') }}</td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2">dapat memenuhi permintaan kami.</td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;font-size:13px;">Total</td>
        <td style="border:1;text-align:right;font-size:13px;">Rp. {{ number_format($totalppn,0,',','.') }}</td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;">3</td>
        <td style="border:0;text-align:left;" colspan="2">Order segera diproses dan konfirmasi kembali lewat Fax/ Email</td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:right;font-size:13px;"><i>Terbilang :</i></td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;">4</td>
        <td style="border:0;text-align:left;" colspan="2"><b>JUMLAH PENGIRIMAN & HARGA HARUS SESUAI DENGAN PO,</b></td>
        <td style="border:1;text-align:center;" colspan="4" rowspan="2">{{ terbilang($totalppn) }}</td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2"><b>PERUBAHAN DITERIMA APABILA ADA PERSETUJUAN TERTULIS.</b></td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;">5</td>
        <td style="border:0;text-align:left;" colspan="2">Barang dan material terkirim sesuai standard kualitas yang disetujui dengan</td>
        <td style="border:0;text-align:left;vertical-align:top;padding:0px" colspan="4" rowspan="8">
          <table style="width:100%;border:1;font-size:12px;padding-top:5px">
            <tr>
              <td style="border:1;text-align:center;">Di Buat</td>
              <td style="border:1;text-align:center;">Di Ketahui</td>
              <td style="border:1;text-align:center;">Di Setujui</td>
              <td style="border:1;text-align:center;">Di Terima</td>
            </tr>
            <tr>
              <td style="border:1;text-align:center;height:50px"></td>
              <td style="border:1;text-align:center;height:50px"></td>
              <td style="border:1;text-align:center;height:50px"></td>
              <td style="border:1;text-align:center;height:50px"></td>
            </tr>
            <tr>
              <td style="border:1;text-align:center;">{{ $po->log_po->where('status_process', '2')->first()->created_user }}</td>
              <td style="border:1;text-align:center;">{{ $po->log_po->where('status_process', '4')->first()->created_user }}</td>
              <td style="border:1;text-align:center;">{{ $po->log_po->where('status_process', '5')->first()->created_user }}</td>
              <td style="border:1;text-align:center;"></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2">melampirkan Mill Certificate / Certificate of Analisys /Technical Data Sheet</td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2">dan atau Material Safety Data Sheet dan atau Inspection Result Data </td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2">(IRD)/Quality Check Sheet.</td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;">6</td>
        <td style="border:0;text-align:left;" colspan="2">Pembayaran Giro mundur 30 hari setelah barang di terima</td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2">* Setelah semua dokumen penagihan diterima lengkap ke RMA.</td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;">7</td>
        <td style="border:0;text-align:left;" colspan="2">Tagihan/Faktur Pajak ditujukan kepada <b>PT. REKADAYA MULTI ADIPRIMA</b></td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2"><b>ALT.CIBUBUR-CILEUNGSI,CIANGSANA RAYA JL. NUSA INDAH  NO. 55,</b></td>
      </tr>
      <tr style="font-size:12px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2"><b>DS. NAGRAK,GN.PUTRI</b></td>
        <td style="border:0;text-align:center;" colspan="4"></td>
      </tr>
      <tr style="font-size:15px;">
        <td style="border:0;text-align:center;"></td>
        <td style="border:0;text-align:left;" colspan="2"><b>NPWP : 02.378.599.1.403.000</b></td>
        <td style="border:0;text-align:center;" colspan="4"></td>
      </tr>
    </table>
      
  </body>
</html>
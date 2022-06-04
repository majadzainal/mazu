<?php

namespace App\Http\Controllers\MazuProcess;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\MazuProcess\CashOut;
use App\Http\Controllers\Controller;
use App\Models\MazuProcess\GeneralLedger;
use App\Models\MazuProcess\Production;
use App\Models\MazuProcess\PurchaseOrderCustomer;
use App\Models\MazuProcess\PurchaseOrderMaterial;
use App\Models\MazuProcess\PurchaseOrderSupplier;
use App\Models\MazuProcess\SalesOrder;
use App\Models\MazuProcess\SalesOrderPaid;
use Illuminate\Support\Facades\Auth;

class GeneralLedgerController extends Controller
{

    //CASH OUT
    public function debitCashOut(CashOut $cashOut)
    {
        try {
            $now = Carbon::now();
            $gl = GeneralLedger::where('cash_out_id', $cashOut->cash_out_id)->get()->first();
            if($gl){
                $gl->update([
                    'debit'                         => $cashOut->dec_cash_out,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);
            }else{
                $gl = GeneralLedger::create([
                    'gl_date'                       => $now,
                    'debit'                         => $cashOut->dec_cash_out,
                    'cash_out_id'                   => $cashOut->cash_out_id,
                    'store_id'                      => getStoreId(),
                    'created_user'                  => Auth::User()->employee->employee_name,
                ]);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function debitCashOutDelete(CashOut $cashOut)
    {
        try {
            $gl = GeneralLedger::where('cash_out_id', $cashOut->cash_out_id)->get()->first();
            if($gl){
                // $gl->forceDelete();
                $gl->delete();
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    //PO CUSTOMER
    public function creditPoCustomer(PurchaseOrderCustomer $poCust)
    {
        try {
            $now = Carbon::now();
            $gl = GeneralLedger::where('po_customer_id', $poCust->po_customer_id)->get()->first();
            if($gl){
                $gl->update([
                    'credit'                        => $poCust->dec_paid,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);
            }else{
                $gl = GeneralLedger::create([
                    'gl_date'                       => $now,
                    'credit'                        => $poCust->dec_paid,
                    'po_customer_id'                => $poCust->po_customer_id,
                    'store_id'                      => getStoreId(),
                    'created_user'                  => Auth::User()->employee->employee_name,
                ]);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function creditPoCustomerDelete(PurchaseOrderCustomer $poCust)
    {
        try {
            $gl = GeneralLedger::where('po_customer_id', $poCust->po_customer_id)->get()->first();
            if($gl){
                $gl->forceDelete();
                // $gl->delete();
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    //PO MATERIAL BAHAN
    public function debitPoMaterial(PurchaseOrderMaterial $poMat)
    {
        try {
            $now = Carbon::now();
            $gl = GeneralLedger::where('po_material_id', $poMat->po_material_id)->get()->first();
            if($gl){
                $gl->update([
                    'debit'                         => $poMat->grand_total,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);
            }else{
                $gl = GeneralLedger::create([
                    'gl_date'                       => $now,
                    'debit'                         => $poMat->grand_total,
                    'po_material_id'                => $poMat->po_material_id,
                    'store_id'                      => getStoreId(),
                    'created_user'                  => Auth::User()->employee->employee_name,
                ]);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function debitPoMaterialDelete(PurchaseOrderMaterial $poMat)
    {
        try {
            $gl = GeneralLedger::where('po_material_id', $poMat->po_material_id)->get()->first();
            if($gl){
                $gl->forceDelete();
                // $gl->delete();
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    //PO SUPPLIER
    public function debitPoSupplier(PurchaseOrderSupplier $poSupplier)
    {
        try {
            $now = Carbon::now();
            $gl = GeneralLedger::where('po_supplier_id', $poSupplier->po_supplier_id)->get()->first();
            if($gl){
                $gl->update([
                    'debit'                         => $poSupplier->grand_total,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);
            }else{
                $gl = GeneralLedger::create([
                    'gl_date'                       => $now,
                    'debit'                         => $poSupplier->grand_total,
                    'po_supplier_id'                => $poSupplier->po_supplier_id,
                    'store_id'                      => getStoreId(),
                    'created_user'                  => Auth::User()->employee->employee_name,
                ]);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function debitPoSupplierDelete(PurchaseOrderSupplier $poSupplier)
    {
        try {
            $gl = GeneralLedger::where('po_supplier_id', $poSupplier->po_supplier_id)->get()->first();
            if($gl){
                $gl->forceDelete();
                // $gl->delete();
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    //PRODUCTION
    public function debitProduction(Production $prod)
    {
        // dd($prod);
        try {
            $now = Carbon::now();
            $gl = GeneralLedger::where('production_id', $prod->production_id)->get()->first();
            if($gl){
                $gl->update([
                    'debit'                         => $prod->grand_total,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);
            }else{
                $gl = GeneralLedger::create([
                    'gl_date'                       => $now,
                    'debit'                         => $prod->grand_total,
                    'production_id'                 => $prod->production_id,
                    'store_id'                      => getStoreId(),
                    'created_user'                  => Auth::User()->employee->employee_name,
                ]);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function debitProductionDelete(Production $prod)
    {
        try {
            $gl = GeneralLedger::where('production_id', $prod->production_id)->get()->first();
            if($gl){
                $gl->forceDelete();
                // $gl->delete();
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    //SALES ORDER
    public function creditSalesOrder(SalesOrderPaid $soPaid)
    {
        // dd($prod);
        try {
            $now = Carbon::now();
            $gl = GeneralLedger::where('sales_order_paid_id', $soPaid->sales_order_paid_id)->get()->first();
            if($gl){
                $gl->update([
                    'credit'                        => $soPaid->dec_paid,
                    'updated_user'                  => Auth::User()->employee->employee_name,
                ]);
            }else{
                $gl = GeneralLedger::create([
                    'gl_date'                       => $now,
                    'credit'                        => $soPaid->dec_paid,
                    'sales_order_paid_id'           => $soPaid->sales_order_paid_id,
                    'store_id'                      => getStoreId(),
                    'created_user'                  => Auth::User()->employee->employee_name,
                ]);
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }

    public function creditSalesOrderDelete(SalesOrderPaid $soPaid)
    {
        try {
            $gl = GeneralLedger::where('sales_order_paid_id', $soPaid->sales_order_paid_id)->get()->first();
            if($gl){
                // $gl->forceDelete();
                $gl->delete();
            }
        } catch (Exception  $e) {
            return response()->json(['status' => 'Error', 'message' => $e->getMessage()], 202);
        }
    }
}

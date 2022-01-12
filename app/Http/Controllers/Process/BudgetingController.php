<?php

namespace App\Http\Controllers\Process;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Part\PartPrice;
use App\Models\Part\PartSupplier;
use App\Models\Part\BillMaterial;
use App\Models\Process\Budgeting;
use App\Models\Process\SalesOrder;
use App\Http\Controllers\Controller;
use App\Models\Process\SalesOrderItem;
use RealRashid\SweetAlert\Facades\Alert;

class BudgetingController extends Controller
{
    public $MenuID = '05';

    public function listBudgeting(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('process.budgetingTable', [
            'MenuID'            => $this->MenuID,
        ]);
    }

    public function loadBudgeting($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $budgetingList = Budgeting::groupBy('sales_order_id', 'part_supplier_id', 'month_periode', 'year_periode')
                        ->where('month_periode', $month_periode)
                        ->where('year_periode', $year_periode)
                        ->select('sales_order_id', 'part_supplier_id', 'month_periode', 'year_periode')
                        ->selectRaw('sum(qty) as qty')
                        ->with('partSupplier', 'partSupplier.unit', 'partSupplier.part_price',  'salesOrder', 'salesOrder.so_items', 'salesOrder.so_items.partCustomer','salesOrder.so_items.partCustomer.unit', 'salesOrder.customer')
                        ->orderBy('year_periode', 'desc')
                        ->orderBy('month_periode', 'desc')
                        ->get()->all();

        return['data'=> $budgetingList];
    }

    function getBudgeting($sales_order_id){
        $budgetingList = Budgeting::groupBy('part_supplier_id')
                        ->select('part_supplier_id')
                        ->selectRaw('sum(qty) as qty')
                        ->selectRaw('sum(total_price) as total_price')
                        ->with('partSupplier', 'partSupplier.unit','partSupplier.part_price')
                        ->where('sales_order_id', $sales_order_id)
                        ->get()->all();

        return['data'=> $budgetingList];
    }

    function updateBudgeting(SalesOrder $salesOrder, SalesOrderItem $item){
        $bom = BillMaterial::where('part_customer_id', $item->part_customer_id)
                    ->where('customer_id', $salesOrder->customer_id)
                    ->with('bom_item', 'bom_item.part_supplier', 'bom_item.part_customer.bom.bom_item.part_supplier',)
                    ->get()->first();
        if($bom){
            $this->getBomAndCreateBudgeting($bom->bom_item, $salesOrder, $item->qty);
        }
    }
    function getBomAndCreateBudgeting($item, $salesOrder, $qty){
        foreach($item as $bom_item){
            if($bom_item->part_supplier){
                $amount_usage = floatval($bom_item->amount_usage) * floatval($qty);
                $this->justCreateBudgeting($salesOrder, $bom_item->part_supplier->part_supplier_id, $amount_usage);
            }

            if($bom_item->part_customer){
                // $bomItemsPartCustomer = $bom_item->part_customer->bom->bom_item;
                $bom =  BillMaterial::where('part_customer_id', $bom_item->part_customer->part_customer_id)
                                    ->with('bom_item', 'bom_item.part_supplier', 'bom_item.part_customer.bom.bom_item.part_supplier',)
                                    ->get()->first();
                // dd($bom->bom_item);
                foreach($bom->bom_item as $bomItemPartCustomer){

                    if($bomItemPartCustomer->part_supplier){
                        $amount_usage = floatval($qty) * (floatval($bom_item->amount_usage) * floatval($bomItemPartCustomer->amount_usage));
                        $this->justCreateBudgeting($salesOrder, $bomItemPartCustomer->part_supplier->part_supplier_id, $amount_usage);
                    }

                    if($bomItemPartCustomer->part_customer){
                        $this->updateBudgetingSecond($bomItemPartCustomer->part_customer, $salesOrder, floatval($qty * $bomItemPartCustomer->amount_usage));
                    }
                }

            }
        }
    }

    function updateBudgetingSecond($item, $salesOrder, $qty){
        $bom = BillMaterial::where('part_customer_id', $item->part_customer_id)
                    ->where('customer_id', $salesOrder->customer_id)
                    ->with('bom_item', 'bom_item.part_supplier', 'bom_item.part_customer.bom.bom_item.part_supplier',)
                    ->get()->first();

        $this->getBomAndCreateBudgeting($bom->bom_item, $salesOrder, $qty);
    }

    function justCreateBudgeting(SalesOrder $salesOrder, $part_supplier_id, $amount_usage){
        $budgeting_id = Uuid::uuid4()->toString();
        $price = PartPrice::where('part_supplier_id', $part_supplier_id)->where('effective_date', '<=', date("Y-m-d"))
                ->where('is_active', 1)
                ->OrderBy('effective_date', 'DESC')
                ->first();

        $total_price = floatval($amount_usage) * floatval($price->price);
        Budgeting::create([
            'budgeting_id'           => $budgeting_id,
            'sales_order_id'        => $salesOrder->sales_order_id,
            'month_periode'         => $salesOrder->month_periode,
            'year_periode'          => $salesOrder->year_periode,
            'part_supplier_id'      => $part_supplier_id,
            'qty'                   => $amount_usage,
            'price'                 => $price->price,
            'total_price'           => floatval($total_price),
        ]);
    }

    function justDeleteBudgetingBySalesOrder($sales_order_id){
        $deletedRows = Budgeting::where('sales_order_id', $sales_order_id)->get();
        foreach ($deletedRows as $ls) {
            $ls->delete();
        }
    }

    function getBudgetingForPO($supplier, $month, $year){

        $partList = PartSupplier::where('supplier_id', $supplier)->pluck('part_supplier_id');
        $budgeting = Budgeting::groupBy('part_supplier_id')
                        ->with('partSupplier', 'partSupplier.part_price_active', 'partSupplier.divisi', 'partSupplier.unit')
                        ->with('forecast', function ($query) use ($month, $year){
                            $query->groupBy('part_supplier_id', 'next_month')->selectRaw('sum(qty) as totalqty, part_supplier_id, next_month')->where('month_periode', $month)->where('year_periode', $year);
                        })
                        ->selectRaw('sum(qty) as totalqty, part_supplier_id')
                        ->whereIn('part_supplier_id', $partList)
                        ->where('month_periode', $month)
                        ->where('year_periode', $year)
                        ->get();
                        
                        
        return['data'=> $budgeting];
        
    }
}

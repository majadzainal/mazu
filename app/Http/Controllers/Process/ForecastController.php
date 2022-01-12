<?php

namespace App\Http\Controllers\Process;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Models\Part\PartPrice;
use App\Models\Part\PartSupplier;
use App\Models\Process\Forecast;
use App\Models\Part\BillMaterial;
use App\Models\Process\SalesOrder;
use App\Http\Controllers\Controller;
use App\Models\Process\SalesOrderItem;
use RealRashid\SweetAlert\Facades\Alert;

class ForecastController extends Controller
{
    public $MenuID = '051';

    public function listForecast(){

        if(!isAccess('read', $this->MenuID)){
            Alert::info('Info', errorMessage('message'));
            return view('errors.denied', [
                'MenuID' => $this->MenuID,
            ]);
        }

        return view('process.forecastTable', [
            'MenuID'            => $this->MenuID,
        ]);
    }

    public function loadForecast($month_periode, $year_periode){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }

        $forecastList = Forecast::groupBy('sales_order_id', 'part_supplier_id', 'month_periode', 'year_periode', 'next_month')
                        ->where('month_periode', $month_periode)
                        ->where('year_periode', $year_periode)
                        ->select('sales_order_id', 'part_supplier_id', 'month_periode', 'year_periode', 'next_month')
                        ->selectRaw('sum(qty) as qty')
                        ->with('partSupplier', 'partSupplier.unit', 'partSupplier.part_price',  'salesOrder', 'salesOrder.so_items', 'salesOrder.so_items.partCustomer','salesOrder.so_items.partCustomer.unit', 'salesOrder.customer')
                        ->orderBy('year_periode', 'DESC')
                        ->orderBy('month_periode', 'DESC')
                        ->orderBy('next_month', 'ASC')
                        ->get()->all();

        return['data'=> $forecastList];
    }

    function getForecast($sales_order_id){
        $dataList = Forecast::groupBy('sales_order_id', 'part_supplier_id', 'next_month')
                        ->select('sales_order_id', 'part_supplier_id', 'next_month')
                        ->selectRaw('sum(qty) as qty')
                        ->with('partSupplier', 'partSupplier.unit','partSupplier.part_price')
                        ->where('sales_order_id', $sales_order_id)
                        ->orderBy('next_month', 'ASC')
                        ->get();
        // dd($dataList);
        $forecastList = $dataList->map(function($item){
            $data['part_supplier_id'] = $item->part_supplier_id;
            $data['qty'] = $item->qty;
            $data['next_month'] = $item->next_month;
            $data['part_number'] = $item->partSupplier->part_number;
            $data['part_name'] = $item->partSupplier->part_name;
            $data['unit_name'] = $item->partSupplier->unit->unit_name;
            $data['price'] = $this->getPrice($item->part_supplier_id, $item->sales_order_id, $item->next_month);
            $data['total_price'] = $item->qty * $this->getPrice($item->part_supplier_id, $item->sales_order_id, $item->next_month);
            return $data;
        });
        // dd($partList);
        return['data'=> $forecastList];
    }

    function getPrice($part_supplier_id, $sales_order_id, $next_month){
        $so = SalesOrder::where('sales_order_id', $sales_order_id)->select('month_periode', 'year_periode')->get()->first();
        $m = $so->year_periode."-".$so->month_periode."-01";
        $dateSO = Carbon::createFromFormat('Y-m-d',  $m);
        $nextMonth = $dateSO->addMonths((int) $next_month);

        $part_price = PartPrice::where('part_supplier_id', $part_supplier_id)
                ->where('effective_date', '<=', $nextMonth)
                ->where('is_active', 1)
                ->orderBy('effective_date', 'DESC')
                ->first();

        return $part_price->price ? $part_price->price : 0;
    }

    function updateForecast(SalesOrder $salesOrder, SalesOrderItem $item){
        $bom = BillMaterial::where('part_customer_id', $item->part_customer_id)
                    ->where('customer_id', $salesOrder->customer_id)
                    ->with('bom_item', 'bom_item.part_supplier', 'bom_item.part_customer.bom.bom_item.part_supplier',)
                    ->get()->first();
        if($bom){
            $this->getBomAndCreateForecast($bom->bom_item, $salesOrder, $item->qtyM1, 1);
            $this->getBomAndCreateForecast($bom->bom_item, $salesOrder, $item->qtyM2, 2);
            $this->getBomAndCreateForecast($bom->bom_item, $salesOrder, $item->qtyM3, 3);
            $this->getBomAndCreateForecast($bom->bom_item, $salesOrder, $item->qtyM4, 4);
            $this->getBomAndCreateForecast($bom->bom_item, $salesOrder, $item->qtyM5, 5);
        }
    }
    function getBomAndCreateForecast($item, $salesOrder, $qty, $next_month){
        foreach($item as $bom_item){
            if($bom_item->part_supplier){
                $amount_usage = floatval($bom_item->amount_usage) * floatval($qty);
                $this->justCreateForecast($salesOrder, $bom_item->part_supplier->part_supplier_id, $amount_usage, $next_month);
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
                        $this->justCreateForecast($salesOrder, $bomItemPartCustomer->part_supplier->part_supplier_id, $amount_usage, $next_month);
                    }

                    if($bomItemPartCustomer->part_customer){
                        $this->updateForecastSecond($bomItemPartCustomer->part_customer, $salesOrder, floatval($qty * $bomItemPartCustomer->amount_usage), $next_month);
                    }
                }

            }
        }
    }

    function updateForecastSecond($item, $salesOrder, $qty, $next_month){
        $bom = BillMaterial::where('part_customer_id', $item->part_customer_id)
                    ->where('customer_id', $salesOrder->customer_id)
                    ->with('bom_item', 'bom_item.part_supplier', 'bom_item.part_customer.bom.bom_item.part_supplier',)
                    ->get()->first();

        $this->getBomAndCreateForecast($bom->bom_item, $salesOrder, $qty, $next_month);
    }

    function justCreateForecast(SalesOrder $salesOrder, $part_supplier_id, $amount_usage, $next_month){
        $forecast_id = Uuid::uuid4()->toString();
        Forecast::create([
            'forecast_id'           => $forecast_id,
            'sales_order_id'        => $salesOrder->sales_order_id,
            'month_periode'         => $salesOrder->month_periode,
            'next_month'            => $next_month,
            'year_periode'          => $salesOrder->year_periode,
            'part_supplier_id'      => $part_supplier_id,
            'qty'                   => $amount_usage,
        ]);
    }

    function justDeleteForecastBySalesOrder($sales_order_id){
        $deletedRows = Forecast::where('sales_order_id', $sales_order_id)->get();
        foreach ($deletedRows as $ls) {
            $ls->delete();
        }
    }

    function getForecastForPO($supplier, $month, $year){

        $partList = PartSupplier::where('supplier_id', $supplier)->pluck('part_supplier_id');
        $forecast = Forecast::groupBy('part_supplier_id', 'next_month')
                        ->selectRaw('sum(qty) as totalqty, part_supplier_id, next_month')
                        ->whereIn('part_supplier_id', $partList)
                        ->where('month_periode', $month)
                        ->where('year_periode', $year)
                        ->get();

        return['data'=> $forecast];
    }
}

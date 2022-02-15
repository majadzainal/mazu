<?php

use Carbon\Carbon;
use App\Models\Setting\Menu_Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Process\OpnameSchedule;
use App\Models\User;

function getMenu(){
    $getmenu = Menu_Role::where('role_id', Auth::user()->role)
                ->where('access', 1)
                ->with('menu', 'menu.menu_parents')
                ->get();

    return $getmenu;
}

function getStoreId(){
    $store_id = User::where('user_id', Auth::user()->user_id)
    ->pluck('store_id')->first();

    return $store_id;
}

function isAccess($crud, $menuId){

    $access = Menu_Role::where('role_id', Auth::user()->role)
            ->where('menu_id', $menuId)
            ->pluck($crud)->first();

    return $access;
}

function isOpname(){
    $opnameScheduleIsOpen = OpnameSchedule::where('is_closed', 0)
            ->where('is_active', 1)
            ->get()->first();

    $result = $opnameScheduleIsOpen ? true : false;
    return $result;
}

function isAccessforButton($crud, $menuId){

    $access = Menu_Role::where('role_id', Auth::user()->role)
            ->where('menu_id', $menuId)
            ->pluck($crud)->first();

    $isAccess = $access === 0 ? "style=display:none" : "";
    return $isAccess;
}

function errorMessage($arr){

    $data = array("message" => "You do not have access for this action",
                    "status" => 'Info',
                    "status_number" => 202,
                    );

    return $data[$arr];
}

function errorMessageOpname($arr){

    $data = array("message" => "Stock opname schedule is opened",
                    "status" => 'Info',
                    "status_number" => 202,
                    );

    return $data[$arr];
}

function getStatus(){
    $status = [
                [
                    "status_id" => 1,
                    "status_name" => "Aktif"
                ],
                [
                    "status_id" => 2,
                    "status_name" => "Tidak Aktif"
                ]
            ];


    return $status;
}

function getOpnameType(){
    $status = [
                [
                    "opname_type_id" => 1,
                    "opname_type" => "Finished Goods"
                ],
                [
                    "opname_type_id" => 2,
                    "opname_type" => "Raw Material"
                ],
                [
                    "opname_type_id" => 3,
                    "opname_type" => "WIP"
                ]
            ];


    return $status;
}

function getBusinessEntity(){
    $entity = [

                [
                    "business_identity" => "LTD"
                ],
                [
                    "business_identity" => "PT"
                ],
                [
                    "business_identity" => "CV"
                ],
                [
                    "business_identity" => "UD"
                ],
                [
                    "business_identity" => "TOKO"
                ],
                [
                    "business_identity" => "KOPERASI"
                ],
            ];


    return $entity;
}

function getMonth(){
    $month = [
                [
                    "month" => 1,
                    "month_name" => "January"
                ],
                [
                    "month" => 2,
                    "month_name" => "February"
                ],
                [
                    "month" => 3,
                    "month_name" => "March"
                ],
                [
                    "month" => 4,
                    "month_name" => "April"
                ],
                [
                    "month" => 5,
                    "month_name" => "May"
                ],
                [
                    "month" => 6,
                    "month_name" => "June"
                ],
                [
                    "month" => 7,
                    "month_name" => "July"
                ],
                [
                    "month" => 8,
                    "month_name" => "August"
                ],
                [
                    "month" => 9,
                    "month_name" => "September"
                ],
                [
                    "month" => 10,
                    "month_name" => "October"
                ],
                [
                    "month" => 11,
                    "month_name" => "November"
                ],
                [
                    "month" => 12,
                    "month_name" => "December"
                ],
            ];


    return $month;
}

function getSOType(){
    $soType = [
                [
                    "type" => 1,
                    "type_name" => "ENDORSE"
                ],
                [
                    "type" => 2,
                    "type_name" => "GIFT AWAY MEDSOS"
                ],
                [
                    "type" => 3,
                    "type_name" => "SPECIAL GIFT"
                ],
                [
                    "type" => 4,
                    "type_name" => "OUTLET"
                ],
                [
                    "type" => 5,
                    "type_name" => "EXC RESELLER"
                ],
                [
                    "type" => 6,
                    "type_name" => "OWNER"
                ],
                [
                    "type" => 7,
                    "type_name" => "CUSTOMER REGULER"
                ],
            ];


    return $soType;
}

function getYearPeriode(){
    $year = [
        [
            "year" => Carbon::now()->addYear(-1)->year,
        ],
        [
            "year" => Carbon::now()->year,
        ],
        [
            "year" => Carbon::now()->addYear(1)->year,
        ],
    ];

    return $year;
}

function getColor(){

    $data = array("1" => "primary",
                  "2" => "info",
                  "3" => "danger",
                  "4" => "warning",
                  "5" => "success",
                  "6" => "inverse",
                );

    return $data;
}

function terbilang($nilai) {
    $nilai = abs($nilai);
    $huruf = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
    $temp = "";
    if ($nilai < 12) {
        $temp = " ". $huruf[$nilai];
    } else if ($nilai <20) {
        $temp = terbilang($nilai - 10). " Belas";
    } else if ($nilai < 100) {
        $temp = terbilang($nilai/10)." Puluh". terbilang($nilai % 10);
    } else if ($nilai < 200) {
        $temp = " Seratus" . terbilang($nilai - 100);
    } else if ($nilai < 1000) {
        $temp = terbilang($nilai/100) . " Ratus" . terbilang($nilai % 100);
    } else if ($nilai < 2000) {
        $temp = " Seribu" . terbilang($nilai - 1000);
    } else if ($nilai < 1000000) {
        $temp = terbilang($nilai/1000) . " Ribu" . terbilang($nilai % 1000);
    } else if ($nilai < 1000000000) {
        $temp = terbilang($nilai/1000000) . " Juta" . terbilang($nilai % 1000000);
    } else if ($nilai < 1000000000000) {
        $temp = terbilang($nilai/1000000000) . " Milyar" . terbilang(fmod($nilai,1000000000));
    } else if ($nilai < 1000000000000000) {
        $temp = terbilang($nilai/1000000000000) . " Trilyun" . terbilang(fmod($nilai,1000000000000));
    }
    return $temp;
}

function getStatusRequestRawMat(){

    $status = array("0" => "Reject",
                    "1" => "Draft",
                    "2" => "Submited",
                    "3" => "Process",
                    "4" => "Approve",
                    "5" => "Send"
                );

    return $status;
}

function getNumberingFormType(){
    $formType = [
                [
                    "numbering_form_type" => "F_PRODUCTION"
                ],
                [
                    "numbering_form_type" => "F_PURCHASE_ORDER_CUSTOMER"
                ],
                [
                    "numbering_form_type" => "F_PURCHASE_ORDER_SUPPLIER"
                ],
                [
                    "numbering_form_type" => "F_PURCHASE_ORDER_SUPPLIER"
                ],
                [
                    "numbering_form_type" => "F_GENERATE_LABEL_PRODUCT"
                ],
                [
                    "numbering_form_type" => "F_RECEIVING_PRODUCT"
                ],
                [
                    "numbering_form_type" => "F_PURCHASE_ORDER_MATERIAL_BAHAN"
                ],
                [
                    "numbering_form_type" => "F_RECEIVING_PRODUCT_SUPPLIER"
                ],
                [
                    "numbering_form_type" => "F_DELIVERY_ORDER_OUTLET"
                ],
                [
                    "numbering_form_type" => "F_DELIVERY_ORDER_EXCLUSIVE_RESELLER"
                ],
                [
                    "numbering_form_type" => "F_SALES_ORDER"
                ],
            ];


    return $formType;
}

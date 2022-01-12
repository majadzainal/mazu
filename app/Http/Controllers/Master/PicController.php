<?php

namespace App\Http\Controllers\Master;

use Ramsey\Uuid\Uuid;
use App\Models\Master\PIC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PicController extends Controller
{
    public $MenuID = '0205';

    public function loadPic($type, $id){
        if(!isAccess('read', $this->MenuID)){
            return['data'=> ''];
        }
        $picList = DB::table('tm_pic')
                ->leftJoin('tm_pic_type', 'tm_pic_type.pic_type_id', '=', 'tm_pic.pic_type_id')
                ->select('tm_pic.pic_type_id', 'tm_pic.pic_name', 'tm_pic.pic_telephone', 'tm_pic.pic_email',
                'tm_pic_type.pic_type_name')
                ->where('tm_pic.'.$type, $id)
                ->get();
        // $picList = PIC::where($type, $id)->with('pic_type')->get();
        return['data'=> $picList];
    }

    public function addPic(PIC $pic){
        $pic_id = Uuid::uuid4()->toString();
        PIC::create([
            'pic_id'          => $pic_id,
            'supplier_id'     => $pic->supplier_id,
            'customer_id'     => $pic->customer_id,
            'pic_type_id'     => $pic->pic_type_id,
            'pic_name'        => $pic->pic_name,
            'pic_telephone'   => $pic->pic_telephone,
            'pic_email'       => $pic->pic_email,
        ]);
    }

    public function deletePic($type, $id){
        PIC::where($type, $id)->delete();
    }
}

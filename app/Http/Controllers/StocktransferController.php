<?php

namespace App\Http\Controllers;

use App\Tmaster;
use App\Spodetail;
use App\Delivery;

use App\Stock;
use App\StockItem;
use App\StockType;
use App\StockDetail;
use App\Srcvdetail;

use App\Stxfrhdr;
use App\Stxfrdetail;

use App\Vendor;
use App\PurchaseOrder;
use App\PurchaseOrderDetail;
use App\Corporation;
use App\Brand;
use App\ProductLine;
use DB;
use Validator;
use Datetime;



use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StocktransferController extends Controller {
  public function index(Request $request) {
    $status= $request->status ? $request->status : 1;
    $company = Corporation::findOrFail($request->corpID);

    $delivery_data = Delivery::limit(1000)->get();

    return view('stocktransfer/index',compact(array('delivery_data','status')));
  }

    public function autoItems(Request $request) {
      $company = Corporation::findOrFail($request->corpID);
      $status= $request->status ? $request->status : 1;

      $hdrModel = new \App\Models\Spo\Hdr;
      $hdrModel->setConnection($company->database_name);

      $items = $hdrModel->orderBy('po_no', 'DESC');

      if($status == 1) {
          $items = $items->where('served', 0);
      }else if($status == 2) {
        $items = $items->where('served', 1);
      }

      return view('stocktransfer/auto-item', [
        'items' => $items->get(),
        'corpID' => $request->corpID
      ]);
    }



    public function  tmasteOriginalDetail($id)
    {
        $tmaster_ItemCode_Distinct = Spodetail::where('po_no',$id)->distinct()->get(['ItemCode']);
        
        $tmaster_Branch_Distinct = Spodetail::where('po_no',$id)->distinct()->get(['Branch']);
        $arr = [];
        $global_data = DB::table('t_sysdata')->get()->all();
        foreach($global_data as $data){
           foreach($tmaster_Branch_Distinct as $branch)
            if($data->Branch == $branch->Branch){
                $arr[] = $data;
                break;
            }
        }
        $tmaster = Tmaster::find($id);
        $tmaster_template = $tmaster->getSpotmpl8hdrData;
        $po_no = $id;

    return view('stocktransfer/showOriginal',compact('tmaster_ItemCode_Distinct','arr','tmaster','tmaster_template','po_no'));
   
    }

    public function saveRowPO(Request $request){

        $arr = $request->changeRowArr;

        foreach ($arr as $item) {
           
            // var_dump($item['rowVal']);die;
            $qty  = $item['rowVal'];
            $itemCode = $item['itemCode'];
            $itemID = $item['itemID'];
            $branchID = $item['branchID'];
            $date = date('Y-m-d');
            
            $srcvDetail = Srcvdetail::where('item_id',$itemID)->first();
            $movementID = $srcvDetail['Movement_ID'];
            // dd($movementID);

        $stxf = Stxfrhdr::create([
               
              'Txfr_Date' => $date,
              'Txfr_To_Branch' =>$branchID,
              'Rcvd' => 1,
              'Uploaded' => 1,
              ]);
      
        $stxfdetail = Stxfrdetail::create([
          
              'item_id' => $itemID,
              'ItemCode' =>$itemCode,
              'Qty' => $qty,
              'Bal' => 0,
              'Movement_ID' => $movementID,
              
              ]);
        }
        
      return response()->json([
        'success'=> 'success'
      ]);
    }

    public function markToserved($id){

        $tmaster = Tmaster::where( 'po_no',$id)->update([
            'served'=>1
        ]);   
        return response()->json(array('msg'=>'success'), 200);   
    }

    public function create(Request $request) {
    if(!\Auth::user()->checkAccessByIdForCorp($request->corpID, 35, 'A')) {
      \Session::flash('error', "You don't have permission"); 
      return redirect("/home"); 
    }
    $company = Corporation::findOrFail($request->corpID);
    $stockModel = new \App\Stock;
    $stockModel->setConnection($company->database_name);
    $purchaseOrderModel = new \App\PurchaseOrder;
    $purchaseOrderModel->setConnection($company->database_name);
    $retailID = StockType::where('type_desc', 'Retail')->first()->inv_type;
    $typeID = [0,$retailID];

    $stockitems = StockItem::where( 'Active', 1 )->whereIn('Type', $typeID)->orderBy('ItemCode')->get();
    $vendors = Vendor::orderBy('VendorName')->get();

    $brands = Brand::all();

    $prod_lines = ProductLine::all();

    $prod_lines = $prod_lines->map(function ($prod_lines) {
      return $prod_lines->Product;
    });
    $brands = $brands->map(function ($brands) {
      return $brands->Brand;
    });
    // dd(Brand::all());
    $pos = $purchaseOrderModel->where('served', 0)->orderBy('po_no', 'desc')->get();
    return view('stocktransfer.create',
      [
        'brands' => $brands,
        'prod_lines' => $prod_lines,
        'corpID' => $request->corpID,
        'vendors' => $vendors,
        'pos' => $pos,
        'stockitems' => $stockitems
      ]
    )->with('corpID', $request->corpID);
  }

    public function store(Request $request)
    {
        //
    }

   
    public function show(Request $request, $id) {
      $company = Corporation::findOrFail($request->corpID);
      
      $hdrModel = new \App\Models\Spo\Hdr;
      $hdrModel->setConnection($company->database_name);

      $stockItem = $hdrModel->findOrFail($id);
      $branchIds = $stockItem->items->pluck('Branch');

      $branches = \App\Branch::whereIn('Branch', $branchIds);

      $itemRows = $stockItem->items()
                            ->whereIn('Branch', $branches->pluck('Branch'))
                            ->orderBy('ItemCode', 'ASC')
                            ->get()
                            ->groupBy('ItemCode');

      return view('stocktransfer/show', [
        'stockItem' => $stockItem,
        'branches' => $branches->get(),
        'itemRows' => $itemRows
      ]);
    }

    public function edit($id)
    {
        //
    }

   
    public function update(Request $request, $id)
    {
        //
    }

   
    public function destroy($id)
    {
        //
    }
}

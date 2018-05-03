<?php

namespace App\Http\Controllers;

use DB;
use App\Vendor;
use App\VendorManagement;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //retrieve input
        $suppId = $request->input('suppId');
        $branchId = $request->input('branchName');
        $mainStatus = $request->input('mainStatus');
        $vendorAccountNum = $request->input('vendorAccountNumber');
        $description = $request->input('description');
        $cycleDays = $request->input('cycleDays');
        $offsetDays = $request->input('offsetDays');
        $activeAccount = $request->input('activeAccount');
        $corpId = $request->input('corporationId');
        //create new instance
        $vendorMgm = new VendorManagement;
        $vendorMgm->supp_id = $suppId;
        $vendorMgm->corp_id = $corpId;
        $vendorMgm->acct_num = $vendorAccountNum;
        $vendorMgm->nx_branch = $mainStatus == "on" ? -1 : $branchId;
        $vendorMgm->description = $description;
        $vendorMgm->days_offset = $cycleDays;
        $vendorMgm->firstday_offset = $offsetDays;
        $vendorMgm->active = $activeAccount == "on" ? 1 : 0;
        //save new instance
        $success = $vendorMgm->save();

        if($success){
            \Session::flash('success', "Vendor account created successfully");
            return redirect()->route('vendors.show', [
              $suppId,
              'corpID' => $request->corpID,
              'active' => $request->active,
              'main' => $request->main
            ]);
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('vendors.show', $suppId);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VendorManagement  $vendorManagement
     * @return \Illuminate\Http\Response
     */
    public function show(VendorManagement $vendorManagement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VendorManagement  $vendorManagement
     * @return \Illuminate\Http\Response
     */
    public function edit(VendorManagement $vendorManagement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VendorManagement  $vendorManagement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VendorManagement $vendorManagement)
    {
        //retrieve input
        $suppId = $request->input('suppId');
        $branchId = $request->input('editBranchName');
        $mainStatus = $request->input('editMainStatus');
        $vendorAccountNum = $request->input('editVendorAccountNumber');
        $description = $request->input('editDescription');
        $cycleDays = $request->input('editCycleDays');
        $offsetDays = $request->input('editOffsetDays');
        $activeAccount = $request->input('editActiveAccount');
        $corp_id = $request->input('editCorporationId');


        //create new instance
        $success = $vendorManagement->update([
            'supp_id' => $suppId,
            'acct_num' => $vendorAccountNum,
            'nx_branch' => $mainStatus == "on" ? -1 : $branchId,
            'description' => $description,
            'corp_id' => $corp_id,
            'days_offset' => $cycleDays,
            'firstday_offset' => $offsetDays,
            'active' => $activeAccount == "on" ? 1 : 0
        ]);

        if($activeAccount == "on")
        {
            $check_active = 1;
        }
        else
        {
            $check_active = 0;
        }

        if($success){
            \Session::flash('success', "Vendor account updated successfully");
            return redirect()->route('vendors.show', [
              $suppId,
              'corpID' => $request->corpID,
              'active' => $request->active,
              'main' => $request->main
            ]);
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('vendors.show', [
          $suppId,
          'corpID' => $request->corpID,
          'active' => $request->active,
          'main' => $request->main
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VendorManagement  $vendorManagement
     * @return \Illuminate\Http\Response
     */
    public function destroy(VendorManagement $vendorManagement, Request $request)
    {
        if(!\Auth::user()->checkAccessById(29, "D"))
        {
            \Session::flash('error', "You don't have permission");
            return redirect("/home");
        }


        $success = $vendorManagement->delete();
        if($success){
            \Session::flash('success', "Vendor deleted successfully");
            return redirect()->route('vendors.show', [
              $vendorManagement->supp_id,
              'corpID' => $request->corpID,
              'active' => $request->active,
              'main' => $request->main
            ]);
        }
        \Session::flash('error', "Something went wrong!");
        return redirect()->route('vendors.show', [
          $vendorManagement->supp_id,
          'corpID' => $request->corpID,
          'active' => $request->active,
          'main' => $request->main
        ]);
    }

    public function getVendorAccount(Request $request){
        $vendorAcct = VendorManagement::where('acct_id', $request->input('id'))->first();

        return response()->json($vendorAcct, 200);
    }
}

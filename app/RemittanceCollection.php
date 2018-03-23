<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Branch;

class RemittanceCollection extends Model
{
  public $timestamps = false;
  protected $table = "remittance_collections";
  protected $primaryKey = "ID";

  protected $fillable = [
    'CreatedAt', 'TellerID', 'Status', 'Subtotal', 'UpdatedBy', 'UpdatedAt'
  ];
  
  protected $dates = [
    'CreatedAt',
    'UpdatedAt'
  ];

  public function details() {
    return $this->hasMany(\App\RemittanceDetail::class, "RemittanceCollectionID", "ID");
  }

  public function user() {
    return $this->belongsTo(\App\User::class, "TellerID", "UserID");
  }

  public function updatedBy() {
    return $this->belongsTo(\App\User::class, "UpdatedBy", "UserID");
  }

  public function detail($groupId, $branchId) {
    $detail = $this->details()
                   ->where('Group', '=', $groupId)
                   ->where('Branch', '=', $branchId)
                   ->first();
    if(!$detail) {
      $detail = new \App\RemittanceDetail;
      $detail->Group = $groupId;
      $detail->Branch = $branchId;
    }
    return $detail;
  }

  public function getStartCRR($groupId, $branchId, $corpID) {
    $company = \App\Company::findOrFail($corpID);
    $startCRR = 1;

    $detail = $this->details()->where('Group', '=', $groupId)
                              ->where('Branch', '=', $branchId)
                              ->first();
    if($detail) {
      $startCRR = $detail->Start_CRR;
    }else {
      if($company->corp_type == 'ICAFE') {
        $remittanceModel = new \App\Remittance;
      }else {
        $remittanceModel = new \App\KRemittance;
      }

      $remittanceModel = $remittanceModel->setConnection($company->database_name);
      $remittance = $remittanceModel->where('Branch', '=', $branchId)
                                    ->where('Sales_Checked', '=', 0)
                                    ->orderBy('Shift_ID', 'ASC')
                                    ->first();
      if($remittance) {
        $startCRR = $remittance->Shift_ID;
      }else {
        $remittance = $remittanceModel->where('Branch', '=', $branchId)
                                      ->where('Sales_Checked', '=', 1)
                                      ->orderBy('Shift_ID', 'DESC')
                                      ->first();
        if($remittance) {
          $startCRR = $remittance->Shift_ID + 1;
        }
      }
    }
    return intval($startCRR);
  }

  protected static function boot() {
    parent::boot();

    static::deleting(function($collection) {
      $collection->details()->delete();
    });
  }
}

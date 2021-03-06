<?php

namespace App\Models\Stxfr;

use Illuminate\Database\Eloquent\Model;

class Hdr extends Model
{
    public $timestamps = false;
    protected $table = "s_txfr_hdr";
    protected $primaryKey = "Txfr_ID";

    protected $fillable = [
        'Txfr_Date', 'Txfr_To_Branch', 'Rcvd',
        'DateRcvd', 'Shift_ID', 'Uploaded'
    ];


    protected $dates = [
        'Txfr_Date'
    ];


    public function branch()
    {
        return $this->belongsTo(\App\Branch::class, 'Txfr_To_Branch', 'Branch');
    }

    public function details()
    {
        return $this->hasMany(Detail::class, 'Txfr_ID', 'Txfr_ID');
    }

    public function shift()
    {
        if ($this->getConnectionName() == 'mysql2') {
            return $this->belongsTo(\App\Models\T\Shift::class, 'Shift_ID', 'Shift_ID');
        } else {
            return $this->belongsTo(\App\KShift::class, 'Shift_ID', 'Shift_ID');
        }
        
    }
}

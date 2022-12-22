<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CubicRate extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "value"
          ];
      
          static function getAllCubicRates()
      {
          $results = DB::table('cubicrate')
              ->get();
  
          return $results;
      }
          protected $primaryKey = 'id';
}

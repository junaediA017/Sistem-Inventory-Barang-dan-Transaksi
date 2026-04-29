<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */

    protected $table = 'cabang';
    public $timestamps = false;

    public static function getAllData()
    {
        return self::select(
            'id_cabang',
            'keterangan_cabang',
        )->get();
    }
}

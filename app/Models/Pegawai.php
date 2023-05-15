<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pegawai extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = "pegawai";

    public function lembur()
    {
        return $this->hasMany(Lembur::class);
    }

    public function cuti()
    {
        return $this->hasMany(Cuti::class);
    }

    public function penggajian()
    {
        return $this->hasMany(Penggajian::class);
    }

    public function golongan()
    {
        return $this->hasOne(Golongan::class);
    }
}

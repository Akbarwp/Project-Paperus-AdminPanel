<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finishing extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = "finishing";

    public function produk()
    {
        return $this->belongsToMany(Produk::class);
    }
}

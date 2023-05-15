<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Restock extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = "restock";

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}

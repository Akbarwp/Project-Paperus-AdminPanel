<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDetail extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = "sales_detail";

    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Produk extends Model
{
    use HasFactory, HasSlug;

    protected $guarded = [];
    public $table = "produk";

    public function kategori()
    {
        return $this->belongsToMany(Kategori::class);
    }

    public function finishing()
    {
        return $this->belongsToMany(Finishing::class);
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }

    public function salesdetail()
    {
        return $this->hasMany(SalesDetail::class);
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('nama')
            ->saveSlugsTo('slug');
    }
    public function getRouteKeyName()
    {
        return 'slug';
    }
}

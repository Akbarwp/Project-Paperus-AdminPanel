<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIdentitas extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $table = "user_identitas";

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

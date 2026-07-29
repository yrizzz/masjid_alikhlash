<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageView extends Model
{
    protected $guarded = [];
    protected $casts = ['date' => 'date'];
}

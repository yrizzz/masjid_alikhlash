<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CampaignUpdate extends Model
{
    protected $guarded = [];

    public function campaign() { return $this->belongsTo(Campaign::class); }
}

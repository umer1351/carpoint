<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageListingItem extends Model
{
    protected $fillable = [
        'name',
        'detail',
        'banner',
        'status',
        'seo_title',
        'seo_meta_description'
    ];

}

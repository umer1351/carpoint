<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['garage_id', 'name', 'price'];

    public function garage()
    {
        return $this->belongsTo(Garage::class);
    }
}

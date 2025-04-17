<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinanceInquiry extends Model
{
    use HasFactory;

    protected $fillable = ['buyer_id', 'listing_id', 'term', 'down_payment', 'message', 'status'];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class, 'listing_id');
    }
}

<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'country',
        'address',
        'state',
        'city',
        'zip',
        'website',
        'facebook',
        'twitter',
        'linkedin',
        'instagram',
        'pinterest',
        'youtube',
        'photo',
        'banner',
        'password',
        'user_role',
        'token',
        'status'
    ];

    public function rPurchasePackage()
    {
        return $this->hasMany(PackagePurchase::class, 'user_id', 'id');
    }

    public function isSeller()
    {
        return $this->user_role === 'seller';
    }

    public function isBuyer()
    {
        return $this->user_role === 'buyer';
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges_pivot')->withTimestamps();
    }
}

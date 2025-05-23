<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
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
    public function listings()
    {
        return $this->hasMany(Listing::class, 'user_id', 'id');
    }

}

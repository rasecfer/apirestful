<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const PRODUCTO_DISPONIBLE = '1';
    const PRODUCTO_NO_DISPONIBLE = '0';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status',
        'image',
        'seller_id'
    ];

    public function esDisponible()
    {
        return $this->status == Product::PRODUCTO_DISPONIBLE;
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }
}

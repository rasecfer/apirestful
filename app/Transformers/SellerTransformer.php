<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Seller;

class SellerTransformer extends TransformerAbstract
{
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Seller $seller)
    {
        return [
            'identificador' => (int)$seller->id,
            'nombre' => $seller->name,
            'correoe' => $seller->email,
            'verificado' => (int)$seller->verified,
            'fechaCreacion' => (string)$seller->created_at,
            'fechaActualizacion' => (string)$seller->updated_at,
            'fechaEliminacion' => isset($seller->deleted_at) ? (string)$seller->deleted_at : null,
            'links' => [
                [
                    'rel' => 'self',
                    'href' => route('sellers.show', $seller->id)
                ],
                [
                    'rel' => 'seller.categories',
                    'href' => route('sellers.categories.index', $seller->id)
                ],
                [
                    'rel' => 'seller.products',
                    'href' => route('sellers.products.index', $seller->id)
                ],
                [
                    'rel' => 'seller.buyers',
                    'href' => route('sellers.buyers.index', $seller->id)
                ],
                [
                    'rel' => 'seller.transactions',
                    'href' => route('sellers.transactions.index', $seller->id)
                ],
                [
                    'rel' => 'user',
                    'href' => route('users.show', $seller->id)
                ]
                
            ]
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identificador' => 'id',
            'nombre' => 'name',
            'correoe' => 'email',
            'verificado' => 'verified',
            'fechaCreacion' => 'created_at',
            'fechaActualizacion' => 'updated_at',
            'fechaEliminacion' => 'deleted_at'
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}

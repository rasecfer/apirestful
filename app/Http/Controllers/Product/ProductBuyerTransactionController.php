<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Product;
use App\User;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use App\Transaction;

class ProductBuyerTransactionController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1'
        ];

        $this->validate($request, $rules);

        if($buyer->id == $product->seller_id){
            return $this->errorResponse('El comprador y el vendedor no pueden ser el mismo', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if(!$buyer->esVerificado()){
            return $this->errorResponse('El comprador no ha sido verificado', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // dd($product->seller);
        if(!$product->seller->esVerificado()){
            return $this->errorResponse('El vendedor no ha sido verificado', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if(!$product->esDisponible()){
            return $this->errorResponse('El producto no está disponible', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if($product->quantity < $request->quantity){
            return $this->errorResponse('La cantidad de producto no es suficiente', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return DB::transaction(function() use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id
            ]);

            return $this->showOne($transaction, Response::HTTP_CREATED);
        });
    }

}

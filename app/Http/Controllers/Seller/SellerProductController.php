<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Seller;
use Symfony\Component\HttpFoundation\Response;
use App\User;
use App\Product;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $seller = Seller::findOrFail($id);

        $products = $seller->products;

        //dd($products);

        return $this->showAll($products, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image'
        ];

        $this->validate($request, $rules);

        $data = $request->all();

        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = 'IMG1.jpg';
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product, Response::HTTP_CREATED);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in: '. Product::PRODUCTO_DISPONIBLE . ',' . Product::PRODUCTO_NO_DISPONIBLE,
            'image' => 'image'
        ];

        $this->validate($request, $rules);

        $this->verifySeller($seller, $product);

        $product->fill($request->only([
            'name',
            'description',
            'quantity'
        ]));

        if($request->has('status')){
            $product->status = $request->status;

            if($product->esDisponible() && $product->categories()->count() == 0){
                return $this->errorResponse('Un producto activo debe tener al menos una categoría', Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        if($product->isClean()){
            return $this->errorResponse('Se debe especificar al menos un valor diferente para actualizar', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $product->save();

        return $this->showOne($product, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verifySeller($seller, $product);

        $product->delete();
        return $this->showOne($product, Response::HTTP_OK);
    }

    protected function verifySeller(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id){
            throw new HttpException(Response::HTTP_UNPROCESSABLE_ENTITY, 'El vendedor especificado no es el vendedor real del producto');
        }
    }
}

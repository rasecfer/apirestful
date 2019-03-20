<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Product;
use Symfony\Component\HttpFoundation\Response;
use App\Category;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $product = Product::findOrFail($id);

        $categories = $product->categories;

        return $this->showAll($categories, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        if(!$product->categories()->find($category->id)){
            return $this->errorResponse('No existe la categoría a eliminar', Response::HTTP_NOT_FOUND);
        }

        $product->categories()->detach([$category->id]);

        return $this->showAll($product->categories, Response::HTTP_OK);
    }
}

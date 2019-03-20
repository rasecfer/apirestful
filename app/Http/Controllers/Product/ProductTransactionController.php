<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Product;
use Symfony\Component\HttpFoundation\Response;

class ProductTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $product = Product::findOrFail($id);

        $transactions = $product->transactions;

        return $this->showAll($transactions, Response::HTTP_OK);
    }

}

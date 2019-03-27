<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Buyer;
use Symfony\Component\HttpFoundation\Response;

class BuyerProductController extends ApiController
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $buyer = Buyer::findOrFail($id);

        $products = $buyer
            ->transactions()
            ->with('product')
            ->get()
            ->pluck('product');

        // dd($products);

        return $this->showAll($products, Response::HTTP_OK);
    }

}

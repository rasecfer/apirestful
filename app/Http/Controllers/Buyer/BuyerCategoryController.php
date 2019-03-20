<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Buyer;
use Symfony\Component\HttpFoundation\Response;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $buyer = Buyer::findOrFail($id);

        $categories = $buyer
            ->transactions()
            ->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse()
            ->unique('id')
            ->values();

        //dd($categories);

        return $this->showAll($categories, Response::HTTP_OK);
    }

}

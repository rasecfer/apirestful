<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $category = Category::findOrFail($id);

        $buyers = $category
            ->products()
            ->whereHas('transactions')
            ->with('transactions.buyer')
            ->get()
            ->pluck('transactions')
            ->collapse()
            ->pluck('buyer')
            ->unique('id')
            ->values();


        return $this->showAll($buyers, Response::HTTP_OK);
    }

}

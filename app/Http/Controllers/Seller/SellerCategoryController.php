<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Seller;
use Symfony\Component\HttpFoundation\Response;

class SellerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $seller = Seller::findOrFail($id);

        $categories = $seller
            ->products()
            ->with('categories')
            ->get()
            ->pluck('categories')
            ->collapse()
            ->unique('id')
            ->values();

        //dd($categories);

        return $this->showAll($categories, Response::HTTP_OK);
    }

}

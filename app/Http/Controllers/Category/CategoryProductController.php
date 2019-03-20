<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Category;
use Symfony\Component\HttpFoundation\Response;

class CategoryProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $category = Category::findOrFail($id);

        $products = $category->products;

        return $this->showAll($products, Response::HTTP_OK);
    }

}

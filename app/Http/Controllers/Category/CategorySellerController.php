<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Category;
use Symfony\Component\HttpFoundation\Response;

class CategorySellerController extends ApiController
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
        $category = Category::findOrFail($id);

        $sellers = $category
            ->products()
            ->with('seller')
            ->get()
            ->pluck('seller')
            ->unique('id')
            ->values();

        

        return $this->showAll($sellers, Response::HTTP_OK);
    }

}

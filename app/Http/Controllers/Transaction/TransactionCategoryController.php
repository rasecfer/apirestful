<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transaction;
use Symfony\Component\HttpFoundation\Response;

class TransactionCategoryController extends ApiController
{
    public function __construct()
    {
        $this->middleware('client.credentials')->only(['index']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $transaction = Transaction::findOrFail($id);

        $categories = $transaction->product->categories;

        return $this->showAll($categories, Response::HTTP_OK);
    }

}

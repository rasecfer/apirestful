<?php

namespace App\Http\Controllers\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Transaction;
use Symfony\Component\HttpFoundation\Response;

class TransactionSellerController extends ApiController
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
        $transaction = Transaction::findOrFail($id);

        $seller = $transaction->product->seller;

        return $this->showOne($seller, Response::HTTP_OK);
    }

}

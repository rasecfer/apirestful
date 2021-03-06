<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Buyer;
use Symfony\Component\HttpFoundation\Response;

class BuyerTransactionController extends ApiController
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

        $transactions = $buyer->transactions;

        return $this->showAll($transactions, Response::HTTP_OK);
    }

}

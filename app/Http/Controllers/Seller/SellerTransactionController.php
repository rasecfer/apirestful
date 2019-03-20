<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Seller;
use Symfony\Component\HttpFoundation\Response;

class SellerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $seller = Seller::findOrFail($id);

        $transactions = $seller
            ->products()
            ->whereHas('transactions')
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();

        //dd($transactions);

        return $this->showAll($transactions, Response::HTTP_OK);
    }

}

<?php

namespace App\Http\Controllers\Buyer;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
use App\Buyer;
use Symfony\Component\HttpFoundation\Response;

class BuyerSellerController extends ApiController
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

        /**
         * Con "with" Se puede acceder a las relaciones
         * 
         * Con "pluck" Se selecciona el nodo o registro que se desea obtener
         * 
         * Con "unique" Se indica que no se quieren registros repetidos por el campo id, pero
         * la colección queda con valores nulos, que son los registros repetidos, por ello
         * se utiliza "values" que elimina esos registros nulos
         */

        $sellers = $buyer
            ->transactions()
            ->with('product.seller')
            ->get()
            ->pluck('product.seller')
            ->unique('id')
            ->values();

        // dd($sellers);

        return $this->showAll($sellers, Response::HTTP_OK);
    }

}

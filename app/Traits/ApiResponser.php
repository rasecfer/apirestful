<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;


trait ApiResponser
{
  private function successResponse($data, $code)
  {
    return response()->json($data, $code);
  }

  protected function errorResponse($message, $code)
  {
    return response()->json(['error' => $message, 'code' => $code], $code);
  }

  protected function showAll(Collection $collection, $code)
  {
    return $this->successResponse($collection, $code);
  }

  protected function showOne(Model $instance, $code)
  {
    return $this->successResponse($instance, $code);
  }
}
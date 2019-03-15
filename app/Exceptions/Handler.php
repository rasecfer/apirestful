<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use App\Traits\ApiResponser;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if($exception instanceOf ValidationException){
            return $this->convertExceptionToArray($exception, $request);
        }

        if($exception instanceOf ModelNotFoundException){
            $modelo = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse("No se ha encontrado instancia de {$modelo} con el id especificado", 404);
        }

        if($exception instanceOf AuthenticationException){
            return $this->unauthenticated($request, $exception);
        }

        if($exception instanceOf AuthorizationException){
            return $this->errorResponse('No posee permisos para ejecutar esta acción', 403);
        }

        if($exception instanceOf NotFoundHttpException){
            return $this->errorResponse('No se encontró el recurso especificado', 404);
        }

        if($exception instanceOf MethodNotAllowedHttpException){
            return $this->errorResponse('No está permitido el método especificado', 405);
        }

        if($exception instanceOf HttpException){
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if($exception instanceOf QueryException){
            $codigo = $exception->errorInfo[1];
            if($codigo == 1451){

            }
            return $this->errorResponse('No se puede eliminar de forma permanente el recurso porque tiene relaciones existentes', 409);
        }

        if(config('app.debug')){
            return parent::render($request, $exception);
        }
        return $this->errorResponse('Error inesperado', 500);
        
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('No autenticado.', 401);
    }

    protected function convertValidationExceptionToResponse(\Illuminate\Validation\ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return $this->errorResponse($errors, 422);
    }
}

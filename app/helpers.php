<?php

use App\Exceptions\ApiExceptionHandler;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;

if (!function_exists('api')) {
    function api(): ApiExceptionHandler
    {
        return app('api.exception');
    }
}
function dbTransaction(callable $callback)
{
    try {
        return DB::transaction($callback);
    } catch (HttpException $e) {
        throw $e;
    } catch (\Exception $e) {
        throw new HttpException(500, $e->getMessage());
    }
}

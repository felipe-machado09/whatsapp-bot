<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Whatsapp\WhatsappController;
use Illuminate\Routing\Router;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$router = app(Router::class);

$router->middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

$router->prefix('v1/teste')->group( function () use ($router) {
    $router->get('/', function () {
        return "API FUNCIONANDO!";
    });

});

$router->prefix('v1/whatsapp')
->name('whatsapp.')
->group(function () use ($router) {
    $router->get('/', [WhatsappController::class, 'index']);
});

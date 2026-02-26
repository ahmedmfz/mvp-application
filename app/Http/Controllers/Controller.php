<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'People Management API',
    version: '1.0.0',
    description: 'Technical assessment API documentation'
)]
#[OA\Server(
    url: '/api',
    description: 'API Base URL'
)]
#[OA\SecurityScheme(
    securityScheme: 'api_key',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
abstract class Controller
{
    //
}
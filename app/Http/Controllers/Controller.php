<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Fund Request API",
 *      description="RESTful API Endpoints for iPF-OS (Fund Request Module) ",
 *      @OA\Contact(
 *          email="mohamedmfaume700@gmail.com"
 *      )
 *
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Development API Server"
 * )

 *
 *
 */

class Controller extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     @OA\Response(response="200", description="An example endpoint")
     * )
     */
    public function getUsers()
    {
    }

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

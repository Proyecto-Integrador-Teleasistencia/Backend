<?php

namespace App\Http\Controllers\Api;
 use App\Http\Controllers\Controller as Controller;
class BaseController extends Controller
{
    /**
 * @OA\Info(
 *    title="Futbol Femeni API Documentation",
 *    version="1.0.0",
 * )
 * @OA\PathItem(path="/api")
**  
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */

    /**
     * success response method.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendResponse($result, $message, $code = 200)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }
    /**
     * return error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendError($error, $errorMessages = [], $code = 200)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['info'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
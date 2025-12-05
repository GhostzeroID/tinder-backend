<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *     title="Tinder Backend API",
 *     version="1.0.0",
 *     description="API Documentation untuk Tinder Backend Application",
 *     @OA\Contact(
 *         email="admin@tinder-backend.com"
 *     )
 * )
 * 
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * 
 * @OA\Tag(
 *     name="People",
 *     description="Endpoints untuk mengelola data orang"
 * )
 * 
 * @OA\Tag(
 *     name="Likes",
 *     description="Endpoints untuk mengelola likes dan dislikes"
 * )
 */
abstract class Controller
{
    //
}

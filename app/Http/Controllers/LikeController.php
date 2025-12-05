<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/like",
     *     tags={"Likes"},
     *     summary="Like or dislike a person",
     *     description="Endpoint untuk memberikan like atau dislike kepada seseorang",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Like")
     *     ),
     *     @OA\Response(
     *         response=200, 
     *         description="Success",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success")
     *         )
     *     )
     * )
     */
    public function like(Request $request)
    {
        $data = $request->validate([
            'person_id' => 'required|exists:people,id',
            'target_id' => 'required|exists:people,id',
            'type' => 'required|in:like,dislike'
        ]);

        Like::create($data);

        return response()->json(['status' => 'success']);
    }

    /**
     * @OA\Get(
     *     path="/api/liked/{personId}",
     *     tags={"Likes"},
     *     summary="Get list of people that a person liked",
     *     description="Mendapatkan daftar orang-orang yang di-like oleh seseorang",
     *     @OA\Parameter(
     *         name="personId",
     *         in="path",
     *         description="ID of the person",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Person"))
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Person not found"
     *     )
     * )
     */
    public function likedPeople($personId)
    {
        $liked = Like::where('person_id', $personId)
            ->where('type','like')
            ->with('target')
            ->get()
            ->map(fn($like) => $like->target);

        return response()->json($liked);
    }
}

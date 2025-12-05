<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Like;

/**
 * @OA\Schema(
 *     schema="Person",
 *     type="object",
 *     title="Person",
 *     description="Model untuk data orang",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="age", type="integer", example=25),
 *     @OA\Property(property="pictures", type="array", @OA\Items(type="string"), example={"photo1.jpg", "photo2.jpg"}),
 *     @OA\Property(property="location", type="string", example="Jakarta"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Person extends Model
{
    protected $fillable = ['name','age','pictures','location'];
    protected $casts = ['pictures' => 'array'];

    public function likesGiven()
    {
        return $this->hasMany(Like::class, 'person_id');
    }

    public function likesReceived()
    {
        return $this->hasMany(Like::class, 'target_id');
    }
}

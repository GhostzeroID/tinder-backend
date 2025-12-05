<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Person;

/**
 * @OA\Schema(
 *     schema="Like",
 *     type="object",
 *     title="Like",
 *     description="Model untuk data like/dislike",
 *     required={"person_id", "target_id", "type"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="person_id", type="integer", example=1, description="ID orang yang memberikan like/dislike"),
 *     @OA\Property(property="target_id", type="integer", example=2, description="ID orang yang menerima like/dislike"),
 *     @OA\Property(property="type", type="string", enum={"like", "dislike"}, example="like"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class Like extends Model
{
    protected $fillable = ['person_id','target_id','type'];

    public function person()
    {
        return $this->belongsTo(Person::class,'person_id');
    }

    public function target()
    {
        return $this->belongsTo(Person::class,'target_id');
    }
}

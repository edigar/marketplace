<?php

namespace App\Models;

/**
 * Class Product
 * @package App\Models
 * @OA\Schema(
 *     schema="ProductRequest",
 *     type="object",
 *     title="ProductRequest",
 *     required={"description", "price"},
 *     properties={
 *         @OA\Property(property="description", type="string"),
 *         @OA\Property(property="price", type="number")
 *     }
 * )
 *
 * @OA\Schema(
 *     schema="ProductResponse",
 *     type="object",
 *     title="ProductResponse",
 *     properties={
 *         @OA\Property(property="id", type="string"),
 *         @OA\Property(property="description", type="string"),
 *         @OA\Property(property="price", type="number"),
 *         @OA\Property(property="updated_at", type="string"),
 *         @OA\Property(property="created_at", type="string"),
 *     }
 * )
 */
class Product extends ModelUuid
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'description', 'price',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'id_movie';

    protected $fillable = [
        'name',
        'duration',
    ];

    public static $rules = [
        'name' => 'required|string',
        'duration' => 'required|integer',
        'id_hall' => 'sometimes|exists:halls,id_hall|integer',
    ];

    public static $rulesUpdate = [
        'name' => 'sometimes|string',
        'duration' => 'sometimes|integer',
        'id_hall' => 'sometimes|exists:halls,id_hall|integer',
    ];

    public function movieHalls()
    {
        return $this->belongsToMany(MovieHall::class, 'movie_halls', 'id_movie', 'id_hall')
            ->withPivot('date_published', 'date_finished');
    }
}

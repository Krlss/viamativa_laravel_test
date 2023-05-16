<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'id_hall';

    protected $fillable = [
        'name',
        'status',
    ];

    public static $rules = [
        'name' => 'required|string',
        'status' => 'required|integer',
        'id_movie' => 'sometimes|exists:movies,id_movie|integer',
    ];

    public static $rulesUpdate = [
        'name' => 'sometimes|string',
        'status' => 'sometimes|integer',
        'id_movie' => 'sometimes|exists:movies,id_movie|integer',
    ];

    public function movies()
    {
        return $this->belongsToMany(Movie::class, 'movie_halls', 'id_hall', 'id_movie')
            ->withPivot('date_published', 'date_finished');
    }
}

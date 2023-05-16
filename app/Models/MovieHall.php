<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MovieHall extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_movie_hall';

    protected $fillable = [
        'date_published',
        'date_finished',
        'id_movie',
        'id_hall',
    ];

    public function movie()
    {
        return $this->belongsTo(Movie::class, 'id_movie');
    }

    public function theater()
    {
        return $this->belongsTo(Hall::class, 'id_hall');
    }
}

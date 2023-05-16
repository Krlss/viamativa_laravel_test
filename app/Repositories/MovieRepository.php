<?php

namespace App\Repositories;

use App\Models\Movie;
use App\Interfaces\IMovieRepo;
use App\Models\MovieHall;

class MovieRepository implements IMovieRepo
{
    function getAllMovies()
    {
        return Movie::paginate(25);
    }

    function getMovieById($id)
    {
        return Movie::find($id);
    }

    function createMovie($data)
    {
        return Movie::create($data);
    }

    function updateMovie($data, $movie)
    {
        $movie->update($data);
        return $movie;
    }

    function deleteMovie($movie)
    {
        $movie->delete();
        return $movie;
    }

    function findByNameAndId_hall($name, $id_hall)
    {
        return Movie::join('movie_halls', 'movies.id_movie', '=', 'movie_halls.id_movie')
            ->where('movies.name', $name)
            ->where('movie_halls.id_hall', $id_hall)
            ->first();
    }

    function countMoviesByDatePublished($date_published)
    {
        return Movie::join('movie_halls', 'movies.id_movie', '=', 'movie_halls.id_movie')
            ->where('movie_halls.date_published', 'LIKE',  $date_published . '%')
            ->count();
    }

    function findByName($name)
    {
        return Movie::where('name', $name)->first();
    }
}

<?php

namespace App\Interfaces;


interface IMovieRepo
{
    public function getAllMovies();
    public function getMovieById($id);
    public function createMovie($movie);
    public function updateMovie($movie, $id);
    public function deleteMovie($id);

    public function findByNameAndId_hall($name, $id_hall);
    public function countMoviesByDatePublished($date_published);

    public function findByName($name);
}

<?php

namespace App\Repositories;

use App\Models\Hall;
use App\Interfaces\IHallRepo;

class HallRepository implements IHallRepo
{
    function getAllHalls()
    {
        return Hall::paginate(25);
    }

    function getHallById($id)
    {
        return Hall::find($id);
    }

    function createHall($data)
    {
        return Hall::create($data);
    }

    function updateHall($data, $hall)
    {
        $hall->update($data);
        return $hall;
    }

    function deleteHall($hall)
    {
        $hall->delete();
        return $hall;
    }

    function getReviewHallByName($name)
    {
        return Hall::join('movie_halls', 'halls.id_hall', '=', 'movie_halls.id_hall')
            ->where('halls.name', $name)
            ->count();
    }

    function getHallByName($name)
    {
        return Hall::where('name', $name)->first();
    }
}

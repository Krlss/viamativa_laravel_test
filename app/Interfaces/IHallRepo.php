<?php

namespace App\Interfaces;


interface IHallRepo
{
    public function getAllHalls();
    public function getHallById($id);
    public function createHall($hall);
    public function updateHall($hall, $id);
    public function deleteHall($id);

    public function getReviewHallByName($name);
    public function getHallByName($name);
}

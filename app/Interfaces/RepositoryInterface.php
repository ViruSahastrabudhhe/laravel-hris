<?php

namespace App\Interfaces;

interface RepositoryInterface
{
    public function getAll();
    public function getById(int $id);
    public function save(array $data);
    public function deleteById(int $id);
    public function update(int $id, array $data);
    public function exists(int $id);
}

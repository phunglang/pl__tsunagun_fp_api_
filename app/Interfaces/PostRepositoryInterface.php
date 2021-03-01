<?php

namespace App\Interfaces;

interface PostRepositoryInterface
{
    public function list(array $dataRequest, $id = null);
    public function find($id);
    public function getPosts();
}
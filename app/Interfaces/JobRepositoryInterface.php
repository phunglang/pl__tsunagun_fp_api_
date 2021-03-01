<?php

namespace App\Interfaces;

interface JobRepositoryInterface
{
    public function list(array $dataRequest, $id = null);
    public function find($id);
    public function detail($id);
    public function getRelated($userId, $id);
    public function getJobs($id = null);
}
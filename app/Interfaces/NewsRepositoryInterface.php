<?php

namespace App\Interfaces;

interface NewsRepositoryInterface
{
    public function list(array $dataRequest);
}
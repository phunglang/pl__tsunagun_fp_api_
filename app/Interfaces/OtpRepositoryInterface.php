<?php

namespace App\Interfaces;

interface OtpRepositoryInterface
{
    public function checkUserComfirmOTP(array $optons);
}
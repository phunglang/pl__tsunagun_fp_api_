<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function checkUserByCredentials(array $credentials);
    public function infoProfile();
    public function detailUser($id);
    public function listMember(array $dataRequest);
    public function getUsers();
    public function getListContact();
    public function blockUser($dataRequest);
    public function hiddenUser($dataRequest);
    public function getListUserHidden();
    public function getLikes(array $query);
    public function find($id);
    public function listBlock();
}
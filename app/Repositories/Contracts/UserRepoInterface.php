<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface UserRepoInterface
{
    public function createUser($userEmail, $userName);

    public function getTotalUsers();

    public function getUser();

    public function getUserProfile();

    public function getUsersOnline();

    public function getUsersInDescendingOrder();

    public function getAllUsersPlusAdmin();

    public function deleteUser(int $id);

    public function getUserViaEmail($userEmail);

    public function updatePassword($newPassword);

    public function updateUserName(Request $request);

    public function updateUserProfile(Request $request);

    public function findUser(int $id);

    public function getUserPassword();

    public function setUserProfileToDefault(int $userId);

    public function putUserOnline(int $userId);

    public function putUserOfline(int $userId);
}
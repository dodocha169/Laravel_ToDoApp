<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Folder;
use Illuminate\Auth\Access\HandlesAuthorization;

class FolderPolicy
{
    use HandlesAuthorization;
    public function view(User $user, Folder $folder)
    {
        return $user->id === $folder->user_id;
    }
}

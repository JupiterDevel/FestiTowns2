<?php

namespace App\Policies;

use App\Models\Advertisement;
use App\Models\User;

class AdvertisementPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Advertisement $advertisement): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Advertisement $advertisement): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Advertisement $advertisement): bool
    {
        return $user->isAdmin();
    }

    public function toggle(User $user, Advertisement $advertisement): bool
    {
        return $user->isAdmin();
    }
}


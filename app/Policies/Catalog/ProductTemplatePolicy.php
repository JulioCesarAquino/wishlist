<?php

namespace App\Policies\Catalog;

use App\Models\Catalog\ProductTemplate;
use App\Models\User;

class ProductTemplatePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, ProductTemplate $productTemplate): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ProductTemplate $productTemplate): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ProductTemplate $productTemplate): bool
    {
        return $user->isAdmin();
    }
}

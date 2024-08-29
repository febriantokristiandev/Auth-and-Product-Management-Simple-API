<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function create(User $user)
    {
        return $user->is_admin; 
    }
    public function update(User $user, Product $product)
    {
        return $user->is_admin || $user->id === $product->user_id;
    }
    
    public function delete(User $user, Product $product)
    {
        return $user->is_admin || $user->id === $product->user_id;
    }
}

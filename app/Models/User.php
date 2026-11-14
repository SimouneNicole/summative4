<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Eloquent model for the TSA3 `users` profile table.
 */
#[Fillable(['username', 'full_name', 'email'])]
class User extends Model
{
    /** @use HasFactory<UserFactory> */
    use HasFactory;
}

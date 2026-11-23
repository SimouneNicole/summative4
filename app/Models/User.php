<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Eloquent authenticatable model for the TSA4 `users` table.
 */
#[Fillable(['username', 'full_name', 'name', 'email', 'password'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Map Breeze's conventional `name` attribute to the schema's `full_name` column.
     */
    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn (): string => (string) ($this->full_name ?? ''),
            set: fn (?string $value): array => ['full_name' => (string) $value],
        );
    }
}

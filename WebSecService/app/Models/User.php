<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'credits',
    ];

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
            'credits' => 'decimal:2',
        ];
    }

    /**
     * Get the orders for the user.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Check if the user has enough credits for a purchase
     */
    public function hasEnoughCredits(float $amount): bool
    {
        return $this->credits >= $amount;
    }

    /**
     * Deduct credits from user account
     */
    public function deductCredits(float $amount): bool
    {
        if (!$this->hasEnoughCredits($amount)) {
            return false;
        }

        $this->credits -= $amount;
        $this->save();

        return true;
    }

    /**
     * Add credits to user account
     */
    public function addCredits(float $amount): void
    {
        $this->credits += $amount;
        $this->save();
    }
}

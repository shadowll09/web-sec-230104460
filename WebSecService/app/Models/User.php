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
     * Deduct credits from user account with validation
     */
    public function deductCredits(float $amount): bool
    {
        // Validate amount is positive
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Credit amount must be positive.');
        }
        
        if (!$this->hasEnoughCredits($amount)) {
            return false;
        }

        $this->credits -= $amount;
        $this->save();
        
        // Log the transaction
        \Log::info("Deducted {$amount} credits from user ID {$this->id}. New balance: {$this->credits}");
        
        return true;
    }

    /**
     * Add credits to user account with validation
     */
    public function addCredits(float $amount): void
    {
        // Validate amount is positive
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Credit amount must be positive.');
        }
        
        // Cap maximum amount that can be added in a single transaction for security
        $maxSingleTransaction = config('app.max_credit_transaction', 10000);
        if ($amount > $maxSingleTransaction) {
            throw new \InvalidArgumentException("Cannot add more than {$maxSingleTransaction} credits in a single transaction.");
        }
        
        $this->credits += $amount;
        $this->save();
        
        // Log the transaction
        \Log::info("Added {$amount} credits to user ID {$this->id}. New balance: {$this->credits}");
    }
}

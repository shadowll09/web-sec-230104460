<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model  {

    use HasFactory;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
        'code',
        'name',
        'model',
        'description',
        'price',
        'stock_quantity',
        'photo',
    ];

    /**
     * Check if the product is in stock
     */
    public function isInStock()
    {
        return $this->stock_quantity > 0;
    }

    /**
     * Safely update stock quantity (prevent negative values)
     *
     * @param int $quantity The quantity to reduce (negative to increase)
     * @return boolean Whether the update was successful
     */
    public function updateStock($quantity)
    {
        // If reducing stock (positive quantity)
        if ($quantity > 0) {
            // Prevent reducing below zero
            if ($this->stock_quantity < $quantity) {
                return false;
            }
        }

        $this->stock_quantity -= $quantity;
        $this->save();

        return true;
    }

    /**
     * Get the orders that include this product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

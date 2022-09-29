<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_message', 'delivery_due_date', 'invoice_creation_date', 'total_price', 'customer_id'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}

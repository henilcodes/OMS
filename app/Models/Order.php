<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Order extends Model
{
    use HasUuids;

    protected $fillable = ['code', 'customer_id', 'total_amount', 'status'];


    public static function booted()
    {
        static::creating(function ($model) {
            $model->code = date('YmdHis') . '-' . random_int(100, 999);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

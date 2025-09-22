<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\{RegravationApprovalRequestStage,RegravationApprovalRequestShaft,MacroOrder, Order};

class RegravationApprovalRequest extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function stages()
    {
        return $this->hasMany(RegravationApprovalRequestStage::class,'regravation_approval_request_id')->orderBy('sequence_number');
    }

    public function shafts()
    {
        return $this->hasMany(RegravationApprovalRequestShaft::class,'regravation_approval_request_id');
    }

    public function macroOrder()
    {
        return $this->hasOne(MacroOrder::class,'id','macro_order_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class,'id','order_id');
    }

    public static function getCustomersList()
    {
        return self::with('macroOrder.customer')
            ->get()
            ->pluck('macroOrder.customer')
            ->filter()
            ->unique('id')
            ->values()
            ->toArray();
    }

    public static function getOrdersList()
    {
        return self::with('order')
            ->get()
            ->pluck('order')
            ->filter()
            ->unique('id')
            ->values()
            ->toArray();
    }

    public function scopeFilter($query, array $filters)
    {
        return $query
            ->when($filters['dateRange'] ?? false, fn($q, $dateRange) => 
                $q->filterByDateRange($dateRange))
            ->when($filters['customer'] ?? false, fn($q, $customer) => 
                $q->filterByCustomer($customer))
            ->when($filters['status'] ?? false, fn($q, $status) => 
                $q->where('status', $status))
            ->when($filters['order'] ?? false, fn($q, $orderId) => 
                $q->filterByOrder($orderId))
            ->when($filters['stage'] ?? false, fn($q, $stageId) => 
                $q->filterByStage($stageId));
    }

    public function scopeFilterByDateRange($query, $dateRange)
    {
        $dates = explode(' - ', $dateRange);
        if (count($dates) === 2) {
            return $query->whereBetween('create_date', [trim($dates[0]), trim($dates[1])]);
        }
        return $query;
    }

    public function scopeFilterByCustomer($query, $customer)
    {
        return $query->whereHas('macroOrder', function($q) use ($customer) {
            $q->where('customer_id', $customer['id']);
        });
    }

    public function scopeFilterByOrder($query, $orderId)
    {
        return $query->whereHas('order', fn($q) => 
            $q->where('id', $orderId));
    }

    public function scopeFilterByStage($query, $stageId)
    {
        return $query->whereHas('stages.stage', fn($q) => 
            $q->where('id', $stageId));
    }

}

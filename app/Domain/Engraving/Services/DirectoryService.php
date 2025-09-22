<?php

namespace App\Domain\Engraving\Services;

use App\Models\Customer;
use App\Models\Designer;
use App\Models\EngravingOrderCondition;
use App\Models\EngravingOrderStatus;
use App\Models\MacroOrder;
use App\Models\MountingParameter;
use App\Models\Profile;
use App\Models\Vendor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class DirectoryService
{
    public function macroOrders(): Collection
    {
        return MacroOrder::with('customer')->get();
    }

    public function findMacroOrder(int $id): ?MacroOrder
    {
        return MacroOrder::with('shaftsInWork.shaft', 'shaftsInWork.engravingOrder', 'shaftsInWork.resources')
            ->find($id);
    }

    public function profiles(): Collection
    {
        return Cache::remember('directories.profiles', now()->addMinutes(10), fn () => Profile::all());
    }

    public function vendors(): Collection
    {
        return Cache::remember('directories.vendors', now()->addMinutes(10), fn () => Vendor::all());
    }

    public function designers(): Collection
    {
        return Cache::remember('directories.designers', now()->addMinutes(10), fn () => Designer::all());
    }

    public function customers(): Collection
    {
        return Cache::remember(
            'directories.customers',
            now()->addMinutes(10),
            fn () => Customer::groupBy('inn')->get()
        );
    }

    public function engravingStatuses(): Collection
    {
        return Cache::remember('directories.engraving_statuses', now()->addMinutes(10), fn () => EngravingOrderStatus::all());
    }

    public function engravingConditions(): Collection
    {
        return Cache::remember('directories.engraving_conditions', now()->addMinutes(10), fn () => EngravingOrderCondition::all());
    }

    public function mountingParameters(): Collection
    {
        return Cache::remember('directories.mounting_parameters', now()->addMinutes(10), fn () => MountingParameter::all());
    }
}

<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Customize;
use App\Models\Destination;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CustomizeService
{
    public function create(array $data): void
    {
        $customize = Customize::create(
            collect($data)->except(['destinations', 'activities'])->toArray()
        );

        $destinations = $this->map(Destination::class, 'name', $data['destinations'] ?? null);
        $activities = $this->map(Activity::class, 'name', $data['activities'] ?? null);

        $this->attach($customize->destinations(), $destinations);
        $this->attach($customize->activities(), $activities);
    }

    protected function map(string $modelClass, string $column, array $data): array
    {
        return $modelClass::whereIn($column, $data)->pluck('id')->toArray();
    }

    protected function attach(BelongsToMany $relationship, array $ids): void
    {
        if (!empty($ids)) $relationship->attach($ids);
    }
}

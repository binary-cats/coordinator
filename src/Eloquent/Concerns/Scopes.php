<?php

namespace BinaryCats\Coordinator\Eloquent\Concerns;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait Scopes
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->whereNotNull('canceled_at');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithoutCancelled(Builder $query): Builder
    {
        return $query->whereNull('canceled_at');
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Carbon\Carbon|\DateTime|\DateTimeInterface|string|null  $at
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePast(Builder $query, $at = null): Builder
    {
        $at = empty($at) ? Carbon::now() : Carbon::parse($at);

        return $query->where('ends_at', '<', $at);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Carbon\Carbon|\DateTime|\DateTimeInterface|string|null  $at
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFuture(Builder $query, $at = null): Builder
    {
        $at = empty($at) ? Carbon::now() : Carbon::parse($at);

        return $query->where('starts_at', '>', $at);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Carbon\Carbon|\DateTime|\DateTimeInterface|string|null  $at
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrent(Builder $query, $at = null): Builder
    {
        $at = empty($at) ? Carbon::now() : Carbon::parse($at);

        return $query->where('starts_at', '<', $at)->where('ends_at', '>', $at);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForModel($query, Model $model): Builder
    {
        return $query->whereHasMorph(
            'model', [
                $model->getMorphClass(),
            ], fn ($advanced) => $advanced->whereKey($model->getKey())
        );
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \Illuminate\Database\Eloquent\Model  $resource
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForResource($query, Model $resource): Builder
    {
        return $query->whereHasMorph(
            'resource', [
                $resource->getMorphClass(),
            ], fn ($advanced) => $advanced->whereKey($resource->getKey())
        );
    }
}

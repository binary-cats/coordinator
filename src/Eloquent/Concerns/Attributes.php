<?php

namespace BinaryCats\Coordinator\Eloquent\Concerns;

trait Attributes
{
    /**
     * True if the record is canceled.
     *
     * @return bool
     */
    public function getIsCanceledAttribute(): bool
    {
        return ! empty($this->canceled_at);
    }

    /**
     * Shortcut to isFuture().
     *
     * @return bool
     */
    public function getIsFutureAttribute(): bool
    {
        return $this->isFuture();
    }

    /**
     * Shortcut to isPast().
     *
     * @return bool
     */
    public function getIsPastAttribute(): bool
    {
        return $this->isPast();
    }

    /**
     * Shortcut to isCurrent().
     *
     * @return bool
     */
    public function getIsCurrentAttribute(): bool
    {
        return $this->isCurrent();
    }
}

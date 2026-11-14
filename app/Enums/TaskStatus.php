<?php

namespace App\Enums;

/**
 * Supported workflow statuses for a task entry.
 */
enum TaskStatus: string
{
    case Pending = 'pending';
    case Ongoing = 'ongoing';
    case Completed = 'completed';

    /**
     * Human-readable badge text for the status.
     */
    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Ongoing => 'Ongoing',
            self::Completed => 'Completed',
        };
    }

    /**
     * CSS modifier class paired with the status pill.
     */
    public function cssClass(): string
    {
        return 'status-pill--'.$this->value;
    }
}

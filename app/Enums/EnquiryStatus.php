<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum EnquiryStatus: string implements HasColor, HasIcon, HasLabel
{
    case New        = 'new';
    case Processing = 'in_progress';
    case Resolved   = 'resolved';
    case Closed     = 'closed';

    public function getLabel(): string
    {
        return match ($this) {
            self::New        => 'New',
            self::Processing => 'In Progress',
            self::Resolved   => 'Resolved',
            self::Closed     => 'Closed',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::New        => 'info',     // blue
            self::Processing => 'warning',  // amber
            self::Resolved   => 'success',  // green
            self::Closed     => 'gray',     // neutral or muted
        };
    }

    public function getIcon(): string
    {
        return match ($this) {
            self::New        => 'heroicon-o-inbox-arrow-down',  // new enquiry
            self::Processing => 'heroicon-o-arrow-path',        // activity / in progress
            self::Resolved   => 'heroicon-o-check-circle',      // completed successfully
            self::Closed     => 'heroicon-o-archive-box-x-mark',// closed/cancelled
        };
    }
}

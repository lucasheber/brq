<?php

declare(strict_types = 1);

namespace App\Enums;

enum RiskStatus: string
{
    case HIGH_RISK   = 'high';
    case MEDIUM_RISK = 'medium';
    case LOW_RISK    = 'low';
}

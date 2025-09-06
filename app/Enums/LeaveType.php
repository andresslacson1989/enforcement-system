<?php

namespace App\Enums;

enum LeaveType: string
{
    case VACATION = 'vacation';
    case SICK = 'sick';
    case EMERGENCY = 'emergency';
    case BEREAVEMENT = 'bereavement';
    case MATERNITY = 'maternity';
    case PATERNITY = 'paternity';

    public function label(): string
    {
        return match ($this) {
            self::VACATION => 'Vacation Leave',
            self::SICK => 'Sick Leave',
            self::EMERGENCY => 'Emergency Leave',
            self::BEREAVEMENT => 'Bereavement Leave',
            self::MATERNITY => 'Maternity Leave',
            self::PATERNITY => 'Paternity Leave',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

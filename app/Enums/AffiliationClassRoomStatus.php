<?php

namespace App\Enums;

enum AffiliationClassRoomStatus: string

{
    case pending = 'محجوز';
    case completed = 'إكتمل';
    case active = 'مشغول';
    case cancelled = 'ملغي';

    public function getColor(): string
    {
        return match ($this) {
            self::pending => 'orange',
            self::completed => 'success',
            self::active => 'info',
            self::cancelled => 'gray',
        };
    }
}

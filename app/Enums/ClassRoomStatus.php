<?php

namespace App\Enums;

enum ClassRoomStatus: string

{
    case EMPTY = 'متاح';
    case RESERVED = 'محجوز';
    case OCCUPIED = 'مشغول';
    case CLOSE = 'مغلف';

    public function getColor(): string
    {
        return match ($this) {
            self::EMPTY => 'green',
            self::RESERVED => 'orange',
            self::OCCUPIED => 'danger',
            self::CLOSE => 'gray',
        };
    }
}

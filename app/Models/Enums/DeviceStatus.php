<?php

namespace App\Models\Enums;

enum DeviceStatus: int
{
    case InLavorazione = 0;
    case Testato = 1;
    case Pronto = 2;
}

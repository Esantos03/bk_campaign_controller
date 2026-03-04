<?php

namespace App\Enums;

enum EstadoActividad: string
{
    case PROGRAMADA = 'programada';
    case CANCELADA = 'cancelada';
    case COMPLETA = 'completa';
}

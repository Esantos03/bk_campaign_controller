<?php

namespace App\Enums;

enum EstadoConfirmacion: string
{
    case PENDIENTE = 'pendiente';
    case CONFIRMADO = 'confirmado';
    case RECHAZADO = 'rechazado';
}

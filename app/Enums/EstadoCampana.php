<?php

namespace App\Enums;

enum EstadoCampana: string
{
    case BORRADOR = 'borrador';
    case ENVIADA = 'enviada';
    case FINALIZADA = 'finalizada';
}

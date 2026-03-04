<?php

namespace App\Filament\Resources\Clientes\Schemas;

use App\Enums\EstadoCliente;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ClienteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nombre')
                    ->required()
                    ->maxLength(255)
                    ->minLength(2)
                    ->regex('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/')
                    ->helperText('Solo letras y espacios'),
                TextInput::make('apellido')
                    ->required()
                    ->maxLength(255)
                    ->minLength(2)
                    ->regex('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/')
                    ->helperText('Solo letras y espacios'),
                TextInput::make('telefono_whatsapp')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->regex('/^[0-9]{10,15}$/')
                    ->helperText('Entre 10 y 15 dígitos, sin espacios ni caracteres especiales')
                    ->maxLength(15),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Select::make('estado')
                    ->options(EstadoCliente::class)
                    ->default('activo')
                    ->required(),
            ]);
    }
}

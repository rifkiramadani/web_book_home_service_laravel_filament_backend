<?php

namespace App\Filament\Resources\HomeServices\Schemas;

use Filament\Schemas\Schema;
use Filament\Schemas\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;

class HomeServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Fieldset::make('General Information')
                    ->columnSpan(2)
                    ->schema([
                        Textinput::make('name')
                            ->required()
                            ->maxLength(255),
                        FileUpload::make('thumbnail')
                            ->required()
                            ->image()
                            ->directory('home_services_thumbnail')
                            ->disk('public'),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->required()
                            ->preload()
                            ->searchable(),
                        TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('IDR')

                    ]),
                Fieldset::make('Additional Information')
                    ->columnSpan(2)
                    ->schema([
                        Textarea::make('about')
                            ->required(),
                        Select::make('is_popular')
                            ->label('Popular?')
                            ->required()
                            ->options([
                                true => 'Popular',
                                false => 'Not Popular'
                            ]),
                        TextInput::make('duration')
                            ->required()
                            ->numeric()
                            ->prefix('Hours'),
                        Repeater::make('Benefits')
                            ->relationship('serviceBenefits')
                            ->schema([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255)
                            ])
                    ])
            ]);
    }
}

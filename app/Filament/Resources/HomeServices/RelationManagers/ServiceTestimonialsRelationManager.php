<?php

namespace App\Filament\Resources\HomeServices\RelationManagers;

use App\Filament\Resources\HomeServices\HomeServiceResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Forms;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;


class ServiceTestimonialsRelationManager extends RelationManager
{
    protected static string $relationship = 'serviceTestimonials';

    // protected static ?string $relatedResource = HomeServiceResource::class; //tidak boleh ada ini

    public function form(Schema $schema): Schema
        {
            return $schema
                ->components([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(255),
                    Textarea::make('message')
                        ->required(),
                    FileUpload::make('photo')
                        ->required()
                        ->image()
                        ->directory('service_testimonials_photo')
                        ->disk('public')
                ]);
        }

    public function table(Table $table): Table
        {
            return $table
                ->columns([
                    ImageColumn::make('photo')
                        ->disk('public'),
                    TextColumn::make('name')
                        ->searchable()
                        ->sortable(),
                    TextColumn::make('message')
                        ->limit(50),
                ])
                ->headerActions([
                    CreateAction::make(),
                ])
                ->actions([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]);
        }
}

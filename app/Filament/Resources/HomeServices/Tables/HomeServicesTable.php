<?php

namespace App\Filament\Resources\HomeServices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Filters\SelectFilter;

class HomeServicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                //
                //thumbnail, name, category, is_popular
                ImageColumn::make('thumbnail')
                    ->disk('public'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name'),
                IconColumn::make('is_popular')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedXCircle)
                    ->label('Popular?')
            ])
            ->filters([
                TrashedFilter::make(),
                SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Resources\HomeServices;

use BackedEnum;
use Filament\Tables\Table;
use App\Models\HomeService;
use Filament\Schemas\Schema;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\HomeServices\RelationManagers\ServiceTestimonialsRelationManager;
use App\Filament\Resources\HomeServices\Pages\EditHomeService;
use App\Filament\Resources\HomeServices\Pages\ListHomeServices;
use App\Filament\Resources\HomeServices\Pages\CreateHomeService;
use App\Filament\Resources\HomeServices\Schemas\HomeServiceForm;
use App\Filament\Resources\HomeServices\Tables\HomeServicesTable;

class HomeServiceResource extends Resource
{
    protected static ?string $model = HomeService::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return HomeServiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HomeServicesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            ServiceTestimonialsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListHomeServices::route('/'),
            'create' => CreateHomeService::route('/create'),
            'edit' => EditHomeService::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

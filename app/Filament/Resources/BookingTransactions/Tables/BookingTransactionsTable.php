<?php

namespace App\Filament\Resources\BookingTransactions\Tables;

use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use App\Models\BookingTransaction;
use Filament\Actions\DeleteAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Actions\ForceDeleteBulkAction;

class BookingTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('booking_trx_id')
                    ->searchable(),
                TextColumn::make('created_at'),
                IconColumn::make('is_paid')
                    ->boolean()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedXCircle)
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                //tombol untuk approve payment
                Action::make('approve')
                    ->label('Approve')
                    ->action(function(BookingTransaction $record) {
                    //langsung set is_paid nya menjadi true
                    $record->is_paid = true;
                    $record->save();

                    Notification::make()
                        ->title('Payment Completed')
                        ->body('Payment Has Been Approved')
                        ->success()
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->iconColor('success')
                        ->send();
                })
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn(BookingTransaction $record) => !$record->is_paid),
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

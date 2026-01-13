<?php

namespace App\Filament\Resources\BookingTransactions\Schemas;

use App\Models\HomeService;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Wizard;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class BookingTransactionForm
{
    //membuat function terpisah untuk menghitung total amount
    public static function updateTotals(Get $get, Set $set) {
        //mengkalkulasi jika semua repeater home service di isi (jika salah satu kosong maka tidak akan di kalkulasi)
        $selectedHomeServices = collect($get('transactionDetails'))->filter(fn($item) => !empty($item['home_service_id']));

        //mengambil data name dan id home service dari relasi transactionDetails
        $prices = HomeService::find($selectedHomeServices->pluck('home_service_id'))->pluck('price','id');

        $subtotal = $selectedHomeServices->reduce(function($subtotal, $item) use ($prices) {
            return $subtotal + ($prices[$item['home_service_id']] * 1);
        }, 0);

        $total_tax_amount = round($subtotal * 0.11);

        $total_amount = round($subtotal + $total_tax_amount);

        $set('sub_total', number_format($subtotal, 0, '.', ''));

        $set('total_amount', number_format($total_amount, 0, '.', ''));

        $set('total_tax_amount', number_format($total_tax_amount, 0, '.',''));
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Product And Price')
                        ->completedIcon(Heroicon::HandThumbUp)
                        ->description('Add Your Product Items')
                        ->schema([

                            Grid::make(2)
                                ->schema([
                                    Repeater::make('transactionDetails')
                                        ->relationship('transactionDetails')
                                        ->schema([
                                            Select::make('home_service_id')
                                                ->relationship('homeService', 'name')
                                                ->required()
                                                ->preload()
                                                ->searchable()
                                                ->live()
                                                ->afterStateUpdated(function ($state, callable $set) {
                                                    $home_service_id = HomeService::find($state);
                                                    $set('price', $home_service_id ? $home_service_id->price : 0);
                                                }),
                                            TextInput::make('price')
                                                ->numeric()
                                                ->required()
                                                ->readOnly()
                                                ->prefix('IDR')
                                                ->label('Price')
                                                ->hint('Price Will be filled automatically based on product selection'),
                                        ])
                                    ->live()
                                    ->afterStateUpdated(function(Get $get, Set $set) {
                                            self::updateTotals($get, $set);
                                    })
                                    ->minItems(1)
                                    ->columnSpan('full')
                                    ->label('Choose Products'),

                                    ]),

                                    Grid::make(2)
                                        ->schema([
                                            TextInput::make('sub_total')
                                                ->numeric()
                                                ->readOnly()
                                                ->label('Sub Total Amount'),
                                            TextInput::make('total_amount')
                                                ->numeric()
                                                ->readOnly()
                                                ->label('Total Amount'),
                                            TextInput::make('total_tax_amount')
                                                ->numeric()
                                                ->readOnly()
                                                ->label('Total Tax (11%)')
                                        ])
                                        ->columnSpan('full')

                        ]),
                    Step::make('Customer Information')
                        ->completedIcon(Heroicon::HandThumbUp)
                        ->description('For Out Marketing')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('name')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('phone')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('email')
                                        ->required()
                                        ->maxLength(255)
                                ])
                                ->columnSpan('full')
                        ]),
                    Step::make('Delivery Information')
                        ->completedIcon(Heroicon::HandThumbUp)
                        ->description('Put Your Correct Address')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                    TextInput::make('city')
                                        ->required()
                                        ->maxLength(255),
                                    TextInput::make('post_code')
                                        ->required()
                                        ->maxLength(255),
                                    DatePicker::make('schedule_at')
                                        ->required(),
                                    TimePicker::make('started_time')
                                        ->required(),
                                    TextArea::make('address')
                                        ->required()
                                        ->maxLength(255),
                                ])
                                ->columnSpan('full')
                        ]),

                       Step::make('Payment Information')
                        ->completedIcon(Heroicon::HandThumbUp)
                        ->description('Review Your Payment')
                        ->schema([
                            Grid::make(2)
                                ->schema([
                                   TextInput::make('booking_trx_id')
                                        ->required()
                                        ->maxLength(255),
                                    ToggleButtons::make('is_paid')
                                        ->label('Apakah Sudah Membayar?')
                                        ->boolean()
                                        ->grouped()
                                        ->icons([
                                            true => Heroicon::OutlinedPencil,
                                            false => Heroicon::OutlinedClock,
                                        ])
                                        ->required(),
                                    FileUpload::make('proof')
                                        ->required()
                                        ->image()
                                        ->disk('public')
                                        ->directory('payment_proof'),
                                ])
                                ->columnSpan('full')
                        ]),
                ])
                ->columnSpan(2)
                ->skippable(),
            ]);
    }
}

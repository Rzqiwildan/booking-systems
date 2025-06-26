<?php

namespace App\Filament\Resources;

use App\Models\Booking;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BookingResource\Pages;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Bookings';

    protected static ?string $pluralModelLabel = 'Bookings';

    protected static ?string $modelLabel = 'Booking';

    protected static ?int $navigationSort = 2;

    // Form Schema
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Customer')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('full_name')
                                    ->label('Nama Lengkap')
                                    ->required()
                                    ->maxLength(255),
                                
                                Forms\Components\TextInput::make('phone_number')
                                    ->label('No. Handphone')
                                    ->required()
                                    ->tel()
                                    ->maxLength(20),
                            ]),

                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->required()
                            ->rows(3),
                    ]),

                Section::make('Detail Pemesanan')
                    ->schema([
                        Forms\Components\Select::make('car_id')
                            ->label('Pilihan Mobil')
                            ->required()
                            ->options(Car::all()->pluck('name', 'id'))  // Menampilkan mobil dari tabel `cars`
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if ($state) {
                                    $car = Car::find($state);
                                    $set('car_type_display', $car?->type); // Menampilkan tipe mobil
                                }
                            })
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('car_type_display')
                            ->label('Tipe Transmisi')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Forms\Components\Select::make('rental_service')
                            ->label('Layanan Sewa')
                            ->required()
                            ->options([
                                'Lepas Kunci' => 'Lepas Kunci',
                                'Dengan Driver' => 'Dengan Driver',
                            ])
                            ->default('Lepas Kunci')
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\DatePicker::make('rental_date')
                                    ->label('Tanggal Penyewaan')
                                    ->required()
                                    ->minDate(now())
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        $set('return_date', null);
                                    }),

                                Forms\Components\DatePicker::make('return_date')
                                    ->label('Tanggal Pengembalian')
                                    ->required()
                                    ->minDate(function (Forms\Get $get) {
                                        return $get('rental_date') ?: now();
                                    }),
                            ]),
                    ]),

                Section::make('Detail Lokasi & Waktu')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('delivery_location')
                                    ->label('Lokasi Pengantaran')
                                    ->maxLength(255),

                                Forms\Components\TextInput::make('return_location')
                                    ->label('Lokasi Pengembalian')
                                    ->maxLength(255),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Forms\Components\TimePicker::make('delivery_time')
                                    ->label('Jam Pengantaran')
                                    ->seconds(false),

                                Forms\Components\TimePicker::make('return_time')
                                    ->label('Jam Pengembalian')
                                    ->seconds(false),
                            ]),
                    ]),

                Section::make('Informasi Tambahan')
                    ->schema([
                        Forms\Components\Textarea::make('special_notes')
                            ->label('Catatan Khusus')
                            ->rows(4),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->required()
                            ->options([
                                'pending' => 'Pending',
                                'confirmed' => 'Confirmed',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->default('pending'),
                    ]),
            ]);
    }

    // Tabel Booking
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nama Customer')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('car.name')
                    ->label('Mobil')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('car.type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Manual' => 'primary',
                        'Matic' => 'success',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('rental_service')
                    ->label('Layanan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Lepas Kunci' => 'warning',
                        'Dengan Driver' => 'info',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('rental_date')
                    ->label('Tgl Sewa')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('return_date')
                    ->label('Tgl Kembali')
                    ->date('d M Y')
                    ->sortable(),

                // Tables\Columns\TextColumn::make('status')
                //     ->label('Status')
                //     ->badge()
                //     ->color(fn (string $state): string => match ($state) {
                //         'pending' => 'warning',
                //         'confirmed' => 'info',
                //         'completed' => 'success',
                //         'cancelled' => 'danger',
                //         default => 'gray',
                //     })
                //     ->formatStateUsing(fn (string $state): string => ucfirst($state)),

                Tables\Columns\TextColumn::make('phone_number')
                    ->label('No. HP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Tables\Filters\SelectFilter::make('status')
                //     ->label('Status')
                //     ->options([
                //         'pending' => 'Pending',
                //         'confirmed' => 'Confirmed',
                //         'completed' => 'Completed',
                //         'cancelled' => 'Cancelled',
                //     ]),

                Tables\Filters\SelectFilter::make('car')
                    ->label('Mobil')
                    ->relationship('car', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('rental_service')
                    ->label('Layanan Sewa')
                    ->options([
                        'Lepas Kunci' => 'Lepas Kunci',
                        'Dengan Driver' => 'Dengan Driver',
                    ]),

                Tables\Filters\Filter::make('rental_dates')
                    ->form([
                        Forms\Components\DatePicker::make('from_date')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('to_date')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('rental_date', '>=', $date),
                            )
                            ->when(
                                $data['to_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('rental_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\BulkAction::make('confirm')
                    ->label('Konfirmasi')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function ($records) {
                        $records->each(function ($record) {
                            $record->update(['status' => 'confirmed']);
                        });
                    })
                    ->requiresConfirmation(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // Get the relations (if needed for more relationships)
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Pastikan Pages diimpor dengan benar
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
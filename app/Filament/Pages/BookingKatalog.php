<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use App\Models\Kendaraan;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Exceptions\Halt;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;

class BookingKatalog extends Page implements Tables\Contracts\HasTable
{
    use Tables\Concerns\InteractsWithTable;

    protected static ?string $navigationIcon  = 'heroicon-o-calendar-days';
    protected static ?string $navigationLabel = 'Booking';
    protected static ?string $title           = 'Booking Kendaraan';
    protected static ?int    $navigationSort  = 3;

    protected static ?string $slug = 'booking';

    /**
     * @var view-string
     */
    protected static string $view = 'filament.pages.booking-katalog';

    protected function getTableQuery(): ?Builder
    {
        return Kendaraan::query();
    }

    /**
     * @return array<string, int|null>
     */
    protected function getTableContentGrid(): ?array
    {
        return [
            'md' => 2,
            'xl' => 3,
        ];
    }

    /**
     * @return array<\Filament\Tables\Columns\Column|\Filament\Tables\Columns\Layout\Component>
     */
    protected function getTableColumns(): array
    {
        return [
            Stack::make([
                ImageColumn::make('thumbnail')
                    ->label('Gambar')
                    ->disk('local')
                    ->height(120)
                    ->extraImgAttributes(['class' => 'object-cover rounded-lg']),

                TextColumn::make('nama_kendaraan')
                    ->label('Nama')
                    ->searchable()
                    ->weight('medium'),

                TextColumn::make('harga_kendaraan')
                    ->label('Harga referensi')
                    ->money('idr'),

                TextColumn::make('lama_sewa')
                    ->label('Lama sewa (info)')
                    ->wrap(),
            ])
                ->space(3),
        ];
    }

    /**
     * @return array<Action|\Filament\Tables\Actions\ActionGroup>
     */
    protected function getTableActions(): array
    {
        return [
            Action::make('booking')
                ->label('Booking')
                ->icon('heroicon-o-calendar-days')
                ->button()
                ->color('primary')
                ->modalHeading(fn (Kendaraan $record): string => 'Booking: '.$record->nama_kendaraan)
                ->modalWidth(MaxWidth::FiveExtraLarge)
                ->modalSubmitActionLabel('Simpan booking')
                ->form(function (Form $form, Kendaraan $record): Form {
                    return $form
                        ->schema([
                            TextInput::make('nama_customer')
                                ->label('Nama')
                                ->required()
                                ->maxLength(255),

                            TextInput::make('nik')
                                ->label('NIK')
                                ->required()
                                ->maxLength(32),

                            TextInput::make('no_hp')
                                ->label('No. HP')
                                ->tel()
                                ->required()
                                ->maxLength(32),

                            Textarea::make('alamat')
                                ->label('Alamat')
                                ->required()
                                ->rows(3)
                                ->columnSpanFull(),

                            FileUpload::make('foto_ktp')
                                ->label('Foto KTP')
                                ->image()
                                ->maxSize(2048)
                                ->disk('public')
                                ->directory('bookings/ktp')
                                ->required(),

                            FileUpload::make('foto_kk')
                                ->label('Foto KK')
                                ->image()
                                ->maxSize(2048)
                                ->disk('public')
                                ->directory('bookings/kk')
                                ->required(),

                            FileUpload::make('foto_sim')
                                ->label('Foto SIM')
                                ->image()
                                ->maxSize(2048)
                                ->disk('public')
                                ->directory('bookings/sim')
                                ->required(),

                            FileUpload::make('foto_dokumentasi')
                                ->label('Foto customer dengan mobil')
                                ->image()
                                ->maxSize(2048)
                                ->disk('public')
                                ->directory('bookings/dokumentasi')
                                ->required(),

                            DateTimePicker::make('waktu_mulai')
                                ->label('Waktu booking dari')
                                ->seconds(false)
                                ->required()
                                ->native(false),

                            DateTimePicker::make('waktu_selesai')
                                ->label('Waktu booking sampai')
                                ->seconds(false)
                                ->required()
                                ->native(false)
                                ->rules([
                                    fn (Get $get): \Closure => function (string $attribute, mixed $value, \Closure $fail) use ($get, $record): void {
                                        $mulai = $get('waktu_mulai');
                                        if (! $mulai || ! $value) {
                                            return;
                                        }
                                        if (strtotime((string) $value) <= strtotime((string) $mulai)) {
                                            $fail('Waktu booking sampai harus setelah waktu mulai.');

                                            return;
                                        }
                                        if (Booking::overlapsForKendaraan((int) $record->id, $mulai, $value)) {
                                            $fail('Kendaraan sudah memiliki booking aktif pada rentang waktu yang bentrok.');
                                        }
                                    },
                                ]),

                            TextInput::make('harga_rental')
                                ->label('Harga rental')
                                ->numeric()
                                ->integer()
                                ->prefix('Rp')
                                ->minValue(0)
                                ->required(),
                        ])
                        ->columns(2);
                })
                ->action(function (array $data, Kendaraan $record): void {
                    if (strtotime((string) $data['waktu_selesai']) <= strtotime((string) $data['waktu_mulai'])) {
                        Notification::make()
                            ->title('Periode tidak valid')
                            ->body('Waktu selesai harus setelah waktu mulai.')
                            ->danger()
                            ->send();

                        throw new Halt;
                    }

                    if (Booking::overlapsForKendaraan((int) $record->id, $data['waktu_mulai'], $data['waktu_selesai'])) {
                        Notification::make()
                            ->title('Jadwal bentrok')
                            ->body('Kendaraan ini sudah dibooking pada rentang waktu tersebut.')
                            ->danger()
                            ->send();

                        throw new Halt;
                    }

                    Booking::create([
                        'kendaraan_id' => $record->id,
                        'nama_customer' => $data['nama_customer'],
                        'nik' => $data['nik'],
                        'no_hp' => $data['no_hp'],
                        'alamat' => $data['alamat'],
                        'foto_ktp' => $data['foto_ktp'],
                        'foto_kk' => $data['foto_kk'],
                        'foto_sim' => $data['foto_sim'],
                        'foto_dokumentasi' => $data['foto_dokumentasi'],
                        'waktu_mulai' => $data['waktu_mulai'],
                        'waktu_selesai' => $data['waktu_selesai'],
                        'harga_rental' => (int) $data['harga_rental'],
                    ]);

                    Notification::make()
                        ->title('Booking tersimpan')
                        ->success()
                        ->send();
                }),
        ];
    }

    /**
     * @return array<\Filament\Tables\Actions\BulkAction|\Filament\Tables\Actions\BulkActionGroup>
     */
    protected function getTableBulkActions(): array
    {
        return [];
    }
}

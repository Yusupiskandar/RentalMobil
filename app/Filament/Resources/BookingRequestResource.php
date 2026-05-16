<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingRequestResource\Pages;
use App\Models\BookingRequest;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BookingRequestResource extends Resource
{
    protected static ?string $model = BookingRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-check';
    protected static ?string $navigationLabel = 'Persetujuan Booking';
    protected static ?int $navigationSort = 2;
    protected static ?string $modelLabel = 'Persetujuan Booking';
    protected static ?string $pluralModelLabel = 'Persetujuan Booking';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable(),
                TextColumn::make('kendaraan.nama_kendaraan')
                    ->label('Kendaraan')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->searchable(),
                TextColumn::make('no_hp')
                    ->label('No. HP'),
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(30),
                ImageColumn::make('foto_ktp')
                    ->label('Foto KTP')
                    ->disk('public')
                    ->action(
                        Action::make('lihat_ktp')
                            ->modalHeading('Foto KTP')
                            ->modalSubmitAction(false)
                            ->infolist([
                                \Filament\Infolists\Components\ImageEntry::make('foto_ktp')
                                    ->hiddenLabel()
                                    ->disk('public')
                                    ->width('100%')
                            ])
                    ),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'proses_review' => 'warning',
                        'diterima' => 'success',
                        'ditolak' => 'danger',
                        default => 'secondary',
                    })
                    ->action(
                        Action::make('ubah_status')
                            ->form([
                                Select::make('status')
                                    ->label('Ubah Status')
                                    ->options([
                                        'proses_review' => 'Proses Review',
                                        'diterima' => 'Diterima',
                                        'ditolak' => 'Ditolak',
                                    ])
                                    ->required(),
                            ])
                            ->action(function (BookingRequest $record, array $data): void {
                                $record->update(['status' => $data['status']]);
                            })
                    ),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingRequests::route('/'),
        ];
    }
}

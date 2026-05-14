<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Models\Booking;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TransaksiResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon  = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Transaksi';
    protected static ?int    $navigationSort  = 4;
    protected static ?string $pluralModelLabel = 'Transaksi';
    protected static ?string $modelLabel      = 'Transaksi';

    // --- Read-Only: Disable all modification capabilities ---
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Tanggal Transaksi')
                    ->dateTime('d M Y, H:i')
                    ->timezone('Asia/Jakarta')
                    ->sortable(),

                TextColumn::make('nama_customer')
                    ->label('Nama Customer')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('kendaraan.nama_kendaraan')
                    ->label('Kendaraan')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no_hp')
                    ->label('No HP')
                    ->searchable(),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->alamat),

                TextColumn::make('waktu_sewa')
                    ->label('Waktu Sewa')
                    ->getStateUsing(function ($record): string {
                        $mulai = $record->waktu_mulai?->format('d M Y');
                        $selesai = $record->waktu_selesai?->format('d M Y');
                        return $mulai && $selesai ? "{$mulai} - {$selesai}" : '-';
                    })
                    ->badge()
                    ->color(function ($record): string {
                        if (!$record->waktu_mulai || !$record->waktu_selesai) {
                            return 'secondary';
                        }
                        $now = now();
                        if ($now->greaterThan($record->waktu_selesai)) {
                            return 'danger';
                        } elseif ($now->between($record->waktu_mulai, $record->waktu_selesai)) {
                            return 'success';
                        } else {
                            return 'info';
                        }
                    }),

                TextColumn::make('harga_rental')
                    ->label('Harga Rental')
                    ->money('IDR')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Informasi Transaksi')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Tanggal Transaksi')
                            ->dateTime('d M Y, H:i')
                            ->timezone('Asia/Jakarta'),

                        TextEntry::make('harga_rental')
                            ->label('Harga Rental')
                            ->money('IDR'),

                        TextEntry::make('kendaraan.nama_kendaraan')
                            ->label('Kendaraan'),
                    ]),

                Section::make('Data Customer')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('nama_customer')
                            ->label('Nama Customer'),

                        TextEntry::make('nik')
                            ->label('NIK'),

                        TextEntry::make('no_hp')
                            ->label('No HP'),

                        TextEntry::make('alamat')
                            ->label('Alamat')
                            ->columnSpanFull(),
                    ]),

                Section::make('Waktu Sewa')
                    ->columns(2)
                    ->schema([
                        TextEntry::make('waktu_mulai')
                            ->label('Waktu Mulai')
                            ->dateTime('d M Y, H:i'),

                        TextEntry::make('waktu_selesai')
                            ->label('Waktu Selesai')
                            ->dateTime('d M Y, H:i'),
                    ]),

                Section::make('Dokumen & Foto')
                    ->columns(2)
                    ->schema([
                        ImageEntry::make('foto_ktp')
                            ->label('Foto KTP')
                            ->disk('public'),

                        ImageEntry::make('foto_kk')
                            ->label('Foto KK')
                            ->disk('public'),

                        ImageEntry::make('foto_sim')
                            ->label('Foto SIM')
                            ->disk('public'),

                        ImageEntry::make('foto_dokumentasi')
                            ->label('Foto Dokumentasi')
                            ->disk('public'),
                    ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'view'  => Pages\ViewTransaksi::route('/{record}'),
        ];
    }
}

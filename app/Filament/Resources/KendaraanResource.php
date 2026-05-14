<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KendaraanResource\Pages;
use App\Models\Kendaraan;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class KendaraanResource extends Resource
{
    protected static ?string $model = Kendaraan::class;

    protected static ?string $navigationIcon  = 'heroicon-o-truck';
    protected static ?string $navigationLabel = 'Kendaraan';
    protected static ?int    $navigationSort  = 1;

    protected static ?string $modelLabel      = 'Kendaraan';
    protected static ?string $pluralModelLabel = 'Kendaraan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_kendaraan')
                    ->label('Nama Kendaraan')
                    ->required()
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('Deskripsi')
                    ->required()
                    ->rows(10)
                    ->columnSpanFull(),

                FileUpload::make('thumbnail')
                    ->label('Thumbnail')
                    ->image()
                    ->maxFiles(1)
                    ->disk('local')
                    ->directory('public/kendaraan-thumbnails')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->imagePreviewHeight('200')
                    ->maxSize(2048)
                    ->required(),

                FileUpload::make('gambar_kendaraan')
                    ->label('Gambar Kendaraan')
                    ->image()
                    ->multiple()
                    ->disk('local')
                    ->directory('public/kendaraan-gambar')
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->imagePreviewHeight('200')
                    ->maxSize(5120)
                    ->reorderable()
                    ->columnSpanFull(),

                TextInput::make('harga_kendaraan')
                    ->label('Harga Kendaraan')
                    ->numeric()
                    ->integer()
                    ->prefix('Rp')
                    ->minValue(0)
                    ->required(),

                TextInput::make('lama_sewa')
                    ->label('Lama Sewa')
                    ->placeholder('Contoh: 12 Jam, 1 Hari')
                    ->required()
                    ->maxLength(255),

                Select::make('status')
                    ->label('Status')
                    ->options([
                        'tersedia' => 'Tersedia',
                        'tidak_tersedia' => 'Tidak Tersedia',
                    ])
                    ->default('tersedia')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('local')
                    ->square()
                    ->height(48),

                TextColumn::make('nama_kendaraan')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('harga_kendaraan')
                    ->label('Harga')
                    ->money('idr')
                    ->sortable(),

                TextColumn::make('lama_sewa')
                    ->label('Lama Sewa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'tersedia' => 'success',
                        'tidak_tersedia' => 'danger',
                        default => 'secondary',
                    })
                    ->action(
                        Action::make('ubah_status')
                            ->form([
                                Select::make('status')
                                    ->label('Ubah Status Kendaraan')
                                    ->options([
                                        'tersedia' => 'Tersedia',
                                        'tidak_tersedia' => 'Tidak Tersedia',
                                    ])
                                    ->required(),
                            ])
                            ->action(function (Kendaraan $record, array $data): void {
                                $record->update(['status' => $data['status']]);
                            })
                    ),

                ImageColumn::make('gambar_kendaraan')
                    ->label('Galeri')
                    ->disk('local')
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->square()
                    ->height(40),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKendaraans::route('/'),
            'create' => Pages\CreateKendaraan::route('/create'),
            'edit' => Pages\EditKendaraan::route('/{record}/edit'),
        ];
    }
}

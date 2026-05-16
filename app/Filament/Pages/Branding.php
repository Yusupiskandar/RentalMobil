<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class Branding extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-swatch';

    protected static string $view = 'filament.pages.branding';
    protected static ?string $navigationGroup = 'Pengaturan';
    protected static ?string $title = 'Branding Aplikasi';

    public ?string $nama_brand = '';

    public function mount(): void
    {
        $this->nama_brand = Setting::where('key', 'brand_name')->value('value') ?? 'Rental Mobil';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_brand')
                    ->label('Nama Aplikasi / Brand')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function save(): void
    {
        Setting::updateOrCreate(
            ['key' => 'brand_name'],
            ['value' => $this->nama_brand]
        );

        Notification::make()
            ->title('Berhasil disimpan')
            ->success()
            ->send();
    }
}

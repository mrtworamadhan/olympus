<?php

namespace App\Filament\App\Pages;

use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Facades\Filament;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\Auth;

class TenantSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected string $view = 'filament.app.pages.tenant-settings';

    protected static string | UnitEnum | null $navigationGroup  = 'Settings';

    protected static ?string $navigationLabel = 'Pengaturan';

    protected static ?string $title = 'Profil Event Organizer';

    protected static ?int $navigationSort = 8;


    public ?array $data = [];

    public function mount(): void
    {
        $tenant = Filament::getTenant();

        if ($tenant) {
            $data = [
                'name' => $tenant->name,
                'tenant_slug' => $tenant->slug,
                'logo' => $tenant->logo,
                'primary_color' => $tenant->primary_color,
            ];
        }
        // dd($data);
        $this->form->fill($data);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profil EO')
                    ->columnSpanFull()
                    ->components([
                        Grid::make()
                            ->columns(1)
                            ->components([
                                FileUpload::make('logo')
                                    ->directory('tenants-logo')
                                    ->disk('public')
                                    ->avatar()
                                    ->alignCenter()
                                    ->image(),
                            ]),
                        Grid::make()
                            ->columns(3)
                            ->components([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('tenant_slug')
                                    ->label('URL Slug (Subdomain)')
                                    ->required()
                                    ->maxLength(255),
                                ColorPicker::make('primary_color')
                                    ->label('Warna Tema')
                                    ->default('#000000'),
                            ]),
                        Action::make()
                            ->label('Simpan Perubahan')
                                ->action('save'),
                        
                    ])
                
            ])
            ->statePath('data');
            
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Simpan Perubahan')
                ->submit('save'),
        ];
    }

    public function save(): void
    {
        $tenant = Filament::getTenant();

        $formData = $this->form->getState();

        $tenant->update([
            'name' => $formData['name'],
            'slug' => $formData['tenant_slug'],
            'logo' => $formData['logo'],
            'primary_color' => $formData['primary_color'],
        ]);

        Notification::make()
            ->success()
            ->title('Profil EO Berhasil Disimpan')
            ->send();
    }

}

<?php

namespace App\Filament\Team\Pages\Auth;

use App\Models\Competitor;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Auth\Pages\Register as BaseRegister;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\DB;

class RegisterTeam extends BaseRegister
{
    public function form(Schema $form): Schema
    {
        return $form
            ->schema([
                Hidden::make('event_id')
                    ->default(request()->query('event'))
                    ->required()
                    ->validationMessages([
                        'required' => 'Link pendaftaran salah! (Event ID tidak ditemukan).',
                    ]),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
                
                TextInput::make('team_name')
                    ->label('Nama Tim / Klub')
                    ->placeholder('Contoh: Garuda FC')
                    ->required()
                    ->maxLength(255),
            ])
            ->statePath('data');
    }

    protected function handleRegistration(array $data): \App\Models\User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->getUserModel()::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'team',
            ]);

            Competitor::create([
                'user_id'  => $user->id,
                'event_id' => $data['event_id'],
                'name'     => $data['team_name'],
                'team_manager' => $data['name'],
            ]);

            return $user;
        });
    }
}
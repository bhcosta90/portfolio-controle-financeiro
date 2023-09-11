<?php

namespace App\Filament;

use Filament\Forms\Components\Hidden;
use Filament\Pages\Auth\Login as BaseAuth;

class Login extends BaseAuth
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        Hidden::make('tenant_id')->default(tenant('id')),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'tenant_id' => $data['tenant_id'],
            'email' => $data['email'],
            'password' => $data['password'],
        ];
    }
}

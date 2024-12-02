<?php

namespace App;

use Filament\Pages\Auth\Login;

class AdminLogin extends Login
{

    public function mount(): void
    {
      if(app()->isLocal()) {

          $this->form->fill([
              'email' => env('ADMIN_EMAIL', 'admin@admin.com'),
              'password' => env('ADMIN_PASSWORD','password'),
          ]);
      }
    }

}

<?php

namespace App\Console\Commands\User;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Models\User;

#[Signature('user:show')]
#[Description('List current users')]
class Show extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::query()
            ->orderBy('id')
            ->get(['id', 'name', 'firstname', 'email', 'created_at', 'role']);

        if ($users->isEmpty()) {
            $this->warn('Aucun utilisateur trouvé.');

            return self::SUCCESS;
        }

        $this->table(
            ['ID', 'Prénom','Nom', 'Email', 'Role', 'Créé le'],
            $users->map(fn (User $user) => [
                $user->id,
                $user->firstname,
                $user->name,
                $user->email,
                $user->role,
                $user->created_at?->format('d/m/Y H:i'),
            ])->toArray()
        );

        $this->info(sprintf('%d utilisateur(s) trouvé(s).', $users->count()));

        return self::SUCCESS;
    }
    
}

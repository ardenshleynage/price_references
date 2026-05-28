<?php

use Illuminate\Database\Migrations\Migration;
/* use Illuminate\Database\Schema\Blueprint; */
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter les colonnes si pas déjà présentes
        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function ($table) {
                $table->string('username')->unique()->nullable()->after('id');
            });
        }

        if (! Schema::hasColumn('users', 'role')) {
            Schema::table('users', function ($table) {
                $table->integer('role')->default(2)->after('email');
                $table->datetime('last_time_connect')->nullable()->after('role');
                $table->integer('status')->default(1)->after('last_time_connect');
                $table->string('theme')->default('light')->after('status');
            });
        }
        // Copier les données de end_user vers users
        $endUsers = DB::table('end_user')->get();
        foreach ($endUsers as $endUser) {
            // Créer un email à partir du username si pas d'email
            $email = $endUser->username.'@example.com';

            // Insérer dans users (ou mettre à jour si déjà existant)
            DB::table('users')->updateOrInsert(
                ['username' => $endUser->username],
                [
                    'name' => $endUser->username,
                    'username' => $endUser->username,
                    'email' => $email,
                    'password' => $endUser->password,
                    'role' => $endUser->role,
                    'last_time_connect' => $endUser->last_time_connect,
                    'status' => $endUser->status,
                    'theme' => $endUser->theme ?? 'light',
                    'created_at' => $endUser->created_at,
                    'updated_at' => $endUser->updated_at,
                ]
            );
        }
    }

    public function down(): void
    {
        // Optionnel: supprimer les colonnes ajoutées
    }
};

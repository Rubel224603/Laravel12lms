<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;

class ImportMongoDbUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-mongo-db-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import MongoDB Json into Mysql';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $path = storage_path('app/inceptionbd.users.json');
        if (!File::exists($path)) {
            $this->error("File not found! Make sure inceptionbd.users.json is in storage/app/");
            return;
        }
        // Decode JSON into an array
        $users = json_decode(File::get($path), true);

        if (!is_array($users)) {
            $this->error("Invalid JSON format.");
            return;
        }
        foreach ($users as $user) {
            User::updateOrCreate([
                'email' => $user['email']
            ], [
                'name' => $user['name'] ?? 'No Name',
                'email' => $user['email'],
                'phone' => $user['phone'] ?? null,
                'password' => $user['password'] ?? Hash::make('123456'),
                'photo' => $user['photo'] ?? null,
                'roll' => $user['roll'] ?? "student",
                'status' => $user['status'] ?? "active",
                'isVerified' => $user['isVerified'] ?? false,
                'education' => $user['education'] ?? null,
                'experience' => $user['experience'] ?? null,
                'bio' => $user['bio'] ?? null,
                'jobTitle' => $user['jobTitle'] ?? null,
                // 'skills' => $user['skills'] ?? null,
                'skills' => isset($user['skills']) && is_array($user['skills'])
                    ? json_encode($user['skills'])
                    : ($user['skills'] ?? null),
                'facebookUrl' => $user['facebookUrl'] ?? null,
                'githubUrl' => $user['githubUrl'] ?? null,
                'linkedInUrl' => $user['linkedInUrl'] ?? null,
                'twitterUrl' => $user['twitterUrl'] ?? null,
                'youtubeUrl' => $user['youtubeUrl'] ?? null,
                'mongodb_id' => $user['mongodb_id'] ?? null,
                'created_at' => isset($u['createdAt']['$date']) ? Carbon::parse($u['createdAt']['$date']) : now(),
                'updated_at' => isset($u['updatedAt']['$date']) ? Carbon::parse($u['updatedAt']['$date']) : now(),

            ]);
        }
    }
}

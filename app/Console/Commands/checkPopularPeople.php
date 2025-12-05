<?php

namespace App\Console\Commands;

use App\Models\Person;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class CheckPopularPeople extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:popular'; // command yang akan dijalankan: php artisan check:popular

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email when a person gets more than 50 likes';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Ambil semua orang beserta jumlah likes yang diterima
        $people = Person::withCount(['likesReceived' => function ($q) {
            $q->where('type', 'like');
        }])->get();

        foreach ($people as $person) {
            if ($person->likes_received_count > 50) {
                // Kirim email ke admin (sesuaikan email di .env atau log)
                Mail::raw("Person {$person->name} has more than 50 likes!", function ($msg) {
                    $msg->to('admin@example.com')->subject('Popular Person Alert');
                });

                // Optional: tampilkan di console
                $this->info("Email sent for {$person->name}");
            }
        }

        return Command::SUCCESS;
    }
}

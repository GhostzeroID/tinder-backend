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
        $this->info('Checking for popular people...');
        $this->newLine();

        // Ambil semua orang beserta jumlah likes yang diterima
        $people = Person::withCount(['likesReceived' => function ($q) {
            $q->where('type', 'like');
        }])->get();

        $this->info("Total people in database: {$people->count()}");
        $this->newLine();

        $emailSent = 0;

        foreach ($people as $person) {
            $likesCount = $person->likes_received_count;
            
            if ($likesCount > 50) {
                // Kirim email ke admin (sesuaikan email di .env atau log)
                $emailBody = "Hello Admin,\n\n";
                $emailBody .= "This is an automated notification from the Tinder Backend System.\n\n";
                $emailBody .= "Person Details:\n";
                $emailBody .= "- Name: {$person->name}\n";
                $emailBody .= "- ID: {$person->id}\n";
                $emailBody .= "- Total Likes Received: {$likesCount}\n";
                $emailBody .= "- Location: {$person->location}\n\n";
                $emailBody .= "This person has exceeded the popularity threshold of 50 likes.\n";
                $emailBody .= "You may want to review this profile for potential promotion or featured placement.\n\n";
                $emailBody .= "Best regards,\n";
                $emailBody .= "Tinder Backend Notification System";

                Mail::raw($emailBody, function ($msg) use ($person) {
                    $msg->to('admin@example.com')->subject("Popular Person Alert: {$person->name}");
                });

                $this->info("[ALERT] {$person->name} (ID: {$person->id})");
                $this->line("   Likes received: {$likesCount}");
                $this->line("   Status: Popular (threshold exceeded)");
                $this->line("   Email sent to: admin@example.com");
                $this->newLine();
                
                $emailSent++;
            } else {
                $this->comment("[OK] {$person->name} - {$likesCount} likes (under threshold)");
            }
        }

        $this->newLine();
        if ($emailSent > 0) {
            $this->info("Total notification emails sent: {$emailSent}");
        } else {
            $this->warn("No popular people found (threshold: 50 likes)");
        }

        return Command::SUCCESS;
    }
}

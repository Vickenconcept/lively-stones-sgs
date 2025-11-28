<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $plainPassword;
    public string $role;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $plainPassword, string $role)
    {
        $this->user = $user;
        $this->plainPassword = $plainPassword;
        $this->role = $role;
    }

    /**
     * Build the message.
     */
    public function build(): self
    {
        return $this->subject('Welcome to De Gracious Lively Stones')
            ->markdown('emails.welcome')
            ->with([
                'user' => $this->user,
                'plainPassword' => $this->plainPassword,
                'role' => $this->role,
                'loginUrl' => config('app.url') . '/login',
            ]);
    }
}


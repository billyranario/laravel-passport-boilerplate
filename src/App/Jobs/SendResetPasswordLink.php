<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendResetPasswordLink implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var mixed $closure
     * @var array $data
     */
    private mixed $closure;
    private array $data;

    /**
     * Create a new job instance.
     * @param mixed $closure
     * @param array $data
     */
    public function __construct(mixed $closure, array $data)
    {
        $this->closure = $closure;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->closure->sendPasswordResetNotification($this->data);
    }
}

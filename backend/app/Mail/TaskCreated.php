<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }


    public function build()
    {
        return $this->subject('Nova Tarefa Criada - ' . $this->task->title)
            ->view('emails.task-created')
            ->with([
                'task' => $this->task,
                'user' => $this->task->user,
                'company' => $this->task->company,
            ]);
    }
}

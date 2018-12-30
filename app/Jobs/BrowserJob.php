<?php

namespace App\Jobs;

use App\Jobs\Robinhood\Tasks\BaseTask;
use App\Traits\BrowserScaffold;
use App\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class BrowserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BrowserScaffold;

    protected $user;

    protected $tags;

    protected $tasks;

    public function __construct(User $user = null, $tags = [])
    {
        $this->user = $user;
        $this->tags = collect(array_merge($tags, ['Browser Job']));
        $this->addTaskTags();
    }

    public function addTag($tag)
    {
        $this->tags->push($tag);
    }

    private function addTaskTags()
    {
        foreach($this->tasks as $task){
            $this->addTag(str_replace('App\Jobs\Robinhood\Tasks\\', '', get_class($task)));
        }
    }

    public function tags()
    {
        return $this->tags->toArray();
    }

    public function handle(User $user = null)
    {
        foreach($this->tasks as $task){
            if ($task instanceof BaseTask) {
                $task->execute($this->user ?: $user);
                session()->flush();
            } else {
                return new Exception('All tasks must be instances of BaseTask');
            }
        }
    }

}
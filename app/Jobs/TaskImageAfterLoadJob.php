<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use App\Classes\FolkProcessorStrategy;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Classes\InterventionProcessorStrategy;

class TaskImageAfterLoadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    public $image_name = '';
    public $image_id = '';
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($image_name, $image_id)
    {
        $this->image_name = $image_name;
        $this->image_id = $image_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $image_engine = env('IMAGE_ENGINE', 'intervention');

        Log::debug("Using engine:" . $image_engine);
        
        if($image_engine == 'intervention') {
            $imageStrategy = new InterventionProcessorStrategy();
        } else if($image_engine == 'folk') {
            $imageStrategy = new FolkProcessorStrategy();
        }

        $imageStrategy->cropMobile($this->image_name, $this->image_id);
        $imageStrategy->cropDesktop($this->image_name, $this->image_id);
        
        Log::debug("Cropping done.");
    }
}

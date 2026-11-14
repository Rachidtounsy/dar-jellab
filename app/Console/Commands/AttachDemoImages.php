<?php

namespace App\Console\Commands;

use App\Support\DemoImages;
use Illuminate\Console\Command;

class AttachDemoImages extends Command
{
    protected $signature = 'demo:images';

    protected $description = 'Attach the demo jellaba illustrations to the demo products that have no image yet';

    public function handle(): int
    {
        $this->info(DemoImages::attach().' change(s) applied (images attached or files restored).');

        return self::SUCCESS;
    }
}

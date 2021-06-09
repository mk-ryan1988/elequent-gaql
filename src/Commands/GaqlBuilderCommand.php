<?php

namespace MkRyan1988\EloquentGaql\Commands;

use Illuminate\Console\Command;

class GaqlBuilderCommand extends Command
{
    public $signature = 'eloquent-gaql';

    public $description = 'My command';

    public function handle()
    {
        $this->comment('All done');
    }
}

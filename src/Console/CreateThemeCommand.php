<?php
namespace Xiso\InertiaUI\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateThemeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xiso:ui-create-theme {themeName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "create new theme for inertia";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $target = __DIR__ . '/../Services/Example';
        $shortcut = resource_path(sprintf('themes/%s',$this->argument('themeName')));

        $this->info(sprintf("try create new theme %s", $this->argument('themeName')));
        $this->info(sprintf("path : %s", $shortcut));

        $this->info($target);

        File::copyDirectory($target,$shortcut);

        return Command::SUCCESS;
    }
}

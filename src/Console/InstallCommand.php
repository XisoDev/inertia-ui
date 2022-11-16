<?php
namespace Xiso\InertiaUI\Console;

use Illuminate\Console\Command;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xiso:ui-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Create Symlink for JetStream";

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $target = __DIR__ . '/../FieldLoader';
        $shortcut = resource_path('FieldLoader');

        $this->info(sprintf("try create symlink %s to %s",$target, $shortcut));
        symlink($target, $shortcut);

        return Command::SUCCESS;
    }
}

<?php
namespace Xiso\InertiaUI\Console;

use Illuminate\Console\Command as BaseCommand;

class InstallCommand extends BaseCommand
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

        if(!file_exists($shortcut)) {
            $this->info(sprintf("try create symlink %s to %s", $target, $shortcut));
            symlink($target, $shortcut);
        }else{
            $this->info(sprintf('skipped create sym link %s (file exists)',$shortcut));
        }

        return BaseCommand::SUCCESS;
    }
}

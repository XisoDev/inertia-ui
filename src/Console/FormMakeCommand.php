<?php
namespace Xiso\InertiaUI\Console;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class FormMakeCommand extends GeneratorCommand
{
    protected $signature = "make:dto {name : The DTO Name}";

    protected $description = "Create a new DTO";

    protected $type = 'Data Transfer Object';

    protected function getStub(): string
    {
        $readonly = Str::contains(
            haystack: PHP_VERSION,
            needles: '8.2',
        );

        $file = $readonly ? 'dto-82.stub' : 'dto.stub';

        return __DIR__ . "/../../../stubs/{$file}";
    }

    protected function getDefaultNamespace($rootNamespace): string
    {
        return "{$rootNamespace}\\DataObjects";
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Cria um repositório e sua interface correspondente';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle()
    {
        $name = $this->argument('name');
        $interfaceName = "\\Repositories\\Contracts\\{$name}RepositoryInterface";
        $repositoryName = "\\Repositories\\{$name}Repository";

        $this->createInterface($interfaceName);
        $this->createRepository($repositoryName, $interfaceName);

        $this->info("Repositório {$repositoryName} e {$interfaceName}  criado com sucesso!");
    }

    private function createInterface($interfaceName)
    {
        $path = app_path(str_replace('\\', '/', "{$interfaceName}.php"));

        if ($this->files->exists($path)) {
            $this->error("A interface {$interfaceName} já existe!");
            return;
        }

        $stub = $this->getStub('interface');
        $content = str_replace('{{ interfaceName }}', $interfaceName, $stub);

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $content);
    }

    private function createRepository($repositoryName, $interfaceName)
    {
        $path = app_path(str_replace('\\', '/', "{$repositoryName}.php"));

        if ($this->files->exists($path)) {
            $this->error("O repositório {$repositoryName} já existe!");
            return;
        }

        $stub = $this->getStub('repository');
        $content = str_replace(
            ['{{ repositoryName }}', '{{ interfaceName }}'],
            [$repositoryName, $interfaceName],
            $stub
        );

        $this->files->ensureDirectoryExists(dirname($path));
        $this->files->put($path, $content);
    }

    private function getStub($type)
    {
        return $type === 'interface'
            ? <<<EOT
<?php

namespace {{ interfaceName }};

interface {{ interfaceName }}
{
    // Defina os métodos aqui
}
EOT
            : <<<EOT
<?php

namespace {{ repositoryName }};

use {{ interfaceName }};

class {{ repositoryName }} implements {{ interfaceName }}
{
    // Implementação dos métodos aqui
}
EOT;
    }
}

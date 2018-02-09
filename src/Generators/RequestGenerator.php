<?php


namespace Lucid\Console\Generators;

use Exception;
use Lucid\Console\Str;
use Lucid\Console\Components\Request;


/**
 * Class RequestGenerator
 *
 * @author Bernat Jufré <info@behind.design>
 *
 * @package Lucid\Console\Generators
 */
class RequestGenerator extends Generator
{
    /**
     * Generate the file.
     *
     * @param string $name
     * @param string $service
     * @return Request|bool
     * @throws Exception
     */
    public function generate($name, $service)
    {
        $request = Str::request($name);
        $service = Str::service($service);
        $path = $this->findRequestPath($service, $request);

        if ($this->exists($path)) {
            throw new Exception('Request already exists');

            return false;
        }

        $namespace = $this->findRequestsNamespace($service);

        $content = file_get_contents($this->getStub());
        $content = str_replace(
            ['{{request}}', '{{namespace}}', '{{foundation_namespace}}'],
            [$request, $namespace, $this->findFoundationNamespace()],
            $content
        );

        $this->createFile($path, $content);

        return new Request(
            $request,
            $service,
            $namespace,
            basename($path),
            $path,
            $this->relativeFromReal($path),
            $content
        );
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    public function getStub()
    {
        return __DIR__ . '/../Generators/stubs/request.stub';
    }
}
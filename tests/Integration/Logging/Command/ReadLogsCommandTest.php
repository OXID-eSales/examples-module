<?php

/**
 * Copyright © . All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\ExamplesModule\Tests\Integration\Logging\Command;

use org\bovigo\vfs\vfsStream;
use OxidEsales\ExamplesModule\Logging\Command\ReadLogsCommand;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

#[CoversClass(ReadLogsCommand::class)]
final class ReadLogsCommandTest extends TestCase
{
    private const VFS_ROOT_DIRECTORY = 'vfsRoot';

    #[Test]
    public function execute(): void
    {
        $fileContents = 'Test log file contents...';
        $basketLogFilePath = $this->getVirtualTestLogFilePath($fileContents);

        $sut = $this->getSut(
            logFilePath: $basketLogFilePath,
        );

        $application = new Application();
        $application->add($sut);

        $commandTester = new CommandTester($sut);
        $commandTester->execute([]);

        $this->assertSame(Command::SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString($fileContents, $commandTester->getDisplay());
    }

    #[Test]
    public function executeLogNotFound(): void
    {
        $nonExistentFilePath = '/none/existent/file.log';

        $sut = $this->getSut(
            logFilePath: $nonExistentFilePath,
        );

        $application = new Application();
        $application->add($sut);

        $commandTester = new CommandTester($sut);
        $commandTester->execute([]);

        $this->assertSame(Command::FAILURE, $commandTester->getStatusCode());
        $this->assertStringContainsString('was not found', $commandTester->getDisplay());
    }

    private function getSut(
        string $logFilePath,
    ): ReadLogsCommand {
        return new ReadLogsCommand(
            logFilePath: $logFilePath,
        );
    }

    /**
     * Use VfsStream to not write to file system.
     */
    private function getVirtualTestLogFilePath(string $fileContents): string
    {
        $vfsRoot = vfsStream::setup(self::VFS_ROOT_DIRECTORY);

        $fileName = 'basket.txt';
        vfsStream::newFile($fileName, 0755)
            ->withContent($fileContents)
            ->at($vfsRoot);

        $this->assertTrue($vfsRoot->hasChild($fileName));
        $this->assertSame($fileContents, $vfsRoot->getChild($fileName)->getContent());

        return vfsStream::url(self::VFS_ROOT_DIRECTORY . DIRECTORY_SEPARATOR . $fileName);
    }
}

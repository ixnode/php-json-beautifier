<?php

declare(strict_types=1);

/*
 * MIT License
 *
 * Copyright (c) 2021 Björn Hempel <bjoern@hempel.li>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace App\Command;

use App\Exception\UnexpectedFalseGivenException;
use App\Exception\UnexpectedNullGivenException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PhpVersionCommand
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 1.1 (2021-09-14)
 * @package App\Command
 */
class PhpVersionCommand extends Command
{
    protected static $defaultName = 'app:php:version';

    public const TEMPLATE_PHP_VERSION = 'PHP version: %d.%d.%d';

    /**
     * Configures this command.
     *
     * @return void
     * @throws UnexpectedNullGivenException
     */
    protected function configure(): void
    {
        if (self::$defaultName === null) {
            throw new UnexpectedNullGivenException();
        }

        $this
            ->setName(self::$defaultName)
            ->setDescription('Shows PHP version to command line');
    }

    /**
     * Execute this command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws UnexpectedFalseGivenException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $phpVersion = phpversion();

        if ($phpVersion === false) {
            throw new UnexpectedFalseGivenException();
        }

        $version = explode('.', $phpVersion);

        $output->writeln(sprintf(self::TEMPLATE_PHP_VERSION, $version[0], $version[1], $version[2]));

        return Command::SUCCESS;
    }
}

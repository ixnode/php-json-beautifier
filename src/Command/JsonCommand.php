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

use App\Exception\InvalidJsonGivenException;
use App\Exception\InvalidPositiveIntegerGivenException;
use App\Exception\UnexpectedFalseGivenException;
use App\Exception\UnexpectedNullGivenException;
use App\Utils\JsonFormatter;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class JsonCommand
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 1.1 (2021-10-16)
 * @package App\Command
 */
class JsonCommand extends Command
{
    protected static $defaultName = 'app:json:beautify';

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
            ->setDescription('Converts the given JSON value.')
            ->setDefinition([
                new InputArgument('json', InputArgument::OPTIONAL, 'The JSON to beautify.', null),
            ]);
    }

    /**
     * Returns the STDIN if given.
     *
     * @return ?string
     * @throws Exception
     */
    protected function getStdin(): ?string
    {
        $fh = fopen('php://stdin', 'r');

        if ($fh === false) {
            throw new Exception(sprintf('Unexpected fopen result (%s:%d)', __FILE__, __LINE__));
        }

        stream_set_blocking($fh, false);

        $stdin = fgets($fh);

        if ($stdin === false) {
            return null;
        }

        return $stdin;
    }

    /**
     * Execute this command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws InvalidJsonGivenException
     * @throws InvalidPositiveIntegerGivenException
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /* Try to JSON from STDIN */
        $json = $this->getStdin();

        /* Try to get JSON from argument */
        if ($json === null) {
            $json = $input->getArgument('json');
        }

        /* Expect non null value */
        if ($json === null) {
            throw new Exception('Please specify the JSON value via STDIN or parameter.');
        }

        $jsonFormatter = new JsonFormatter($json);

        $output->writeln($jsonFormatter->beautify());

        return Command::SUCCESS;
    }
}

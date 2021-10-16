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

namespace App\Writer;

use RuntimeException;
use Version\Version;
use Shivas\VersioningBundle\Writer\WriterInterface;

/**
 * Class VersionWriter
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 1.0 (2021-10-10)
 * @package App\Writer
 */
class EnvVersionWriter implements WriterInterface
{
    private string $path;

    public const NAME_APP_VERSION = 'APP_VERSION=%s';

    public const REGEXP = '~^APP_VERSION=(.+)$~m';

    public const TEMPLATE_ADD = <<<TEMPLATE

# App version
APP_VERSION=%s
TEMPLATE;


    /**
     * EnvVersionWriter constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Returns the path of the VERSION file.
     *
     * @return string
     */
    protected function getVersionFilePath(): string
    {
        return sprintf('%s%s%s', $this->path, DIRECTORY_SEPARATOR, 'VERSION');
    }

    /**
     * Returns the path of the .env file.
     *
     * @return string
     */
    protected function getEnvFilePath(): string
    {
        return sprintf('%s%s%s', $this->path, DIRECTORY_SEPARATOR, '.env');
    }

    /**
     * Returns the content of the VERSION file.
     *
     * @param Version $version
     * @return string
     */
    protected function getVersionFileContent(Version $version): string
    {
        return $version->toString();
    }

    /**
     * Returns the content of the new .env file.
     *
     * @param Version $version
     * @return string
     */
    protected function getEnvFileContent(Version $version): string
    {
        $filename = $this->getEnvFilePath();

        $envFileContent = file_get_contents($filename);

        if (false === $envFileContent) {
            throw new RuntimeException(sprintf('Reading "%s" failed', $filename));
        }

        $matches = [];

        if (preg_match(self::REGEXP, $envFileContent, $matches)) {
            $replaced = preg_replace(self::REGEXP, sprintf(self::NAME_APP_VERSION, $version->toString()), $envFileContent);

            if ($replaced !== null) {
                $envFileContent = $replaced;
            }
        } else {
            $envFileContent .= sprintf(self::TEMPLATE_ADD, $version->toString());
        }

        return $envFileContent;
    }

    /**
     * Writes the VERSION file.
     *
     * @param Version $version
     * @return bool
     */
    public function writeVersionFile(Version $version): bool
    {
        file_put_contents($this->getVersionFilePath(), $this->getVersionFileContent($version));

        return true;
    }

    /**
     * Writes the .env file.
     *
     * @param Version $version
     * @return bool
     */
    public function writeEnvFile(Version $version): bool
    {
        file_put_contents($this->getEnvFilePath(), $this->getEnvFileContent($version));

        return true;
    }

    /**
     * Writes the version files.
     *
     * @param Version $version
     */
    public function write(Version $version): void
    {
        $this->writeEnvFile($version);
        $this->writeVersionFile($version);
    }
}

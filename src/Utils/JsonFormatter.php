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

namespace App\Utils;

use App\Exception\InvalidJsonGivenException;
use App\Exception\InvalidPositiveIntegerGivenException;
use Exception;

/**
 * Class JsonFormatter
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 1.0 (2021-09-13)
 * @package App\Validator
 */
class JsonFormatter
{
    public const OPTION_INDENT = 4;

    protected string $json;

    protected int $indent;

    /**
     * JsonFormatter constructor.
     *
     * @param string $json
     * @param int $indent
     * @throws InvalidJsonGivenException
     * @throws InvalidPositiveIntegerGivenException
     */
    public function __construct(string $json, int $indent = self::OPTION_INDENT)
    {
        if (!self::isJson($json)) {
            throw new InvalidJsonGivenException($json);
        }

        if ($indent < 0) {
            throw new InvalidPositiveIntegerGivenException($indent);
        }

        $this->json = $json;
        $this->indent = $indent;
    }

    /**
     * Beautify given json string.
     *
     * @return string
     * @throws Exception
     */
    public function beautify(): string
    {
        $jsonObject = json_decode($this->json);

        // Beautify the given JSON.
        $json = json_encode($jsonObject, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        if ($json === false) {
            throw new Exception('Unable to encode given object.');
        }

        return self::reformatIndent($json, $this->indent);
    }

    /**
     * Formats the indent of given json
     *
     * @param string $json
     * @param int $indent
     * @return string
     * @throws Exception
     */
    protected static function reformatIndent(string $json, int $indent = self::OPTION_INDENT): string
    {
        $formattedJson = preg_replace_callback('/^([ ])+/m', function (array $matches) use ($indent) {
            $indentNumber = intval(strlen($matches[0]) / 4);
            return str_repeat(' ', $indent * $indentNumber);
        }, $json);

        if ($formattedJson === null) {
            throw new Exception('Unable to use callback.');
        }

        return $formattedJson;
    }

    /**
     * Checks if given json is valid.
     *
     * @param string $json
     * @return bool
     */
    protected static function isJson(string $json): bool
    {
        json_decode($json);

        return json_last_error() === JSON_ERROR_NONE;
    }
}

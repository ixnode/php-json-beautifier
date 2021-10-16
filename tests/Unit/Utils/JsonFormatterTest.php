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

namespace App\Tests\Unit\Utils;

use App\Exception\InvalidJsonGivenException;
use App\Exception\InvalidPositiveIntegerGivenException;
use App\Utils\JsonFormatter;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Throwable;

/**
 * Class AppVersionTest
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 1.0 (2021-09-04)
 * @package App\Tests\Unit\Utils
 */
final class JsonFormatterTest extends WebTestCase
{
    /**
     * Test wrapper.
     *
     * @test
     * @testdox $number) Test JsonFormatter class ($title).
     * @dataProvider dataProvider
     * @param int $number
     * @param string $title
     * @param string $givenJson
     * @param string $expectedJson
     * @param int $indent
     * @param ?class-string<Throwable> $exception
     * @param ?int $exceptionCode
     * @param ?string $exceptionMessage
     * @return void
     * @throws InvalidJsonGivenException
     * @throws InvalidPositiveIntegerGivenException
     */
    public function version(int $number, string $title, string $givenJson, string $expectedJson, int $indent = JsonFormatter::OPTION_INDENT, ?string $exception = null, ?int $exceptionCode = null, ?string $exceptionMessage = null): void
    {
        /* Check exception */
        if ($exception !== null) {
            $this->expectException($exception);
        }
        if ($exceptionCode !== null) {
            $this->expectExceptionCode($exceptionCode);
        }
        if ($exceptionMessage !== null) {
            $this->expectExceptionMessage(sprintf($exceptionMessage, $givenJson));
        }

        /* Arrange */
        $jsonFormatter = new JsonFormatter($givenJson, $indent);

        /* Act */
        $formattedJson = $jsonFormatter->beautify();

        /* Assert */
        $this->assertInstanceOf(JsonFormatter::class, $jsonFormatter);
        $this->assertEquals($expectedJson, $formattedJson);
    }

    /**
     * Data provider.
     *
     * @return array[]
     */
    public function dataProvider(): array
    {
        $number = 0;

        return [

            /**
             * 1) Empty JSON's
             */
            [++$number, 'Empty JSON', '{}', '{}', ],
            [++$number, 'Empty JSON', '{

}', '{}', ],

            /**
             * 2) Invalid JSON's
             */
            [++$number, 'Invalid JSON', '{":123}', '{}', JsonFormatter::OPTION_INDENT, InvalidJsonGivenException::class, InvalidJsonGivenException::ERROR_CODE, InvalidJsonGivenException::ERROR_MESSAGE],

            /**
             * 3) Simple JSON's (indent: 4)
             */
            [++$number, 'Simple JSON (indent: 4)', '{"key1": "value1", "key2": "value2", "key3": "value3"}', '{
    "key1": "value1",
    "key2": "value2",
    "key3": "value3"
}', ],
            [++$number, 'Simple JSON (indent: 4)', '{"key1": "value1", "key2": "value2", "key3": [1, 2, 3]}', '{
    "key1": "value1",
    "key2": "value2",
    "key3": [
        1,
        2,
        3
    ]
}', ],

            /**
             * 4) Simple JSON's (indent: 2)
             */
            [++$number, 'Simple JSON (indent: 2)', '{"key1": "value1", "key2": "value2", "key3": "value3"}', '{
  "key1": "value1",
  "key2": "value2",
  "key3": "value3"
}', 2, ],
            [++$number, 'Simple JSON (indent: 2)', '{"key1": "value1", "key2": "value2", "key3": [1, 2, 3]}', '{
  "key1": "value1",
  "key2": "value2",
  "key3": [
    1,
    2,
    3
  ]
}', 2, ],
        ];
    }
}

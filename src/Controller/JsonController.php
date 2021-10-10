<?php declare(strict_types=1);

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

namespace App\Controller;

use App\Entity\Json;
use App\Exception\InvalidJsonGivenException;
use App\Exception\InvalidPositiveIntegerGivenException;
use App\Form\Type\JsonType;
use App\Utils\JsonFormatter;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Annotation\Route;

class JsonController extends AbstractController
{
    const NAME_APP_VERSION = 'app.version';

    /**
     * Shows the json form.
     *
     * @param Request $request
     * @param KernelInterface $appKernel
     * @return Response
     * @throws InvalidJsonGivenException
     * @throws InvalidPositiveIntegerGivenException
     * @throws Exception
     */
    #[Route(path: '/', name: 'json_convert', methods: ['GET', 'POST'])]
    public function form(Request $request, KernelInterface $appKernel): Response
    {
        // Build JSON form.
        $json = new Json();
        $json->setJson('{"example": "value 123"}');

        // Creates the JSON form
        $form = $this->createForm(JsonType::class, $json);

        // Handle form request
        $form->handleRequest($request);

        // Beautify the given JSON
        if ($form->isSubmitted() && $form->isValid()) {
            $jsonFormatter = new JsonFormatter($form->getData()->getJson());

            $json->setJson($jsonFormatter->beautify());

            $form = $this->createForm(JsonType::class, $json);
        }

        return $this->render('json/form.html.twig', [
            'form' => $form->createView(),
            'appVersion' => $this->getParameter(self::NAME_APP_VERSION)
        ]);
    }
}

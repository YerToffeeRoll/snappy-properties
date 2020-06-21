<?php

namespace App\Controller;

use App\Container;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractController
{
    /**
     * Render template.
     *
     * @param string $view
     * @param array $parameters
     * @return Response
     */
    public function render(string $view, array $parameters = []): Response
    {
        $twig = Container::get('Twig\Environment');

        $content = $twig->render($view, $parameters);

        return (new Response)->setContent($content);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param string $url
     * @param int $status
     * @return RedirectResponse
     */
    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Add message and flash to session.
     *
     * @param string $type
     * @param string $message
     */
    protected function addFlash(string $type, string $message): void
    {
        $session = Container::get('Symfony\Component\HttpFoundation\Session\Session');

        $session->getFlashBag()->add($type, $message);
    }
}

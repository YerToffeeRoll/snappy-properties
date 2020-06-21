<?php

namespace App;

use App\Database\DatabaseManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Kernel
{
    protected RouteCollection $routes;

    /**
     * Kernel constructor.
     */
    public function __construct()
    {
        $this->boot();
    }

    /**
     * Boot the kernel.
     */
    public function boot(): void
    {
        $session = new Session();
        $session->start();

        $loader = new FilesystemLoader(__DIR__ . '/../templates');
        $twig = new Environment($loader, [
            'cache' => __DIR__ . '/../var/cache',
            'debug' => true
        ]);

        Container::add('Symfony\Component\HttpFoundation\Session\Session', $session);
        Container::add('Twig\Environment', $twig);

        $twig->addGlobal('app', ['flashes' => $session->getFlashBag()->all()]);

    }

    /**
     * Handles the request.
     *
     * @param Request $request
     * @return Response
     */
    public function handle(Request $request): Response
    {
        Container::add('Symfony\Component\HttpFoundation\Request', $request);

        try {
            $context = (new RequestContext)->fromRequest($request);
            $matcher = new UrlMatcher($this->routes, $context);
            $parameters = $matcher->matchRequest($request);

            $class = $parameters['_controller'];

            $reflectionClass = new \ReflectionClass($class);
            $method = $reflectionClass->getMethod($parameters['_method']);

            $arguments = [];

            foreach ($method->getParameters() as $parameter) {
                if (!empty($parameter->getClass()->name)) {
                    $arguments[] = Container::get($parameter->getClass()->name);
                } else {
                    $arguments[] = $parameters[$parameter->name];
                }
            }

            return (new $class(new DatabaseManager))->{$parameters['_method']}(...$arguments);

        } catch (ResourceNotFoundException $exception){
            return $this->displayErrors($exception->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (\Throwable $exception) {
            return $this->displayErrors($exception->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display errors.
     *
     * @param $message
     * @param $status
     * @return Response
     */
    protected function displayErrors($message, $status): Response
    {
        $twig = Container::get('Twig\Environment');

        $content = $twig->render('errors/index.html.twig', [
            'error' => [
                'status' => $status,
                'message' => $message
            ]
        ]);

        return (new Response)->setContent($content);
    }
}

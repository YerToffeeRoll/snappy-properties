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

        $twig->addGlobal('app', ['flashes' => $session->getFlashBag()->all()]);

        $route1 = new Route('/admin/properties', ['_controller' => \App\Controller\AdminPropertyController::class, '_method' => 'index']);
        $route2 = new Route('/admin/properties/create', ['_controller' => \App\Controller\AdminPropertyController::class, '_method' => 'create']);
        $route3 = new Route('/admin/properties/{id}/edit', ['_controller' => \App\Controller\AdminPropertyController::class, '_method' => 'edit']);
        $route4 = new Route('/admin/properties/{id}/delete', ['_controller' => \App\Controller\AdminPropertyController::class, '_method' => 'delete']);
        $route5 = new Route('/admin/properties/import', ['_controller' => \App\Controller\AdminPropertyImportController::class, '_method' => 'import']);

        $routes = new RouteCollection();
        $routes->add('admin_properties', $route1);
        $routes->add('admin_properties_create', $route2);
        $routes->add('admin_properties_edit', $route3);
        $routes->add('admin_properties_delete', $route4);
        $routes->add('admin_properties_import', $route5);

        $this->routes = $routes;

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

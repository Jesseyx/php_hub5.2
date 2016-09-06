<?php

/*
 * This file is part of OAuth 2.0 Laravel.
 *
 * (c) Luca Degasperi <packages@lucadegasperi.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Auth\Auth as Authentication;
use Dingo\Api\Routing\Router;
use League\OAuth2\Server\Exception\AccessDeniedException;
use League\OAuth2\Server\Exception\InvalidScopeException;
use LucaDegasperi\OAuth2Server\Authorizer;

/**
 * This is the oauth middleware class.
 *
 * @author Luca Degasperi <packages@lucadegasperi.com>
 */
class OAuthMiddleware
{
    /**
     * The Authorizer instance.
     *
     * @var \LucaDegasperi\OAuth2Server\Authorizer
     */
    protected $authorizer;

    private $router;
    private $auth;

    /**
     * Create a new oauth middleware instance.
     *
     * @param \LucaDegasperi\OAuth2Server\Authorizer $authorizer
     * @param \Dingo\Api\Routing\Router              $router
     * @param \Dingo\Api\Auth\Auth                   $auth
     */
    public function __construct(Authorizer $authorizer, Router $router, Authentication $auth)
    {
        $this->authorizer = $authorizer;
        $this->router = $router;
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $type
     * @throws \League\OAuth2\Server\Exception\AccessDeniedException
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $type = null)
    {
        $route = $this->router->getCurrentRoute();

        if (!$this->auth->check(false)) {
            $this->auth->authenticate($route->getAuthenticationProviders());
        }

        $this->authorizer->setRequest($request);

        // type: user or client
        if ($type && $this->authorizer->getResourceOwnerType() !== $type) {
            throw new AccessDeniedException();
        }

        return $next($request);
    }
}

<?php

namespace JwtApi\Laravel\Guards;

use Illuminate\Auth\GuardHelpers;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Support\Traits\Macroable;
use JwtApi\Laravel\Client;
use JwtApi\Laravel\ClientRepository;
use JwtApi\Server\RequestParser;

class RequestGuard implements Guard
{
    use GuardHelpers, Macroable;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var ClientRepository
     */
    protected $clients;

    /**
     * @var UserProvider
     */
    protected $provider;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var RequestParser
     */
    protected $requestParser;

    /**
     * @var Authenticatable|null
     */
    protected $user;

    /**
     * @var boolean
     */
    protected $validated;

    public function __construct(Request $request, RequestParser $requestParser, UserProvider $provider, ClientRepository $clients)
    {
        $this->setRequest($request);

        $this->requestParser = $requestParser;
        $this->provider = $provider;
        $this->clients = $clients;
    }

    protected function validatedRequest(): RequestParser
    {
        if ($this->validated) {
            return $this->requestParser;
        }

        $this->requestParser->setRequest($this->request);
        $this->requestParser->verify();

        $this->validated = true;

        return $this->requestParser;
    }

    public function client(): ?Client
    {
        if ($this->client !== null) {
            return $this->client;
        }

        $client = $this->clients->find($this->validatedRequest()->getApiKey());

        if ($client) {
            return $this->client = $client;
        }

        return null;
    }

    public function user(): ?Authenticatable
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $user = $this->provider->retrieveById($this->getClaim('user'));

        if ($user) {
            return $this->user = $user;
        }

        return null;
    }

    /**
     * @return mixed
     */
    public function getClaim(string $name, $default = null)
    {
        return $this->validatedRequest()->getClaim($name, $default);
    }

    public function validate(array $credentials = []): bool
    {
        return (new static($credentials['request'], $this->requestParser, $this->provider, $this->clients))->user() !== null;
    }

    public function setRequest(Request $request): self
    {
        $this->request = $request;

        $this->validated = false;

        return $this;
    }
}

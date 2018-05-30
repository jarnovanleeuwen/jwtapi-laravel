<?php

namespace JwtApi\Laravel;

class ClientRepository
{
    public function find(string $apiKey): ?Client
    {
        return Client::where('api_key', $apiKey)->first();
    }
}

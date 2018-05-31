<?php

namespace JwtApi\Laravel;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

trait IsApiUser
{
    /**
     * Get all of the user's attached API clients.
     */
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class, 'api_client_user', 'user_id', 'api_client_id');
    }
}

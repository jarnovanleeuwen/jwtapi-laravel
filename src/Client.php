<?php

namespace JwtApi\Laravel;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_clients';

    /**
     * @var array
     */
    protected $casts = [
        'last_activity' => 'datetime'
    ];

    /**
     * @var array
     */
    protected $hidden = ['api_key'];

    public function users(): BelongsToMany
    {
        $provider = config('auth.guards.api.provider');

        return $this->belongsToMany(config('auth.providers.'.$provider.'.model'));
    }

    /**
     * Touch the last activity timestamp.
     */
    public function used()
    {
        $this->last_activity = Carbon::now();
        $this->save();
    }
}

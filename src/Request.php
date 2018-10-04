<?php

namespace JwtApi\Laravel;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    /**
     * @var string
     */
    protected $table = 'api_requests';

    /**
     * @var array
     */
    protected $hidden = ['api_key'];

    /**
     * @var array
     */
    protected $fillable = ['api_client_id', 'request', 'response'];

    public function client(): Belongs
    {
        return $this->belongsTo(Client::class);
    }
}

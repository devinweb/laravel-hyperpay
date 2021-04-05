<?php

namespace Devinweb\LaravelHyperpay\Models;

use Carbon\Carbon;
use Devinweb\LaravelHyperpay\Traits\HasUniqID;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasUniqID;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Disable auto-increment.
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * Specify The type of the primary key ID..
     *
     * @var string
     */

    protected $keyType = 'string';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = ['data'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'trackable_data' => 'array'
    ];

    

    /**
     * Get the user that owns the transation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->owner();
    }

    /**
     * Determine if the transaction is pending
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->status == 'pending';
    }


    /**
    * Determine if the transaction is paid
    *
    * @return bool
    */
    public function isPaid()
    {
        return $this->status == 'paid';
    }

    /**
     * Scope a query to only include pending transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include overdue transactions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOverdue($query)
    {
        return $query->where('created_at', '<', Carbon::now()->subMinutes(29));
    }
    
    /**
     * Get the model related to the subscription.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        $model = config('hyperpay.model');

        return $this->belongsTo($model, (new $model)->getForeignKey());
    }
}

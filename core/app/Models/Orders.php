<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

    /**
     * table name
     *
     * @var string
     */
    protected $table = 'orders';

    /**
     * primary key field
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * fields
     *
     * @var array
     */
    protected $fillable = ['customer_name','customer_email','customer_mobile','request_id','status'];

    /**
     * dates audit
     *
     * @var boolean
     */
    public $timestamps = true;
}

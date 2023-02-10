<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

abstract class Cities extends Model implements GlobalModelInterface
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string[]
     */
    protected $fillable = ['city'];
}
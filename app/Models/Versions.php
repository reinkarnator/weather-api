<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Versions extends Model implements GlobalModelInterface
{
    /**
     * @var bool
     */
    public $timestamps = false;
    /**
     * @var string[]
     */
    protected $fillable = ['version'];
}
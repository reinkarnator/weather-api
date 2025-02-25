<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class Statistic extends Model implements GlobalModelInterface
{
    use HasFactory;

    const UPDATED_AT = null;
    /**
     * @var string
     */
    protected $table = 'statistic';
    /**
     * @var string[]
     */
    protected $fillable = ['city_id', 'version_id', 'service_id', 'temperature'];

}
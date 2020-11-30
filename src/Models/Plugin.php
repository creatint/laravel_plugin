<?php


namespace Gallery\Plugin\Models;

use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    protected $casts = [
        'title_i18n' => 'json',
        'description_i18n' => 'json',
        'options' => 'json'
    ];
}

<?php

namespace EvansKim\Translator;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        parent::setTable(config("translator.table"));
    }
    public $timestamps = false;
    protected $fillable = ['name','group','value','locale'];

}

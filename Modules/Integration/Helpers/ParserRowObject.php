<?php


namespace Modules\Integration\Helpers;


use Illuminate\Database\Eloquent\Model;

abstract class ParserRowObject
{

    public $model;

    public  function __construct(Model $model) {
        $this->model = $model;
    }

}

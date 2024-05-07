<?php

namespace app\core\form;

use app\core\Model;

class TextareaField extends BaseField
{
    public const TYPE_TEXT = 'text';

    public string $type;
    public Model $model;
    public string $attribute;

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->type = self::TYPE_TEXT;
        parent::__construct($model, $attribute);
    }

    public function renderInput(): string
    {
        return sprintf('
                <textarea name="%s" value="%s" class="form-control%s"> </textarea>
        ',
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
        );
    }
}
<?php

namespace janm\phpmvc\form;

use janm\phpmvc\Model;

abstract class BaseField
{
    public string $type;
    public Model $model;
    public string $attribute;

    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(Model $model, string $attribute)
    {
        $this->model = $model;
        $this->attribute = $attribute;
    }
    abstract public function renderInput(): string;

    public function __toString() // megic method -> jest automatycznie wywoływana, gdy obiekt jest używany w kontekście, gdzie oczekiwany jest string
    {
        return sprintf('
            <div class="form_group">
                <label>%s</label>
                %s
                <div class="invalid-feedback"> %s </div>
            </div>
        ',
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)

        );
    }
}
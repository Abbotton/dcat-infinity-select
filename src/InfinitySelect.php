<?php

namespace Dcat\Admin\Extension\DcatInfinitySelect;

use Dcat\Admin\Form\Field\Select;

class InfinitySelect extends Select
{
    protected $view = 'dcat-infinity-select::index';

    public function list(string $list)
    {
        $this->addVariables(['valueList' => $list]);

        return $this;
    }
}

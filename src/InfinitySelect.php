<?php

namespace Dcat\Admin\Extension\DcatInfinitySelect;

use Dcat\Admin\Form\Field\Select;

class InfinitySelect extends Select
{
    protected $view = 'dcat-infinity-select::index';

    /**
     * 设定有序链表的值.
     *
     * @param  string  $list
     * @return $this
     */
    public function list(string $list)
    {
        $this->addVariables(['valueList' => $list]);

        return $this;
    }

    /**
     * 设定有序链表的字段名称.
     *
     * @param  string  $listName
     * @return $this
     */
    public function listName(string $listName)
    {
        $this->addVariables(['listName' => $listName]);

        return $this;
    }
}

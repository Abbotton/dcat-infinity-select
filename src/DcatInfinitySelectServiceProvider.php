<?php

namespace Dcat\Admin\Extension\DcatInfinitySelect;

use Dcat\Admin\Admin;
use Dcat\Admin\Extend\ServiceProvider;
use Dcat\Admin\Form;

class DcatInfinitySelectServiceProvider extends ServiceProvider
{
    public function init()
    {
        parent::init();

        if ($views = $this->getViewPath()) {
            $this->loadViewsFrom($views, 'dcat-infinity-select');
        }

        Admin::booting(function () {
            Form::extend('infinitySelect', InfinitySelect::class);
        });
    }
}

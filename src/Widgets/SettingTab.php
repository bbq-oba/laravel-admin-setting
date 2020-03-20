<?php

namespace Encore\Setting\Widgets;

use Encore\Admin\Widgets\Tab;

class SettingTab extends Tab
{
    protected $view = 'setting.tab';

    public static function initTab($tabs)
    {
        $tab = new static();
        return $tab->buildTabs($tabs);
    }

    protected function buildTabs($tabs, $active = null)
    {
        $active = $active ?: request($this->activeName);

        if (!isset($tabs[$active])) {
            $active = key($tabs);
        }

        foreach ($tabs as $tag => $name) {
            if ($tag == $active) {
                $this->add($name, SettingForm::init($tag)->unbox(), true);
            } else {
                $this->addLink($name, $this->getTabUrl($tag));
            }
        }
        return $this;
    }

    protected function getTabUrl($name)
    {
        $query = [$this->activeName => $name];

        return request()->fullUrlWithQuery($query);
    }

    public function button(array $links)
    {
        if (is_array($links[0])) {
            foreach ($links as $link) {
                call_user_func([$this, 'button'], $link);
            }

            return $this;
        }

        $this->data['button'][] = [
            'name' => $links[0],
            'href' => $links[1],
            'icon' => $links[2],
        ];

        return $this;
    }
}

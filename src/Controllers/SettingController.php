<?php

namespace Encore\Setting\Controllers;

use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Layout\Content;
use Encore\Setting\Models\SettingModel;
use Encore\Setting\Setting;
use Encore\Setting\Widgets\SettingTab;

class SettingController extends AdminController
{
    public $title = '配置中心';

    public function index(Content $content)
    {

        $tabs = config('setting.tag');
        return $content
            ->title($this->title)
            ->body(SettingTab::initTab($tabs)->button([
                '添加',
                route('setting.create'),
                'fa fa-plus'
            ]));
    }

    protected function form()
    {
        $form = new Form(new SettingModel());
        $form->text('label', __('字段名称'));
        $form->select('tag', __('所属分类'))->options(config('settings.tag'));
        $form->text('help', __('说明简介'));
        $form->keyValue('options', '可选值');
        if ($form->isEditing()) {
            $form->fieldset('详情', function (Form $form) {
                $form->text('key', __('配置键名'))->readonly();
                $form->text('type', __('字段类型'))->readonly();
            })->collapsed();
        } else {
            $form->text('key', __('配置键名'));
            $form->select('type', __('字段类型'))->options(Setting::$availableFields);
        }
        $form->disableViewCheck();
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        return $form;
    }

}

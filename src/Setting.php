<?php

namespace Encore\Setting;

use Encore\Admin\Extension;
use Encore\Admin\Form\Field;
use Encore\Setting\Models\SettingModel;

class Setting extends Extension
{
    /**
     * Available fields.
     *
     * @var array
     */
    public static $availableFields = [
        'checkbox' => Field\Checkbox::class,
        'color' => Field\Color::class,
        'currency' => Field\Currency::class,
        'date' => Field\Date::class,
        'datetime' => Field\Datetime::class,
        'decimal' => Field\Decimal::class,
        'email' => Field\Email::class,
        'file' => Field\File::class,
        'image' => Field\Image::class,
        'ip' => Field\Ip::class,
        'mobile' => Field\Mobile::class,
        'month' => Field\Month::class,
        'multipleSelect' => Field\MultipleSelect::class,
        'number' => Field\Number::class,
        'radio' => Field\Radio::class,
        'rate' => Field\Rate::class,
        'select' => Field\Select::class,
        'slider' => Field\Slider::class,
        'switch' => Field\SwitchField::class,
        'text' => Field\Text::class,
        'textarea' => Field\Textarea::class,
        'time' => Field\Time::class,
        'url' => Field\Url::class,
        'year' => Field\Year::class,
        'html' => Field\Html::class,
        'tags' => Field\Tags::class,
        'icon' => Field\Icon::class,
        'multipleFile' => Field\MultipleFile::class,
        'multipleImage' => Field\MultipleImage::class,
        'listbox' => Field\Listbox::class,
        'timezone' => Field\Timezone::class,
    ];
    public static $decodeType = [
        'checkbox',
        'multipleImage',
        'multipleSelect',
        'dateRange',
        'listbox',
    ];
    public static $uniqueNameType = [
        'image',
        'multipleImage',
        'file',
        'multipleFile'
    ];

    public $name = 'laravel-admin-setting';

    public $views = __DIR__ . '/../resources/views';

    public $assets = __DIR__ . '/../resources/assets';

    public $menu = [
        'title' => 'Setting',
        'path' => 'laravel-admin-setting',
        'icon' => 'fa-gears',
    ];

    public static function load()
    {
        foreach (SettingModel::all(['key', 'val', 'type']) as $config) {
            if (in_array($config['type'], self::$decodeType)) {
                $config['val'] = json_decode($config['val']);
            }
            config(['settings.' . $config['key'] => $config['val']]);
        }
    }
}

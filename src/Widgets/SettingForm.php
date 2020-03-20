<?php


namespace Encore\Setting\Widgets;


use Encore\Admin\Form\Field;
use Encore\Admin\Widgets\Form;
use Encore\Setting\Models\SettingModel;
use Encore\Setting\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SettingForm extends Form
{
    const TAG = '__TAG__';
    public $tag = '';
    public $activeName = 'active';
    /**
     * @var Field
     */
    protected $formFields;

    public static function init($tag = null)
    {

        $tab = new static();
        $tab->tag = $tag;
        return $tab;
    }

    public function handle(Request $request)
    {
        /**
         * 提交表单时候 需要先把表单塞到form里
         * 重建 表单 否则获取不到fields
         */
        $this->buildFields($request->input(self::TAG));
        $data = $this->prepareFields($request->all());
        $this->save($data);

        admin_success('ok');
        return back();
    }


    public function buildFields($tag)
    {
        $formFields = SettingModel::where('tag', '=', $tag)->get();
        foreach ($formFields as $field) {
            $this->formFields[$field->key] = $field;
            $edit = '';
            if ($field->id) {
                $edit = ' <a class="pull-right" href="' . route('setting.edit', ['setting' => $field->id]) . '"><i class="fa fa-edit"></i></a> ';
            }
            $row = $this->{$field->type}($field->key, $field->label);
            $tooltip = '<a tabindex="0" class="pull-right" data-placement="left" role="button" data-toggle="popover" data-html=true title="Usage" data-content="config(\'settings.' . $field->key . '\');"><code>' . $field->key . '</code></a>';
            $row->help($edit . $tooltip . $field->help);
            if (in_array($field->type, Setting::$uniqueNameType)) {
                $row->move('settings/')->uniqueName()->removable();
            }
            empty($field->options) ?: $row->options($field->options);
        }
    }

    /**
     * @param $inserts
     * @return array
     * 处理字段
     */
    public function prepareFields($inserts)
    {
        unset($inserts[self::TAG]);
        foreach ($inserts as $column => $value) {
            if (($field = $this->getFieldByColumn($column)) === null) {
                unset($inserts[$column]);
                continue;
            }
            $inserts[$column] = $field->prepare($value);
        }
        return $inserts;
    }

    /**
     * @param $column
     * @return Field
     */
    protected function getFieldByColumn($column)
    {
        return Arr::first($this->fields(), function (Field $field) use ($column) {
            if (is_array($field->column())) {
                return in_array($column, $field->column());
            }
            return $field->column() == $column;
        });
    }

    public function save($data)
    {
        foreach ($data as $k => $v) {
            if (isset($this->formFields[$k]) && $this->formFields[$k]->val != $v) {
                SettingModel::where('key', '=', $k)->update(
                    ['val' => $v]
                );
            }
        }
    }

    public function data()
    {
        $data = [];
        if ($this->formFields) {
            foreach ($this->formFields as $field) {
                if (in_array($field->type, Setting::$decodeType)) {
                    $data[$field->key] = json_decode($field->val, true);
                } else {
                    $data[$field->key] = $field->val;
                }
            }
        }
        return $data;
    }

    public function form()
    {
        $this->buildFields($this->tag);
        $this->hidden(self::TAG)->default($this->tag);
    }


}

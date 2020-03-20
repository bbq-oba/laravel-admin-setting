<?php


namespace Encore\Setting\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SettingModel extends Model
{
    public static $tableName = 'settings';
    public $timestamps = false;


    //同时更新多个记录，参数，表名，数组（别忘了在一开始use DB;）
    public static function updateBatch($multipleData = array())
    {
        $tableName = self::$tableName;
        if (!empty($multipleData)) {

            // column or fields to update
            $updateColumn = array_keys($multipleData[0]);
            $referenceColumn = $updateColumn[0]; //e.g id
            unset($updateColumn[0]);
            $whereIn = "";

            $q = "UPDATE " . $tableName . " SET ";
            foreach ($updateColumn as $uColumn) {
                $q .= $uColumn . " = CASE ";

                foreach ($multipleData as $data) {
                    $q .= "WHEN " . $referenceColumn . " = " . $data[$referenceColumn] . " THEN '" . $data[$uColumn] . "' ";
                }
                $q .= "ELSE " . $uColumn . " END, ";
            }
            foreach ($multipleData as $data) {
                $whereIn .= "'" . $data[$referenceColumn] . "', ";
            }
            $q = rtrim($q, ", ") . " WHERE " . $referenceColumn . " IN (" . rtrim($whereIn, ', ') . ")";

            // Update
            return DB::update(DB::raw($q));
        } else {
            return false;
        }
    }

    public function getTable()
    {
        return self::$tableName;
    }

    public function setOptionsAttribute($value)
    {
        $this->attributes['options'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    //test data
    /*
    $multipleData = array(
       array(
          'title' => 'My title' ,
          'name' => 'My Name 2' ,
          'date' => 'My date 2'
       ),
       array(
          'title' => 'Another title' ,
          'name' => 'Another Name 2' ,
          'date' => 'Another date 2'
       )
    )
    */

    /*
     * ----------------------------------
     * update batch
     * ----------------------------------
     *
     * multiple update in one query
     *
     * tablename( required | string )
     * multipleData ( required | array of array )
     */

    public function getOptionsAttribute()
    {
        $options = json_decode($this->attributes['options'], true);
        return $options ? $options : [];
    }
}

<?php

namespace Karacraft\Roleperm\Helpers;

use Illuminate\Support\Facades\DB;


class SeederHelper
{

    /** 
     * csvToArray()
     * Converts data from csv to Array for Table Push
     * Ensure to export delimited csv not Excel csv
     * @param filename
     * @param delimiter (Optional)
     * @return Array()
     */
    public static function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }

        return $data;
    }

    /**
     * pushData()
     * Send the data to table, but clears the table first
     *
     * @param [type] $tableData Received From CSV
     * @param [type] $modelName Eloquent Model Name
     * @return void
     */
    public static function pushData($tableData,$modelName)
    {
        //  Get Table Name
        $model = new $modelName;    //NOTE:Must be /App/Model
        // dd($model);
        $table = $model->getTable();    //Get It's Table Name
        // dd ($table);
        //  Clear Data
        DB::table($table)->delete();
        // DB::select('SET IDENTITY_INSERT ' .$table. ' ON;');
        //  Push New Data
        for ($i = 0; $i < count($tableData); $i++)
        {
            //NOTE:Ensure to use Model Name for seeding
            // $model = /App/Model
            // dd($tableData[1]);
            $model::Create($tableData[$i]);
        }
        // DB::select('SET IDENTITY_INSERT ' .$table. ' OFF;');
    }
}

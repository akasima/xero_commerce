<?php
/**
 * Created by PhpStorm.
 * User: yeong-ung-gim
 * Date: 2018. 9. 12.
 * Time: 오후 4:49
 */

namespace Xpressengine\Plugins\XeroStore;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Xpressengine\Plugins\XeroStore\Plugin\Database;

class Dev
{
    public function makeTable()
    {
        Database::create();
    }

    public function dropTable()
    {
        $tables = DB::select('SHOW TABLES LIKE "xe_xero_store_%"');
        foreach($tables as $table){
            $table_name = str_replace('xe_', '', head($table));
            Schema::dropIfExists($table_name);
            dump($table_name);
        }
    }

    public function resetTable()
    {
        $this->dropTable();
        $this->makeTable();
    }
}
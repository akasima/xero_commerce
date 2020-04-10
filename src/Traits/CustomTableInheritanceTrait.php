<?php

namespace Xpressengine\Plugins\XeroCommerce\Traits;

use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

trait CustomTableInheritanceTrait
{
    use SingleTableInheritanceTrait {
        SingleTableInheritanceTrait::bootSingleTableInheritanceTrait as bootCustomTableInheritanceTrait;
    }

    /**
     * 타입에 따른 이름맵을 가져오는 함수
     * @return array the type map
     */
    public static function getSingleTableNameMap() {
        $nameMap = [self::$singleTableType => self::$singleTableName];
        $subclasses = self::getSingleTableTypeMap();
        foreach ($subclasses as $type => $subclass) {
            $nameMap[$type] = $subclass::$singleTableName;
        }
        return $nameMap;
    }

    /**
     * 외부에서 상품타입을 등록하는 함수
     * @return void
     */
    public static function addSingleTableSubclass($subClass)
    {
        self::$singleTableSubclasses[] = $subClass;
    }
}

<?php

namespace Xpressengine\Plugins\XeroCommerce\Traits;

use Nanigans\SingleTableInheritance\Exceptions\SingleTableInheritanceException;
use Nanigans\SingleTableInheritance\SingleTableInheritanceTrait;

trait CustomTableInheritanceTrait
{
    use SingleTableInheritanceTrait {
        SingleTableInheritanceTrait::bootSingleTableInheritanceTrait as bootCustomTableInheritanceTrait;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if(!$this->type && property_exists($this, 'singleTableType')) {
            $this->type = $this::$singleTableType;
        }
    }

    /**
     * 타입에 따른 이름맵을 가져오는 함수
     * @return array the type map
     */
    public static function getSingleTableNameMap() {
        $nameMap = [];
        $subclasses = self::getSingleTableTypeMap();
        foreach ($subclasses as $type => $subclass) {
            $nameMap[$type] = $subclass::$singleTableName;
        }
        return $nameMap;
    }

    /**
     * 외부에서 타입을 등록하는 함수
     * @return void
     */
    public static function addSingleTableSubclass($subClass)
    {
        self::$singleTableSubclasses[] = $subClass;
    }

    /**
     * Root모델에서 일괄 저장이 가능하도록 수정
     * @inheritDoc
     */
    public function setSingleTableType() {
        $modelClass = get_class($this);
        $classType = property_exists($modelClass, 'singleTableType') ? $modelClass::$singleTableType : $this->getAttribute(static::$singleTableTypeField);
        if ($classType !== null) {
            if ($this->hasGetMutator(static::$singleTableTypeField)) {
                $this->{static::$singleTableTypeField} = $this->mutateAttribute(static::$singleTableTypeField, $classType);
            } else {
                $this->{static::$singleTableTypeField} = $classType;
            }
        } else {
            // We'd like to be able to declare non-leaf classes in the hierarchy as abstract so they can't be instantiated and saved.
            // However, Eloquent expects to instantiate classes at various points. Therefore throw an exception if we try to save
            // and instance that doesn't have a type.
            throw new SingleTableInheritanceException('Cannot save Single table inheritance model without declaring static property $singleTableType.');
        }
    }

}

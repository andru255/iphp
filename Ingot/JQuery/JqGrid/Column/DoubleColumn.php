<?php

class Ingot_JQuery_JqGrid_Column_DoubleColumn
{

    public static function createSelectColumn (Ingot_JQuery_JqGrid $objGrid, $mixValueData, $options = array())
    {
        
        $objAdapter = $objGrid->getAdapter();
        
        if ($objAdapter instanceof Ingot_JQuery_JqGrid_Adapter_DbTableSelect) {
            $objSelect = $objGrid->getAdapter()
                ->getSelect()
                ->getTable();
            $strTableClassName = get_class($objSelect);
            $arrReferenceMap = $objSelect->getReference($strTableClassName, $mixValueData);
            
            $strTableClass = $arrReferenceMap[$strTableClassName::REF_TABLE_CLASS];
            $arrColumns = array_merge($arrReferenceMap[$strTableClassName::REF_COLUMNS], array($arrReferenceMap['displayColumn']));
            $index = $arrReferenceMap[$strTableClassName::COLUMNS][0];
            
            $objReferenceTable = new $strTableClass();
            $objReferenceTableSelect = $objReferenceTable->select(TRUE);
            
            $objReferenceTableSelect->reset(Zend_Db_Select::COLUMNS);
            $objReferenceTableSelect->columns($arrColumns);
            
            $arrValues = $objReferenceTable->getAdapter()->fetchPairs($objReferenceTableSelect);
        
        } else {
            $arrValues = $mixValueData;
            
            if (! isset($options['index'])) {
                throw new Exception('When using Values an Index mast be set');
            }
            $index = $options['index'];
        
        }
        
        $name = $objGrid->getId() . '_' . $index;
        
        $objParentColumn = new Ingot_JQuery_JqGrid_Column($name, array_merge($options,array('index' => $index)));
        $objParentDecorator = new Ingot_JQuery_JqGrid_Column_Decorator_Search_DoubleSelect($objParentColumn, array('value' => $arrValues));
        $objGrid->addColumn($objParentDecorator);
        
        if (empty($options['label'])){
            $options['label'] = $objParentColumn->label;
        }
        
        $options['hidden'] = true;
        
        $objEditParentColumn = new Ingot_JQuery_JqGrid_Column($index, $options);
        $objParentEditDecorator = new Ingot_JQuery_JqGrid_Column_Decorator_Edit_Select($objEditParentColumn, array('value' => $arrValues), array('required' => true,'edithidden' => true));
        $objGrid->addColumn($objParentEditDecorator);
    
    }

}
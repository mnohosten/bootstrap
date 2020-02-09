<?php
declare(strict_types=1);

namespace App\Core\ServiceProvider\Persistence;

class NotORM_DatabaseStructure extends \NotORM_Structure_Convention
{
    public function getPrimary($table)
    {
        if($table == 'cuzk_address') {
            return 'adm_code';
        }
        return parent::getPrimary($table);
    }

}
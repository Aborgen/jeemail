<?php

namespace App\Hydrator;

use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Internal\Hydration\AbstractHydrator;

class ColumnHydrator extends AbstractHydrator
{
    protected function hydrateAllData(): ?array
    {
        return $this->_stmt->fetchAll(FetchMode::COLUMN);
    }
}
?>

<?php

namespace App\Hydrator;

use Doctrine\DBAL\FetchMode;
use Doctrine\ORM\Internal\Hydration\ArrayHydrator;

class EmailHydrator extends ArrayHydrator
{
    public const DEFAULT_LABEL = 0;
    public const LABELS        = 1;
    public const CATEGORY      = 2;

    private $emails = [];

    protected function hydrateAllData(): array
    {
        $result = &$this->emails;

        while ($data = $this->_stmt->fetch(FetchMode::ASSOCIATIVE)) {
            $this->hydrateRowData($data, $result);
        }

        if(count($result) > 0) {
            array_map('self::reorganize', $result);
            return $result;
        }

        return [];
    }

    private function reorganize(array &$arr): void
    {
        $arr[0]['content']['timestamp'] = $arr['timestamp'];
        $arr = $arr[0];
        $arr['organizers'] = $arr['labels'][0];
        $arr['organizers']['category'] = $arr['category'];
        unset($arr[0]);
        unset($arr['labels']);
        unset($arr['category']);
        unset($arr['timestamp']);

        $this->flatten_array(self::DEFAULT_LABEL, $arr);
        $this->flatten_array(self::LABELS, $arr);
        $this->flatten_array(self::CATEGORY, $arr);

        unset($arr['organizers']['labels']['label']);
        unset($arr['organizers']['defaultLabel']['label']);
        unset($arr['organizers']['category']['category']);
        return;
    }

    private function flatten_array(int $type, array &$arr): void
    {
        $parent = [];
        $child  = [];

        switch($type) {
            case self::LABELS:
                if(null === $arr['organizers']['labels']) {
                    $arr['organizers']['labels'] = [];
                    return;
                }
                else {
                    $parent = &$arr['organizers']['labels'];
                    $child  = $parent['label'];
                }
                break;
            case self::DEFAULT_LABEL:
                $parent = &$arr['organizers']['defaultLabel'];
                $child  = $parent['label'];
                break;
            case self::CATEGORY:
                $parent = &$arr['organizers']['category'];
                $child  = $parent['category'];
                break;
        }

        foreach($child as $key => $value) {
            if($key !== 'id') {
                $parent[$key] = $value;
            }
        }

        return;
    }
}
?>

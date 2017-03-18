<?php

namespace App;

class ScreenshotStack extends \SplStack
{
    /**
     * @param Screenshot[] $list
     */
    public function __construct(array $list)
    {
        usort($list, [$this, 'compare']);

        foreach ($list as $i => $screenshot) {
            if ($screenshot instanceof Screenshot) {
                $this->add($i, $screenshot);
            } else {
                throw new \RuntimeException('Unsupported object');
            }
        }
    }

    /**
     * @param Screenshot $value1
     * @param Screenshot $value2
     * @return int
     */
    public function compare($value1, $value2)
    {
        $firstDate = $value1->createdAt();
        $secondDate = $value2->createdAt();

        return $firstDate->greaterThan($secondDate) ? -1 : 1;
    }
}
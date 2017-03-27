<?php

namespace App\Service\ScreenshotStorage;

class StoragePriorityQueue extends \SplPriorityQueue
{
    private $priorityList = [];

    /**
     * Add a storage with tracking for a unique priority
     *
     * @param StorageInterface $storage
     * @param int|null $priority
     */
    public function add(StorageInterface $storage, int $priority = null)
    {
        $priority = $priority ?? $storage->getPriority();

        if (array_key_exists($priority, $this->priorityList)) {
            $message = "Priority {$priority} already exists";

            throw new \InvalidArgumentException($message);
        }

        $this->insert($storage, $priority);
        $this->priorityList[ $priority ] = get_class($storage);
    }
}
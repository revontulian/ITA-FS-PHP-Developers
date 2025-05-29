<?php

declare(strict_types=1);

class Tasklist extends Model
{
    private JsonCRUD $jsonCRUD;

    public function __construct()
    {
        $this->jsonCRUD = new JsonCRUD('tasklists.json');
    }

    /**
     * Get all tasklists from the JSON file.
     * @return array
     */

    protected function getAll(): array
    {
        return $this->jsonCRUD->read();
    }

    public function getTasklistsByUserId(string $userId): array
    {
        $tasklists = $this->getAll();
        $userTasklists = [];
        foreach ($tasklists as $tasklist) {
            if ($tasklist['user_id'] === $userId) {
                $userTasklists[] = $tasklist;
            }
        }
        return $userTasklists;
    }

    public function verifyTaskListDoesNotExist(string $name): bool
    {
        $tasklists = $this->getAll();
        foreach ($tasklists as $tasklist) {
            if ($tasklist['name'] === $name) {
                return false;
            }
        }
        return true;
    }

    public function getTasklistId()
    {
        
    }
}

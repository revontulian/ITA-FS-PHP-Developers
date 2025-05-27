<?php

declare(strict_types=1);

class Tasklist extends Model
{
    private string $file;
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

    public function getTasklistsById(string $userId): ?array
    {
        $tasklists = $this->getAll();
        foreach ($tasklists as $tasklist) {
            if ($tasklist['id'] === $userId) {
                return $tasklist;
            }
        }
        return null;
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
    
    public function createTasklist(string $name, string $userId): array
    {
        $tasklists = $this->getAll();
        $newTasklist = [
            'id' => uniqid(),
            'userId' => $userId, // Assuming you have a way to get the current user's ID
            'name' => $name,
            'tasks' => []
        ];
        $tasklists[] = $newTasklist;
        $this->jsonCRUD->update($userId, $tasklists);
        //file_put_contents($this->file, json_encode($tasklists, JSON_PRETTY_PRINT));
        return $newTasklist;
    }
}

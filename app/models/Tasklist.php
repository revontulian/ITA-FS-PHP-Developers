<?php

declare(strict_types=1);

class Tasklist extends Model
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../lib/data/tasklist.json';
    }

    /**
     * Get all tasklists from the JSON file.
     * @return array
     */

    protected function getAll(): array
    {
        $json = file_get_contents($this->file);
        $tasklists = json_decode($json, true);
        return is_array($tasklists) ? $tasklists : [];
    }

    public function getTasklistsById(string $id): ?array
    {
        $tasklists = $this->getAll();
        foreach ($tasklists as $tasklist) {
            if ($tasklist['id'] === $id) {
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
            'userId' => $userId,
            'name' => $name,
            'tasks' => []
        ];
        $tasklists[] = $newTasklist;
        file_put_contents($this->file, json_encode($tasklists, JSON_PRETTY_PRINT));
        return $newTasklist;
    }
}

<?php

declare(strict_types=1);

class Tasklist
{
    private string $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../data/tasklist.json';
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
}

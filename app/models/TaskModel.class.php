<?php

class Task extends Model
{
  protected string $taskFile = ROOT_PATH . '/app/db/tasks.json';
  protected array $tasks;

  public function __construct()
  {
    if (!is_dir(ROOT_PATH . '/app/db/')) {
      mkdir(ROOT_PATH . '/app/db/', 0755, true);
    }
    $this->loadTasks();
  }

  private function loadTasks(): void
  {
    if (!file_exists($this->taskFile)) {
      file_put_contents($this->taskFile, json_encode([]));
    }

    $jsonString = file_get_contents($this->taskFile);
    $this->tasks = json_decode($jsonString, false) ?? [];
  }

  public function fetchAll(): array
  {
    return $this->tasks;
  }

  public function fetchOne($id)
  {
    foreach ($this->tasks as $task) {
      if ($task->id == $id) {
        return $task;
      }
    }
  }

  public function save($data = array()): bool
  {
    $newId = count($this->tasks) + 1;

    $currentDateTime = date('Y-m-d H:i:s');

    $newTask = (object) [
      'id' => $newId,
      'name' => $data['name'],
      'username' => $data['username'],
      'create_time' => $currentDateTime,
      'completed_time' => null,
      'status' => 'pendent'
    ];

    $this->tasks[] = $newTask;

    $result = file_put_contents($this->taskFile, json_encode($this->tasks));

    return $result !== false;
  }

  public function update($data = array())
  {
    foreach ($this->tasks as $task) {
      if ($task->id == $data['id']) {
        $task->name = $data['name'];
        $task->username = $data['username'];
        $task->create_time = $data['create_time'];
        $task->status = $data['status'];

        $result = file_put_contents($this->taskFile, json_encode($this->tasks));

        return $result !== false;
      }
    }
  }

  public function delete($id): bool
  {
    foreach ($this->tasks as $key => $task) {
      if ($task->id == $id) {
        unset($this->tasks[$key]);
        break;
      }
    }

    $result = file_put_contents($this->taskFile, json_encode(array_values($this->tasks)));

    return $result !== false;
  }

  public function search(string $term): array
  {
    $results = [];
    foreach ($this->tasks as $task) {
      if (strcasecmp($term, $task->name) === 0) {
        $results[] = $task;
      }
    }
    return $results;
  }
}

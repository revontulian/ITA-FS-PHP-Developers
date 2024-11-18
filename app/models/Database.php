<?php

class Database extends Model {

    private array $tasks;
    private string $fullPath;

    public function __construct(){
        $dbFile = 'config/database.json';

        $this->fullPath = str_replace("app/models/Database.php", $dbFile, __FILE__);

        if(file_exists($this->fullPath)){
            $data = file_get_contents($this->fullPath);
            $this->tasks = json_decode($data, true)['tasks'];
        } else {
            throw new Exception("Error retrieving file.");
        }
    }

    public function getAll(): array{
        return $this->tasks;
    }

    public function getTask(string $id): array{
        $ids = [];
        foreach($this->tasks as $task){
            array_push($ids, $task["id"]);
        }
        if(in_array($id, $ids)){
            $idx = array_search($id, $ids);
            $task = $this->tasks[$idx];
        } else {
            $task = [];
        }
        return $task;
    }

    public function updateTask(string $id, array $changes){
        $countChanges = 0;
        $oldTask = $this->getTask($id);
        foreach($changes as $param=>$content){
            if($this->isAcceptedParameter($param) && $this->isValidContent($param, $content) && $this->isDifferent($oldTask[$param], $content)){
                
                $this->updateInnerTasks($id, $param, $content);
                $countChanges++;
            }
        }
        if($countChanges > 0){
            $this->updateDatabase();
        } else {
            echo "Nothing changed<br/>";
        }
    }

    private function isAcceptedParameter(string $param): bool{
        return in_array($param, ["title", "description", "start_time", "end_time", "created_by"]);
    }

    private function isValidContent(string $param, string $content): bool{
        return match($param){
            'title' => true,
            'description' => true,
            'start_time' => strtotime($content),
            'end_time' => strtotime($content),
            'created_by' => true,
            default => false
        };
    }

    private function isDifferent(string $oldContent, string $newContent): bool {
        return $oldContent != $newContent;
    }

    private function updateInnerTasks(string $id, string $parameter, string $input): void{
        $task = $this->getTask($id);
        $task[$parameter] = $input;
        
        $updated_tasks = [];
        foreach($this->tasks as $t){
            if ($t["id"] === $id){
                array_push($updated_tasks, $task);
            } else {
                array_push($updated_tasks, $t);
            }
        }
        $this->tasks = $updated_tasks;
    }

    private function updateDatabase(){
        $baseDB = [];
        $baseDB["tasks"] = $this->tasks;

        try {
            $json = json_encode($baseDB, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
            file_put_contents($this->fullPath, $json);
        } catch (Exception $e){
            echo "Error " . $e->getMessage();
        }
    }
}
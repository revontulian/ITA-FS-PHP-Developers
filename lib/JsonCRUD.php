<?php
class JsonCRUD
{
    private $file;

    public function __construct($filename)
    {
        $this->file = $filename;
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        }
    }

    private function readData()
    {
        $json = file_get_contents($this->file);
        return json_decode($json, true) ?: [];
    }

    private function writeData($data)
    {
        file_put_contents($this->file, json_encode($data, JSON_PRETTY_PRINT));
    }

    public function create($item)
    {
        $data = $this->readData();
        $item['id'] = uniqid();
        $data[] = $item;
        $this->writeData($data);
        return $item;
    }

    public function read($id = null)
    {
        $data = $this->readData();
        if ($id === null) return $data;
        foreach ($data as $item) {
            if ($item['id'] === $id) return $item;
        }
        return null;
    }

    public function update($id, $newData)
    {
        $data = $this->readData();
        foreach ($data as &$item) {
            if ($item['id'] === $id) {
                $item = array_merge($item, $newData);
                $this->writeData($data);
                return $item;
            }
        }
        return null;
    }

    public function delete($id)
    {
        $data = $this->readData();
        foreach ($data as $i => $item) {
            if ($item['id'] === $id) {
                array_splice($data, $i, 1);
                $this->writeData($data);
                return true;
            }
        }
        return false;
    }

    public function search($key, $value)
    {
        $data = $this->readData();
        $results = [];
        foreach ($data as $item) {
            if (isset($item[$key]) && stripos($item[$key], $value) !== false) {
                $results[] = $item;
            }
        }
        return $results;
    }
}

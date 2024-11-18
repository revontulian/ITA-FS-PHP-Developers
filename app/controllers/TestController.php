<?php

class TestController extends ApplicationController
{
	public function indexAction()
	{

		echo "<br/><br/>Obtener todas<br/><br/>";
		$tasks = $this->database->getAll();
		var_dump($tasks);

		echo "<br/><br/>Obtener tarea con id 'tasks-0'<br/><br/>";
		$task = $this->database->getTask("tasks-0");
		var_dump($task);

		echo "<br/><br/>Editar tarea con id 'tasks-0'<br/><br/>";
		$task = $this->database->updateTask("tasks-1", ["start_time" => "😨", "description" => "Texto nuevo"]);
		$task = $this->database->getTask("tasks-1");
		var_dump($task);
	}
	
	public function checkAction()
	{
		echo "hello from test::check";
	}
}

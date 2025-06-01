<?php
// filepath: app/views/scripts/test/test_task/TaskModelTest.php

echo "=== TaskModel Test Suite ===\n";

try {
    $taskModel = new TaskModel();
    
    // ✅ Test 1: Add Task
    echo "✅ Test 1: Add Task\n";
    $taskName = 'Test Task ' . uniqid();
    $result = $taskModel->addTask(
        $taskName,
        TaskStatus::PENDING,
        new DateTimeImmutable('2025-01-01'),
        'Test Description',
        new DateTimeImmutable('2025-01-02')
    );
    assert($result === true, 'Should create task successfully');
    echo "   ✓ Task creation test passed - Name: $taskName\n";
    
    
    echo "Test 2: Get All Tasks\n";
    $tasks = $taskModel->getAll();
    assert(is_array($tasks), 'Should return array');
    assert(count($tasks) > 0, 'Should have at least one task');
    echo "   ✓ Get all tasks test passed - Found " . count($tasks) . " tasks\n";
    
   
    echo "Test 3: Duplicate Task Name Prevention\n";
    $duplicateResult = $taskModel->addTask(
        $taskName, // Same name
        TaskStatus::IN_PROGRESS,
        new DateTimeImmutable('2025-01-03'),
        'Another Description'
    );
    assert($duplicateResult === false, 'Should not create task with duplicate name');
    echo "   ✓ Duplicate task name prevention test passed\n";
    
    
    echo "Test 4: Filter Tasks by Status\n";
    $pendingTasks = $taskModel->filterStatus(TaskStatus::PENDING);
    assert(is_array($pendingTasks), 'Should return array');
    
    foreach ($pendingTasks as $task) {
        assert($task['taskStatus'] === 'pending', 'All tasks should be pending');
    }
    echo "   ✓ Filter status test passed - Found " . count($pendingTasks) . " pending tasks\n";
    
    
    echo "Test 5: Get Task by ID\n";
    if (!empty($tasks)) {
        $firstTask = $tasks[0];
        $foundTask = $taskModel->getTaskbyId($firstTask['id']);
        assert($foundTask !== null, 'Should find task by ID');
        assert($foundTask['id'] === $firstTask['id'], 'IDs should match');
        echo "   ✓ Get task by ID test passed - Found task: " . $foundTask['nameTask'] . "\n";
        
        echo "Test 6: Update Task\n";
        $updateData = [
            'nameTask' => 'Updated Task Name',
            'taskStatus' => 'completed',
            'description' => 'Updated description'
        ];
        $updateResult = $taskModel->updateTaskid($firstTask['id'], $updateData);
        assert($updateResult === true, 'Should update task successfully');
        echo "   ✓ Update task test passed\n";
        
       
        echo "Test 7: Delete Task\n";
        $deleteResult = $taskModel->deleteTaskId($firstTask['id']);
        assert($deleteResult === true, 'Should delete task successfully');
        
    
        $deletedTask = $taskModel->getTaskbyId($firstTask['id']);
        assert($deletedTask === null, 'Task should not exist after deletion');
        echo "   ✓ Delete task test passed\n";
    }
    
    echo "TaskModel tests completed successfully!\n\n";
    
} catch (Exception $e) {
    echo "TaskModel Test Failed: " . $e->getMessage() . "\n";
} catch (AssertionError $e) {
    echo "Assertion Failed: " . $e->getMessage() . "\n";
}
?>
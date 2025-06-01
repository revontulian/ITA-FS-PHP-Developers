<?php
// filepath: app/views/scripts/test/test_tasklist/TasklistTest.php

echo "=== Tasklist Test Suite ===\n";

try {
    $tasklist = new Tasklist();
    
    echo "Test 1: Get Tasklists by User ID\n";
    
    
    $existingUserId = '6834ad9eb8f20'; 
    $userTasklists = $tasklist->getTasklistsByUserId($existingUserId);
    
    assert(is_array($userTasklists), 'Should return array');
    echo "   ✓ Found " . count($userTasklists) . " tasklists for user $existingUserId\n";
    
    foreach ($userTasklists as $list) {
        assert($list['user_id'] === $existingUserId, 'All tasklists should belong to the specified user');
        assert(isset($list['name']), 'Tasklist should have a name');
        assert(isset($list['id']), 'Tasklist should have an ID');
    }
    echo "   ✓ All tasklists belong to correct user\n";
    
   
    echo " Test 2: Verify Tasklist Name Uniqueness\n";
    
    $uniqueName = 'Test Unique Tasklist ' . uniqid();
    $doesNotExist = $tasklist->verifyTaskListDoesNotExist($uniqueName);
    assert($doesNotExist === true, 'Unique name should not exist');
    echo "   ✓ Unique name verification test passed\n";
  
    if (!empty($userTasklists)) {
        $existingName = $userTasklists[0]['name'];
        $exists = $tasklist->verifyTaskListDoesNotExist($existingName);
        assert($exists === false, 'Existing name should return false');
        echo "   ✓ Existing name verification test passed\n";
    }
    
   
    echo "Test 3: Get All Tasklists Structure\n";
    
    
    if (!empty($userTasklists)) {
        $firstTasklist = $userTasklists[0];
        $requiredFields = ['name', 'user_id', 'id'];
        
        foreach ($requiredFields as $field) {
            assert(array_key_exists($field, $firstTasklist), "Tasklist should have field: $field");
        }
        echo "   ✓ Tasklist structure test passed\n";
    }
    
    
    echo "Test 4: Empty User ID Handling\n";
    $emptyUserTasklists = $tasklist->getTasklistsByUserId('nonexistent_user_id');
    assert(is_array($emptyUserTasklists), 'Should return array for non-existent user');
    assert(count($emptyUserTasklists) === 0, 'Should return empty array for non-existent user');
    echo "   ✓ Empty user ID handling test passed\n";
    
    echo " Tasklist tests completed successfully!\n\n";
    
} catch (Exception $e) {
    echo " Tasklist Test Failed: " . $e->getMessage() . "\n";
} catch (AssertionError $e) {
    echo " Assertion Failed: " . $e->getMessage() . "\n";
}
?>
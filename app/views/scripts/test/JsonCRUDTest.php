<?php
// filepath: app/views/scripts/test/test_lib/JsonCRUDTest.php

echo "=== JsonCRUD Test Suite ===\n";


$testFile = 'test_crud_' . uniqid() . '.json';
$jsonCRUD = new JsonCRUD($testFile);

echo "Test 1: Create Item\n";
$item = ['name' => 'Test Task', 'status' => 'pending'];
$created = $jsonCRUD->create($item);
assert(!empty($created['id']), 'ID should be generated');
assert($created['name'] === 'Test Task', 'Name should match');
echo "   ✓ Create test passed\n";

echo " Test 2: Read Items\n";
$items = $jsonCRUD->read();
assert(count($items) === 1, 'Should have 1 item');
assert($items[0]['name'] === 'Test Task', 'Item name should match');
echo "   ✓ Read test passed\n";

echo "Test 3: Update Item\n";
$itemId = $items[0]['id'];
$updated = $jsonCRUD->update($itemId, ['name' => 'Updated Task']);
assert($updated['name'] === 'Updated Task', 'Name should be updated');
echo "   ✓ Update test passed\n";

echo " Test 4: Delete Item\n";
$deleted = $jsonCRUD->delete($itemId);
assert($deleted === true, 'Delete should return true');
$items = $jsonCRUD->read();
assert(count($items) === 0, 'Should have 0 items after delete');
echo "   ✓ Delete test passed\n";

// ✅ Cleanup
$filePath = ROOT_PATH . '/lib/data/' . $testFile;
if (file_exists($filePath)) {
    unlink($filePath);
    echo "   ✓ Test file cleaned up\n";
}

echo "🎉 JsonCRUD tests completed successfully!\n\n";
?>
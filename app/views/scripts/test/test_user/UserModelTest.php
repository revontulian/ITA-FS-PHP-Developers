<?php
// filepath: app/views/scripts/test/test_user/UserModelTest.php

echo "=== UserModel Test Suite ===\n";

try {
    $userModel = new ModelUser();
    
    // ✅ Test 1: Add User
    echo "✅ Test 1: Add User\n";
    $testEmail = 'test_' . uniqid() . '@example.com';
    $result = $userModel->addUser(
        $testEmail,
        'password123',
        'Test',
        'User',
        '1990-01-01'
    );
    assert($result === true, 'Should create user successfully');
    echo "   ✓ User creation test passed - Email: $testEmail\n";
    
    // ✅ Test 2: Check if user exists
    echo "✅ Test 2: User Exists Check\n";
    $exists = $userModel->userExists($testEmail);
    assert($exists === true, 'User should exist after creation');
    echo "   ✓ User exists test passed\n";
    
    // ✅ Test 3: Prevent duplicate email
    echo "✅ Test 3: Duplicate Email Prevention\n";
    $duplicateResult = $userModel->addUser(
        $testEmail, // Same email
        'differentpassword',
        'Another',
        'User',
        '1995-01-01'
    );
    assert($duplicateResult === false, 'Should not create user with duplicate email');
    echo "   ✓ Duplicate email prevention test passed\n";
    
    // ✅ Test 4: Validate Login
    echo "✅ Test 4: Login Validation\n";
    $user = $userModel->validateLogin($testEmail, 'password123');
    assert($user !== null, 'Should validate login with correct credentials');
    assert($user['email'] === $testEmail, 'Email should match');
    assert($user['name'] === 'Test', 'Name should match');
    echo "   ✓ Login validation test passed - User ID: " . $user['id'] . "\n";
    
    // ✅ Test 5: Invalid Login
    echo "✅ Test 5: Invalid Login\n";
    $invalidUser = $userModel->validateLogin($testEmail, 'wrongpassword');
    assert($invalidUser === null, 'Should not validate with wrong password');
    echo "   ✓ Invalid login test passed\n";
    
    // ✅ Test 6: Email Exists Check
    echo "✅ Test 6: Email Exists Method\n";
    $emailExists = $userModel->emailExists($testEmail);
    assert($emailExists === true, 'Email should exist');
    $emailNotExists = $userModel->emailExists('nonexistent@example.com');
    assert($emailNotExists === false, 'Non-existent email should return false');
    echo "   ✓ Email exists check test passed\n";
    
    echo "🎉 UserModel tests completed successfully!\n\n";
    
} catch (Exception $e) {
    echo "❌ UserModel Test Failed: " . $e->getMessage() . "\n";
} catch (AssertionError $e) {
    echo "❌ Assertion Failed: " . $e->getMessage() . "\n";
}
?>
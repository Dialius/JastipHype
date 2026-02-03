<?php

/**
 * Test admin route structure and middleware configuration
 * 
 * Validates: Requirements 10.1, 10.2
 */
describe('Admin Route Structure', function () {
    
    test('admin routes are registered with correct prefix', function () {
        // Check that admin.dashboard route exists
        expect(route('admin.dashboard'))->toContain('/admin/dashboard');
    });
    
    test('admin dashboard route requires authentication', function () {
        // Unauthenticated user should be redirected to login
        $response = $this->get(route('admin.dashboard'));
        
        $response->assertRedirect(route('login'));
    });
    
    test('admin root path is accessible', function () {
        // Test that /admin path exists (will redirect to dashboard)
        $response = $this->get('/admin');
        
        // Should redirect (either to login or to dashboard)
        $response->assertStatus(302);
    });
});

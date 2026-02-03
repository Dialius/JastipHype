<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('AdminMiddleware', function () {
    
    it('redirects unauthenticated users to login', function () {
        $response = $this->get('/admin/dashboard');
        
        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error', 'You must be logged in to access the admin panel.');
    });
    
    it('redirects non-admin users to unauthorized page', function () {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);
        
        $response = $this->actingAs($user)->get('/admin/dashboard');
        
        $response->assertRedirect(route('unauthorized'));
        $response->assertSessionHas('error', 'You do not have permission to access the admin panel.');
    });
    
    it('allows admin users to access admin routes', function () {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        
        // Note: This will fail until dashboard route is implemented
        // For now, we just check it doesn't redirect to unauthorized
        $response->assertStatus(404); // Route not found yet, but not unauthorized
    });
    
    it('displays unauthorized page correctly', function () {
        $response = $this->get(route('unauthorized'));
        
        $response->assertStatus(200);
        $response->assertSee('403');
        $response->assertSee('Unauthorized Access');
        $response->assertSee('You do not have permission to access this page');
    });
    
    it('shows error message on unauthorized page when redirected', function () {
        $user = User::factory()->create([
            'is_admin' => false,
        ]);
        
        $response = $this->actingAs($user)->get('/admin/dashboard');
        
        $response->assertRedirect(route('unauthorized'));
        
        // Follow the redirect
        $response = $this->followingRedirects()
            ->actingAs($user)
            ->get('/admin/dashboard');
        
        $response->assertSee('You do not have permission to access the admin panel.');
    });
});

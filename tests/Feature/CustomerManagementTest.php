<?php

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('administrator can create a customer', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $response = $this->actingAs($admin)
        ->post(route('customers.store'), [
            'name' => 'Acme Services',
            'company' => 'Acme Ltd.',
            'email' => 'contact@acme.test',
            'phone' => '780-555-0100',
            'city' => 'Edmonton',
            'province' => 'Alberta',
            'postal_code' => 'T5J 0N3',
        ]);

    $response->assertRedirect(route('customers.index'));

    $this->assertDatabaseHas('customers', [
        'name' => 'Acme Services',
        'company' => 'Acme Ltd.',
        'email' => 'contact@acme.test',
        'created_by' => $admin->id,
    ]);
});

test('customer name is required', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $this->actingAs($admin)
        ->post(route('customers.store'), [
            'email' => 'contact@example.test',
        ])
        ->assertSessionHasErrors('name');
});

test('administrator can update a customer', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $customer = Customer::factory()->create([
        'name' => 'Old Name',
    ]);

    $response = $this->actingAs($admin)
        ->patch(route('customers.update', $customer), [
            'name' => 'Updated Name',
            'company' => 'Updated Company',
        ]);

    $response->assertRedirect(route('customers.show', $customer));

    $this->assertDatabaseHas('customers', [
        'id' => $customer->id,
        'name' => 'Updated Name',
        'company' => 'Updated Company',
    ]);
});

test('customer index can search by name', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    Customer::factory()->create([
        'name' => 'North Star Plumbing',
    ]);

    Customer::factory()->create([
        'name' => 'Prairie Electric',
    ]);

    $this->actingAs($admin)
        ->get(route('customers.index', [
            'search' => 'North Star',
        ]))
        ->assertInertia(fn (Assert $page) => $page
            ->component('customers/Index')
            ->where('filters.search', 'North Star')
            ->has('customers.data', 1)
            ->where('customers.data.0.name', 'North Star Plumbing')
        );
});

test('customer index is paginated', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    Customer::factory()
        ->count(15)
        ->create();

    $this->actingAs($admin)
        ->get(route('customers.index'))
        ->assertInertia(fn (Assert $page) => $page
            ->component('customers/Index')
            ->has('customers.data', 10)
        );
});

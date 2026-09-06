<?php

use App\Enums\UserRole;
use App\Models\Customer;
use App\Models\User;

test('guests cannot access customer routes', function () {
    $customer = Customer::factory()->create();

    $this->get(route('customers.index'))
        ->assertRedirect(route('login'));

    $this->get(route('customers.create'))
        ->assertRedirect(route('login'));

    $this->get(route('customers.show', $customer))
        ->assertRedirect(route('login'));

    $this->get(route('customers.edit', $customer))
        ->assertRedirect(route('login'));
});

test('technicians can view customers', function () {
    $technician = User::factory()->create([
        'role' => UserRole::Technician,
    ]);

    $customer = Customer::factory()->create();

    $this->actingAs($technician)
        ->get(route('customers.index'))
        ->assertOk();

    $this->actingAs($technician)
        ->get(route('customers.show', $customer))
        ->assertOk();
});

test('technicians cannot create customers', function () {
    $technician = User::factory()->create([
        'role' => UserRole::Technician,
    ]);

    $this->actingAs($technician)
        ->get(route('customers.create'))
        ->assertForbidden();

    $this->actingAs($technician)
        ->post(route('customers.store'), [
            'name' => 'Unauthorized Customer',
        ])
        ->assertForbidden();
});

test('technicians cannot update customers', function () {
    $technician = User::factory()->create([
        'role' => UserRole::Technician,
    ]);

    $customer = Customer::factory()->create();

    $this->actingAs($technician)
        ->get(route('customers.edit', $customer))
        ->assertForbidden();

    $this->actingAs($technician)
        ->patch(route('customers.update', $customer), [
            'name' => 'Unauthorized Update',
        ])
        ->assertForbidden();
});

test('administrators can create and update customers', function () {
    $admin = User::factory()->create([
        'role' => UserRole::Admin,
    ]);

    $customer = Customer::factory()->create();

    $this->actingAs($admin)
        ->get(route('customers.create'))
        ->assertOk();

    $this->actingAs($admin)
        ->get(route('customers.edit', $customer))
        ->assertOk();
});

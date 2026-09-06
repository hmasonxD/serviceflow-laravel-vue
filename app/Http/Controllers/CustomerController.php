<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class CustomerController extends Controller
{
    public function index(Request $request): Response
    {
        Gate::authorize('viewAny', Customer::class);

        $search = $request->string('search')->trim()->toString();

        $customers = Customer::query()
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('company', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                }),
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('customers/Index', [
            'customers' => $customers,
            'filters' => [
                'search' => $search,
            ],
            'can' => [
                'create' => Gate::allows('create', Customer::class),
            ],
        ]);
    }

    public function create(): Response
    {
        Gate::authorize('create', Customer::class);

        return Inertia::render('customers/Create');
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        Gate::authorize('create', Customer::class);

        Customer::create([
            ...$request->validated(),
            'created_by' => $request->user()->id,
        ]);

        return to_route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): Response
    {
        Gate::authorize('view', $customer);

        return Inertia::render('customers/Show', [
            'customer' => $customer,
            'can' => [
                'update' => Gate::allows('update', $customer),
            ],
        ]);
    }

    public function edit(Customer $customer): Response
    {
        Gate::authorize('update', $customer);

        return Inertia::render('customers/Edit', [
            'customer' => $customer,
        ]);
    }

    public function update(
        UpdateCustomerRequest $request,
        Customer $customer,
    ): RedirectResponse {
        Gate::authorize('update', $customer);

        $customer->update($request->validated());

        return to_route('customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }
}

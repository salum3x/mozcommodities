<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the customer registration view.
     */
    public function createCustomer(): View
    {
        return view('auth.register-customer');
    }

    /**
     * Display the supplier registration view.
     */
    public function createSupplier(): View
    {
        return view('auth.register-supplier');
    }

    /**
     * Display the old registration view (redirects to customer).
     */
    public function create(): RedirectResponse
    {
        return redirect()->route('register.customer');
    }

    /**
     * Handle customer registration.
     */
    public function storeCustomer(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }

    /**
     * Handle supplier registration.
     */
    public function storeSupplier(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'document_number' => ['required', 'string', 'max:50'],
            'address' => ['required', 'string'],
            'latitude' => ['required', 'string'],
            'longitude' => ['required', 'string'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'supplier',
        ]);

        // Create supplier record
        $user->supplier()->create([
            'company_name' => $request->company_name,
            'whatsapp' => $request->whatsapp,
            'document_number' => $request->document_number,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('supplier.dashboard', absolute: false));
    }

    /**
     * Handle an incoming registration request (legacy - redirects to customer).
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->storeCustomer($request);
    }
}

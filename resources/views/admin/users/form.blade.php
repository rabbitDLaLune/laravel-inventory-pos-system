<div>
    <label class="block text-sm font-medium mb-1">Full Name</label>
    <input
        type="text"
        name="name"
        value="{{ old('name', $user->name ?? '') }}"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div>
    <label class="block text-sm font-medium mb-1">Email</label>
    <input
        type="email"
        name="email"
        value="{{ old('email', $user->email ?? '') }}"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div>
    <label class="block text-sm font-medium mb-1">
        Password
        @isset($user)
            <span class="text-xs text-gray-500">(leave blank to keep current password)</span>
        @endisset
    </label>

    <input
        type="password"
        name="password"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div>
    <label class="block text-sm font-medium mb-1">Confirm Password</label>
    <input
        type="password"
        name="password_confirmation"
        class="w-full border rounded-lg px-3 py-2"
    >
</div>

<div>
    <label class="block text-sm font-medium mb-1">Role</label>
    <select name="role" class="w-full border rounded-lg px-3 py-2">
        <option value="admin" @selected(old('role', $user->role ?? '') === 'admin')>
            Admin
        </option>

        <option value="manager" @selected(old('role', $user->role ?? '') === 'manager')>
            Manager
        </option>

        <option value="cashier" @selected(old('role', $user->role ?? '') === 'cashier')>
            Cashier
        </option>
    </select>
</div>

<div>
    <label class="block text-sm font-medium mb-1">Status</label>

    <label class="flex items-center gap-2">
        <input
            type="checkbox"
            name="is_active"
            value="1"
            @checked(old('is_active', $user->is_active ?? true))
        >
        <span>Active account</span>
    </label>
</div>

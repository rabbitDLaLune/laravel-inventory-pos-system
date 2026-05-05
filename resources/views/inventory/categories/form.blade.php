<div>
    <label class="block text-sm font-medium mb-1">Category Name</label>
    <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" class="w-full border rounded-lg px-3 py-2">
</div>

<div>
    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $category->is_active ?? true))>
        <span>Active category</span>
    </label>
</div>

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Delete Account
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </header>

    <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
        @csrf
        @method('delete')

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input id="password" name="password" type="password" 
                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2"
                   placeholder="Enter your password to confirm account deletion">
            @error('password')
                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-6">
            <button type="submit" 
                    class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700"
                    onclick="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
                Delete Account
            </button>
        </div>
    </form>
</section>
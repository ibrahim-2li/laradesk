<div>
    <div class="mb-6 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-800">{{ __('Departments') }}</h1>
        <button wire:click="create" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
            {{ __('New Department') }}
        </button>
    </div>

    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="p-4 border-b border-gray-200">
            <div class="w-full md:w-1/3">
                <input wire:model.live.debounce.300ms="search" type="text"
                    placeholder="{{ __('Search departments...') }}"
                    class="form-input rounded-md shadow-sm block w-full" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Public') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('All Agents') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Assigned Agents') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($departments as $department)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $department->name }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($department->public)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">{{ __('Yes') }}</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ __('No') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($department->all_agents)
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">{{ __('Yes') }}</span>
                                @else
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">{{ __('No') }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $department->all_agents ? __('All') : $department->agent_count }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button wire:click="edit({{ $department->id }})"
                                    class="text-blue-600 hover:text-blue-900 mr-3">{{ __('Edit') }}</button>
                                <button wire:click="delete({{ $department->id }})"
                                    wire:confirm="{{ __('Are you sure you want to delete this department?') }}"
                                    class="text-red-600 hover:text-red-900">{{ __('Delete') }}</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                {{ __('No departments found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4">
            {{ $departments->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if ($isModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $editingId ? __('Edit Department') : __('Create New Department') }}
                    </h3>
                </div>
                <form wire:submit.prevent="store">
                    <div class="px-6 py-4 space-y-4">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">{{ __('Name') }}</label>
                            <input wire:model="name" type="text"
                                class="form-input mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                            @error('name')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Public -->
                        <div class="flex items-center">
                            <input wire:model="public" type="checkbox"
                                class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                            <label class="ml-2 block text-sm text-gray-900">{{ __('Public') }} <span
                                    class="text-xs text-gray-500">({{ __('Available for ticket submission by customers') }})</span></label>
                        </div>

                        <!-- All Agents -->
                        <div class="flex items-center">
                            <input wire:model="all_agents" type="checkbox"
                                class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out">
                            <label class="ml-2 block text-sm text-gray-900">{{ __('All Agents Access') }} <span
                                    class="text-xs text-gray-500">({{ __('If unchecked, you can assign specific agents later') }})</span></label>
                        </div>

                    </div>
                    <div class="px-6 py-4 bg-gray-50 flex justify-end rounded-b-lg">
                        <button type="button" wire:click="closeModal"
                            class="mr-3 px-4 py-2 text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            {{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

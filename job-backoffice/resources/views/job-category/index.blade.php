<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Job Categories') }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <x-toast-notification type="success" :message="session('success')" />
            @endif

            <div class="bg-white shadow-sm rounded-lg border border-gray-200">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-800">{{ __('Job Categories') }}</h3>
                    <a href="{{ route('job-categories.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-500 active:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <span class="text-lg leading-none mr-2">+</span>
                        <span>{{ __('Add Category') }}</span>
                    </a>
                </div> 

                <div class="overflow-hidden rounded-b-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                    {{ __('Category Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wide">
                                    {{ __('Actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse ($jobCategories as $jobCategory)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-800 font-medium">
                                        {{ $jobCategory->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex justify-end items-center space-x-4">
                                            <a href="{{ route('job-categories.edit', $jobCategory) }}"
                                               class="text-amber-600 hover:text-amber-700 font-semibold flex items-center space-x-1">
                                                <span>✍️</span><span>{{ __('Edit') }}</span>
                                            </a>
                                            <form action="{{ route('job-categories.destroy', $jobCategory) }}" method="POST"
                                                  onsubmit="return confirm('{{ __('Are you sure you want to archive this category?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="text-rose-600 hover:text-rose-700 font-semibold flex items-center space-x-1">
                                                    <span>🗂️</span><span>{{ __('Archive') }}</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-4 text-sm text-gray-500">
                                        {{ __('No job categories found.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="flex items-center justify-between mt-4 px-1">
                <div class="text-sm text-gray-600">
                    @if ($jobCategories->total())
                        {{ __('Showing :from to :to of :total results', [
                            'from' => $jobCategories->firstItem(),
                            'to' => $jobCategories->lastItem(),
                            'total' => $jobCategories->total(),
                        ]) }}
                    @else
                        {{ __('No results') }}
                    @endif
                </div>
                <div>
                    {{ $jobCategories->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

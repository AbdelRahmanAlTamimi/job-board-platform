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
                    <div class="flex items-center space-x-4">
                        <h3 class="text-lg font-semibold text-gray-800">{{ __('Job Categories') }}</h3>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('job-categories.index') }}"
                               class="px-3 py-1.5 rounded-md text-xs font-semibold {{ ($status ?? null) === 'archived' ? 'text-gray-500 hover:text-gray-700' : 'bg-gray-800 text-white' }}">
                                {{ __('Active') }}
                            </a>
                            <a href="{{ route('job-categories.index', ['status' => 'archived']) }}"
                               class="px-3 py-1.5 rounded-md text-xs font-semibold {{ ($status ?? null) === 'archived' ? 'bg-gray-800 text-white' : 'text-gray-500 hover:text-gray-700' }}">
                                {{ __('Archived') }}
                            </a>
                        </div>
                    </div>
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
                                        @if (($status ?? null) === 'archived')
                                            <div class="flex justify-end items-center space-x-4">
                                                <form action="{{ route('job-categories.restore', $jobCategory->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit"
                                                            class="text-emerald-600 hover:text-emerald-700 font-semibold flex items-center space-x-1">
                                                        <span>↩</span><span>{{ __('Restore') }}</span>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <div class="flex justify-end items-center space-x-4">
                                                <a href="{{ route('job-categories.edit', $jobCategory->id) }}"
                                                   class="text-amber-600 hover:text-amber-700 font-semibold flex items-center space-x-1">
                                                    <span>✍️</span><span>{{ __('Edit') }}</span>
                                                </a>
                                                <form id="archive-form-{{ $jobCategory->id }}" action="{{ route('job-categories.destroy', $jobCategory) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                                <button type="button"
                                                        data-archive-form="archive-form-{{ $jobCategory->id }}"
                                                        data-archive-name="{{ $jobCategory->name }}"
                                                        class="text-rose-600 hover:text-rose-700 font-semibold flex items-center space-x-1 archive-trigger">
                                                    <span>🗂️</span><span>{{ __('Archive') }}</span>
                                                </button>
                                            </div>
                                        @endif
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

    {{-- Archive confirmation modal --}}
    <div id="archive-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 space-y-4">
            <div class="flex items-start space-x-3">
                <div class="text-rose-600 text-2xl leading-none">🗂️</div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ __('Archive category?') }}</h3>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ __('This will move') }} <span class="font-semibold" id="archive-modal-name"></span> {{ __('to archived categories. You can view it later from the Archived tab.') }}
                    </p>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button"
                        class="px-4 py-2 rounded-md text-gray-600 hover:text-gray-800"
                        id="archive-cancel">
                    {{ __('Cancel') }}
                </button>
                <button type="button"
                        class="inline-flex items-center px-4 py-2 bg-rose-600 text-white rounded-md hover:bg-rose-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500"
                        id="archive-confirm">
                    {{ __('Yes, Archive') }}
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('archive-modal');
            const modalName = document.getElementById('archive-modal-name');
            const cancelBtn = document.getElementById('archive-cancel');
            const confirmBtn = document.getElementById('archive-confirm');
            let pendingFormId = null;

            const openModal = (formId, name) => {
                pendingFormId = formId;
                modalName.textContent = name;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const closeModal = () => {
                pendingFormId = null;
                modalName.textContent = '';
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            };

            document.querySelectorAll('.archive-trigger').forEach(btn => {
                btn.addEventListener('click', () => {
                    openModal(btn.dataset.archiveForm, btn.dataset.archiveName);
                });
            });

            cancelBtn.addEventListener('click', closeModal);
            confirmBtn.addEventListener('click', () => {
                if (pendingFormId) {
                    document.getElementById(pendingFormId).submit();
                }
            });

            // Close when clicking backdrop
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeModal();
            });
        });
    </script>
</x-app-layout>

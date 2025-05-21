<div class="max-w-6xl mx-auto px-4 py-6">
    <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-4">Mis dietas del blog</h2>

    {{-- Controles --}}
    <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-2">
        <div class="w-full sm:w-auto">
            <x-input type="text" wire:model.debounce.500ms="search" placeholder="Buscar por título..." />
        </div>

        <div class="flex items-center gap-2">
            <label class="flex items-center gap-1 text-sm">
                <input type="checkbox" wire:model="showTrashed" class="rounded border-gray-300" />
                <span>Ver eliminadas</span>
            </label>

            <a href="{{ route('posts.create') }}">
                <x-button class="bg-green-600 hover:bg-green-700">
                    + Nueva dieta
                </x-button>
            </a>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="overflow-x-auto bg-white dark:bg-gray-900 shadow rounded-xl">
        <table class="w-full table-auto">
            <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-200 text-sm uppercase">
            <tr>
                <th class="px-4 py-3 text-left">Título</th>
                <th class="px-4 py-3 text-left">Calorías</th>
                <th class="px-4 py-3 text-left">Proteínas</th>
                <th class="px-4 py-3">Acciones</th>
            </tr>
            </thead>
            <tbody class="text-gray-800 dark:text-gray-100">
            @forelse ($posts as $post)
                <tr class="border-b border-gray-200 dark:border-gray-700">
                    <td class="px-4 py-3 font-medium">{{ $post->title }}</td>
                    <td class="px-4 py-3">{{ $post->macros['calories'] ?? '—' }}</td>
                    <td class="px-4 py-3">{{ $post->macros['protein'] ?? '—' }} g</td>
                    <td class="px-4 py-3 space-x-2 text-center">
                        @can('update', $post)
                            <a href="{{ route('posts.edit', $post) }}">
                                <x-button size="sm">Editar</x-button>
                            </a>
                        @endcan

                        @can('delete', $post)
                            @if($post->trashed())
                                <x-button size="sm" wire:click="restore({{ $post->id }})" class="bg-yellow-600 hover:bg-yellow-700">
                                    Restaurar
                                </x-button>
                            @else
                                <x-button size="sm" wire:click="delete({{ $post->id }})" class="bg-red-600 hover:bg-red-700">
                                    Eliminar
                                </x-button>
                            @endif
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-6 text-center text-gray-500">No hay dietas encontradas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>

        {{-- Paginación --}}
        <div class="px-4 py-4">
            {{ $posts->links() }}
        </div>
    </div>
</div>


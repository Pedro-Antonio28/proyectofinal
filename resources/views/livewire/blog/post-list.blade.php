<div class="max-w-6xl mx-auto px-4 py-12">
    <h2 class="text-4xl font-extrabold text-center text-gray-900 mb-12" data-aos="fade-down">
        🥗 Dietas del Blog
    </h2>

    {{-- ⚡ AOS solo aquí: en envoltorio que no se actualiza --}}
    <div data-aos="zoom-in">
        {{-- Contenedor Livewire reactivo sin AOS --}}
        <div class="grid gap-10 sm:grid-cols-1 md:grid-cols-2 xl:grid-cols-2">
            @foreach ($posts as $post)
                <livewire:blog.post-card :post="$post" :wire:key="'post-'.$post->id" />
            @endforeach



        @if ($posts->isEmpty())
                <div class="text-center text-gray-500 col-span-full" data-aos="fade-up">
                    No hay dietas disponibles 🥲
                </div>
            @endif
        </div>
    </div>

    <div class="mt-12">
        {{ $posts->links() }}
    </div>
</div>

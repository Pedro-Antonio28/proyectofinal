@if ($errors->any())
    <div class="text-red-600 bg-red-100 border border-red-500 rounded-lg p-4 text-center mb-6">
        @foreach ($errors->all() as $error)
            <p>❌ {{ $error }}</p>
        @endforeach
    </div>
@endif

@extends('layouts.CalorixLayout')

@section('content')
    <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-2xl p-8 border border-gray-300 mt-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-6 text-center">👤 {{ __('messages.edit_profile') }}</h2>

        <div class="flex flex-col md:flex-row items-center gap-6">
            <div class="flex-shrink-0">
                <img src="{{ asset('images/user-placeholder.png') }}" alt="{{ __('messages.profile_photo') }}"
                     class="w-28 h-28 rounded-full shadow-md border border-gray-300">
            </div>

            <div class="flex-grow text-gray-700 space-y-2 w-full">
                <div class="bg-gray-100 p-4 rounded-lg">
                    <p><strong>📧 {{ __('messages.email') }}:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>📅 {{ __('messages.member_since') }}:</strong> {{ Auth::user()->created_at->format('d M Y') }}</p>
                    <p><strong>🎯 {{ __('messages.role') }}:</strong>
                        <span class="bg-green-500 text-white px-3 py-1 rounded-md">
                            {{ ucfirst(Auth::user()->roles->first()->name ?? __('messages.default_role')) }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="mt-6">
            @csrf
            @method('PATCH')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">{{ __('messages.name') }}</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">{{ __('messages.email') }}</label>
                    <input type="email" value="{{ Auth::user()->email }}" disabled
                           class="w-full px-4 py-2 border border-gray-300 bg-gray-200 rounded-lg cursor-not-allowed">
                </div>

                <div class="mt-10 bg-green-50 p-6 rounded-xl border border-green-200 text-gray-800 shadow-sm">
                    <h3 class="text-xl font-bold mb-2">💼 {{ __('messages.current_plan') }}</h3>

                    @if(Auth::user()->is_premium)
                        <p class="text-green-700 font-semibold">✅ {{ __('messages.premium_user') }}</p>
                    @else
                        <p class="text-red-700 font-semibold">🔒 {{ __('messages.free_plan') }}</p>
                        <a href="{{ route('paypal.create') }}"
                           class="mt-4 inline-block bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-md shadow-md transition-all duration-300">
                            💳 {{ __('messages.upgrade_to_premium') }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('dashboard') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                    ⬅ {{ __('messages.back') }}
                </a>

                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                    💾 {{ __('messages.save_changes') }}
                </button>
            </div>
        </form>

        <hr class="my-10 border-gray-300">

        <h2 class="text-xl font-bold text-gray-800 mb-4">🔐 {{ __('messages.change_password') }}</h2>

        @if (session('status') === 'password-updated')
            <div class="mb-4 text-green-600 font-semibold">
                ✅ {{ __('messages.password_updated') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update-password') }}">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">{{ __('messages.new_password') }}</label>
                <input type="password" name="password"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">{{ __('messages.confirm_new_password') }}</label>
                <input type="password" name="password_confirmation"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-6 rounded-lg transition-all duration-300">
                🔒 {{ __('messages.update_password') }}
            </button>
        </form>
    </div>
@endsection

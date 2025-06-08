
<a href="{{ route('change.language', ['locale' => App::getLocale() === 'es' ? 'en' : 'es']) }}"
   title="{{ __('messages.change_language_tooltip') }}"
   class="ml-4 bg-white border border-gray-400 hover:bg-gray-100 rounded-full p-2 transition duration-300 shadow-sm hover:shadow-md flex items-center justify-center"
   style="width: 40px; height: 40px;">
    🌐
</a>

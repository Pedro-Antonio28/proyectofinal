@if (!auth()->user()?->is_premium)
    <div id="modalOverlayPremium"
         class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 transition-opacity duration-300">
        <div class="bg-white w-full max-w-md mx-auto rounded-2xl shadow-2xl p-6 animate-fade-in scale-95">
            <h2 class="text-3xl font-bold text-gray-900 mb-4 text-center">🚀 Función Premium</h2>
            <p class="text-gray-600 text-center mb-6 leading-relaxed">
                Esta funcionalidad está disponible solo para usuarios premium.<br>
                Desbloquéala por solo <strong>4.99€</strong> y accede a todos los beneficios.
            </p>

            <div class="w-20 h-20 mx-auto mb-6 animate-bounce text-yellow-400">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-full h-full" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l2.9 6.6L22 9.3l-5.5 5.1L17.8 22 12 18.4 6.2 22l1.3-7.6L2 9.3l7.1-0.7z"/>
                </svg>
            </div>

            <div class="flex justify-center gap-4">
                <a href="{{ route('paypal.create') }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded-lg font-semibold shadow transition">
                    💳 Comprar Premium
                </a>
                <button onclick="cerrarModalPremium()"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-5 py-2 rounded-lg font-semibold transition">
                    Cancelar
                </button>
            </div>
        </div>
    </div>

    <script>
        function abrirModalPremium() {
            document.getElementById('modalOverlayPremium')?.classList.remove('hidden');
        }

        function cerrarModalPremium() {
            document.getElementById('modalOverlayPremium')?.classList.add('hidden');
        }
    </script>

    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .animate-fade-in {
            animation: fade-in 0.25s ease-out forwards;
        }
    </style>
@endif

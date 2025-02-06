import React from "react";
import { ArrowRight } from "lucide-react";

export default function Page() {
    return (
        <div className="min-h-screen bg-[#f8fff4]">
            {/* Navigation */}
            <nav className="flex items-center justify-between px-6 py-4 max-w-7xl mx-auto">
                <div className="flex items-center gap-2">
                    <img src="/placeholder.svg" alt="Calorix Logo" width={40} height={40} className="w-10 h-10" />
                    <span className="text-xl font-semibold">Calorix</span>
                </div>

                <div className="hidden md:flex items-center gap-8">
                    <a href="#" className="text-gray-700 hover:text-gray-900">Inicio</a>
                    <a href="#" className="text-gray-700 hover:text-gray-900">Sobre Nosotros</a>
                    <a href="#" className="text-gray-700 hover:text-gray-900">Blog de Nutrición</a>
                </div>

                <button className="bg-[#a7d675] hover:bg-[#96c464] text-gray-800 px-6 py-2 rounded-full flex items-center gap-2">
                    Iniciar Sesión
                    <ArrowRight className="w-4 h-4" />
                </button>
            </nav>
        </div>
    );
}

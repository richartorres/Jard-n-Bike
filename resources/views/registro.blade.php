<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA] flex justify-center items-center min-h-screen p-4">

    <div class="max-w-md w-full">
        <div class="flex items-center justify-between mb-6">
            <button class="p-2 rounded-full border border-gray-200 hover:bg-gray-50 transition">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.833 10H4.166" stroke="#101828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 15.833L4.167 10L10 4.167" stroke="#101828" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
            <span class="text-sm text-gray-500 font-medium">Paso 1 de 2</span>
        </div>

        <h1 class="text-2xl font-bold text-[#101828] mb-1">Crea tu cuenta</h1>
        <p class="text-gray-500 mb-8">Tomará menos de un minuto.</p>

        <div class="space-y-5">
            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Nombre completo</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    </span>
                    <input type="text" placeholder="Ej. Sofía Ramírez" class="w-full pl-12 pr-4 py-3 bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] transition placeholder:text-gray-400">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Cédula o pasaporte</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
                    </span>
                    <input type="text" placeholder="1023456789" class="w-full pl-12 pr-4 py-3 bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] transition placeholder:text-gray-400">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-[#344054] mb-1.5 uppercase tracking-wide">Teléfono</label>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-[#98A2B3]">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                    </span>
                    <input type="tel" placeholder="+57 300 000 0000" class="w-full pl-12 pr-4 py-3 bg-white rounded-2xl border border-gray-200 outline-none focus:border-[#105B3A] transition placeholder:text-gray-400">
                </div>
            </div>

            <div class="bg-white rounded-3xl border border-gray-200 p-5 shadow-sm">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex gap-3">
                        <div class="bg-[#F2F4F2] p-2 rounded-xl text-[#105B3A]">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg>
                        </div>
                        <div>
                            <p class="font-semibold text-[#101828]">Tarjeta de garantía</p>
                            <p class="text-xs text-gray-500">No hacemos cobros sin tu autorización.</p>
                        </div>
                    </div>
                    <span class="text-[#98A2B3]">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    </span>
                </div>
                
                <div class="bg-gradient-to-br from-[#115C39] to-[#257A3C] rounded-2xl p-5 text-white shadow-lg">
                    <div class="flex justify-between mb-6">
                        <div class="w-10 h-7 bg-[#FFBC00] rounded-md"></div>
                        <span class="font-bold tracking-widest text-sm italic">VISA</span>
                    </div>
                    <p class="text-lg tracking-[0.2em] mb-4">•••• •••• •••• 4242</p>
                    <div class="flex justify-between items-end">
                        <p class="font-medium text-sm tracking-wide">SOFÍA RAMÍREZ</p>
                        <p class="text-xs">09/28</p>
                    </div>
                </div>

                <button class="w-full mt-4 py-3 text-sm font-semibold text-[#105B3A] border border-dashed border-[#105B3A] rounded-xl hover:bg-emerald-50 transition">
                    Cambiar / actualizar tarjeta
                </button>
            </div>

            <div class="flex gap-3 bg-[#F9FAFB] p-4 rounded-2xl border border-gray-100 items-start">
                <span class="text-[#105B3A] mt-0.5">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path><path d="M9 12l2 2 4-4"></path></svg>
                </span>
                <p class="text-xs text-gray-600 leading-relaxed">Tus datos viajan cifrados. Solo cobramos en caso de daño o pérdida según los términos.</p>
            </div>

           <a href="/login" class="block w-full text-center py-4 rounded-2xl font-bold text-[#101828] bg-[#FFBC00] hover:bg-[#F0B000] transition shadow-[0_4px_12px_rgba(255,188,0,0.25)]">
    Comfirmar y Continuar
</a>

        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="es">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#F8F9FA] flex justify-center items-center min-h-screen">

    <div class="max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center gap-3">
                <div class="bg-[#105B3A] p-3 rounded-full">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.5 19C6.88071 19 8 17.8807 8 16.5C8 15.1193 6.88071 14 5.5 14C4.11929 14 3 15.1193 3 16.5C3 17.8807 4.11929 19 5.5 19Z" stroke="white" stroke-width="2"/>
                        <path d="M18.5 19C19.8807 19 21 17.8807 21 16.5C21 15.1193 19.8807 14 18.5 14C17.1193 14 16 15.1193 16 16.5C16 17.8807 17.1193 19 18.5 19Z" stroke="white" stroke-width="2"/>
                        <path d="M12 16.5V11.5L8 8" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 11.5H16.5L18.5 14" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 8.5C12.8284 8.5 13.5 7.82843 13.5 7C13.5 6.17157 12.8284 5.5 12 5.5C11.1716 5.5 10.5 6.17157 10.5 7C10.5 7.82843 11.1716 8.5 12 8.5Z" fill="white"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-base text-[#101828]">Jardín Bike</h1>
                    <p class="text-xs font-semibold tracking-wider text-[#938F99]">ANTIOQUIA · CO</p>
                </div>
            </div>
            <button class="border border-gray-200 px-4 py-1.5 rounded-full text-xs font-medium text-gray-700 bg-white shadow-sm">Admin</button>
        </div>

        <div class="bg-gradient-to-r from-[#115C39] to-[#257A3C] text-white rounded-[2rem] p-8 mb-6 shadow-lg">
            <div class="inline-flex items-center gap-1.5 bg-white/20 text-xs px-3 py-1 rounded-full mb-4 font-medium">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 0L8.89 5.11L14 7L8.89 8.89L7 14L5.11 8.89L0 7L5.11 5.11L7 0Z" fill="white"/>
                </svg>
                Movilidad limpia
            </div>
            <h2 class="text-3xl font-bold mb-3 leading-tight">Pedalea Jardín,<br>sin esfuerzo.</h2>
            <p class="text-emerald-100 text-sm font-normal">Descubre cascadas, cafetales y balcones coloridos en una e-bike. Desbloquea con un QR.</p>
        </div>

        <div class="space-y-3 mb-8">
            <div class="bg-white p-4 rounded-2xl border border-[#EBEBEB] shadow-sm flex items-center gap-4">
                <div class="bg-[#F2F4F2] p-3 rounded-full">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M20.9 2.5C18.6 1.3 14.9 1 12 4C9.1 1 5.4 1.3 3.1 2.5C1.6 3.3 1 4.9 1 6.6C1 12.2 4.6 17 12 21C19.4 17 23 12.2 23 6.6C23 4.9 22.4 3.3 20.9 2.5ZM12 18.8C6 15.6 3 11.7 3 6.6C3 5.9 3.2 5.3 3.8 5C5.4 4.1 8.6 3.8 11 6.1C11.4 6.5 12 6.5 12.4 6.1C14.8 3.8 18 4.1 19.6 5C20.2 5.3 20.4 5.9 20.4 6.6C20.4 11.7 17.4 15.6 11.4 18.8L12 18.8Z" fill="#105B3A"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-sm text-[#101828]">100% eléctrica</p>
                    <p class="text-xs text-gray-500">Cero emisiones</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-[#EBEBEB] shadow-sm flex items-center gap-4">
                <div class="bg-[#F2F4F2] p-3 rounded-full">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2C8.13 2 5 5.13 5 9C5 14.25 12 22 12 22C12 22 19 14.25 19 9C19 5.13 15.87 2 12 2ZM7 9C7 6.24 9.24 4 12 4C14.76 4 17 6.24 17 9C17 11.88 14.12 16.19 12 18.88C9.91 16.2 7 11.85 7 9Z" fill="#105B3A"/>
                        <path d="M12 11.5C13.3807 11.5 14.5 10.3807 14.5 9C14.5 7.61929 13.3807 6.5 12 6.5C10.6193 6.5 9.5 7.61929 9.5 9C9.5 10.3807 10.6193 11.5 12 11.5Z" fill="#105B3A"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-sm text-[#101828]">Estaciones en el pueblo</p>
                    <p class="text-xs text-gray-500">6 puntos de retiro</p>
                </div>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-[#EBEBEB] shadow-sm flex items-center gap-4">
                <div class="bg-[#F2F4F2] p-3 rounded-full">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L4 5V11C4 16.1 7.4 20.8 12 22C16.6 20.8 20 16.1 20 11V5L12 2ZM18 11C18 15 15.4 18.6 12 19.6C8.6 18.6 6 15 6 11V6.5L12 4.2L18 6.5V11Z" fill="#105B3A"/>
                        <path d="M15 9.5L11 13.5L9 11.5L8.3 12.2L11 14.9L15.7 10.2L15 9.5Z" fill="#105B3A"/>
                    </svg>
                </div>
                <div>
                    <p class="font-semibold text-sm text-[#101828]">Garantía segura</p>
                    <p class="text-xs text-gray-500">Tarjeta como respaldo</p>
                </div>
            </div>
        </div>

        <a href="/registro" class="w-full bg-[#FFBC00] hover:bg-[#F0B000] transition py-4 rounded-2xl font-bold shadow-[0_4px_12px_rgba(255,188,0,0.25)] text-[#101828] mb-3 flex items-center justify-center gap-2">
        Crear cuenta
         <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
         <path d="M10.78 7.22L7 3.44L8.06 2.38L13.68 8L8.06 13.62L7 12.56L10.78 8.78H2.32V7.22H10.78Z" fill="#101828"/>
            </svg>
         </a>
       
         <a href="/login" class="block w-full text-center py-4 rounded-2xl font-bold text-[#101828] bg-white border border-[#D0D5DD] hover:bg-gray-50 transition shadow-sm">
             Ya tengo cuenta, iniciar sesión
        </a>

        <p class="text-center text-xs text-[#667085] mt-6">Tarifa $4.000 COP · USD $1 cada 15 minutos</p>
    </div>
</body>
</html>
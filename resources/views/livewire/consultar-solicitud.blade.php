<div class="px-4 md:px-8">
<div class="max-w-2xl mx-auto bg-[#C0C0C0] rounded-xl px-12 py-8">

        {{-- CONTENEDOR SUPERIOR --}}
        <div class="bg-white border border-b-0 p-8 rounded-t-xl">

            {{-- TÍTULO --}}
            <h2
                class="text-center text-2xl font-bold mt-6 mb-2"
                style="color: #03192B"
            >
                Ver estado de mi constancia
            </h2>

             <p class="mb-5 text-red-600 text-center text-sm mt-1 bg-yellow-100 p-2 rounded">
                    Debe ingresar los datos que colocó en su solicitud
            </p>

            {{-- ICONO --}}
            <img
                src="{{ asset('imagenes/icono_muni.png') }}"
                alt="Icono"
                class="w-20 md:w-32 mx-auto block"
            >

            {{-- INPUT DPI / CUI --}}
            <div class="max-w-xl mx-auto mt-10">


                <label class="block font-bold text-center text-green-600">
                    Número de DPI/CUI
                </label>
                <input
                    type="text"
                    wire:model.defer="cui"
                    placeholder="Ingrese su número de DPI/CUI"
                    class="w-full bg-transparent
                        border-0 border-b-2 border-[#757575]
                        hover:border-[#030EA7]
                        focus:border-[#030EA7]
                        text-center text-[#757575]
                        px-1 py-2
                        focus:outline-none focus:ring-0
                        transition-colors duration-300"
                >
                
            </div>

            {{-- INPUT NÚMERO DE SOLICITUD --}}
            <div class="max-w-xl mx-auto mt-10">

                <label class="block text-center font-bold text-green-600">
                    Número de solicitud
                </label>

                <input
                    type="text"
                    wire:model.defer="no_solicitud"
                    placeholder="Ingrese su número de solicitud"
                    class="w-full bg-transparent
                        border-0 border-b-2 border-[#757575]
                        hover:border-[#030EA7]
                        focus:border-[#030EA7]
                        text-center text-[#757575]
                        px-1 py-2
                        focus:outline-none focus:ring-0
                        transition-colors duration-300"
                >
            </div>

            {{-- ERROR --}}
            @if ($error)
                <p class="mt-6 text-center text-red-600 font-semibold">
                    {{ $error }}
                </p>
            @endif

            {{-- BOTONES --}}
            <div class="max-w-xl mx-auto mt-10">
                <div class="flex flex-col md:flex-row gap-4">

                    <button
                        wire:click="consultar"
                        class="w-full md:w-1/2
                            bg-[#03192B]
                            hover:bg-[#03192B]/90
                            active:bg-[#03192B]/80
                            transition
                            rounded-lg
                            py-3
                            text-white text-xl font-bold
                            focus:outline-none focus:ring-0"
                    >
                        Consultar
                    </button>

                    <button
                        wire:click="limpiar"
                        type="button"
                        class="w-full md:w-1/2
                            bg-[#757575]
                            hover:bg-[#757575]/90
                            active:bg-[#757575]/80
                            transition
                            rounded-lg
                            py-3
                            text-white text-xl font-bold
                            focus:outline-none focus:ring-0"
                    >
                        Limpiar
                    </button>

                </div>
            </div>

        </div>

        {{-- RESULTADO --}}
        @if ($solicitud)
            @php
                $tramite = $solicitud->requisitosTramites->first()?->tramite;
            @endphp

            <div class="bg-white p-8 rounded-b-xl">

                <h3
                    class="text-center text-xl md:text-2xl font-bold
                    bg-[#83BD3F] text-white
                    py-3 rounded-t-lg"
                >
                    Estado actual de su constancia
                </h3>

                <div class="border border-t-0 rounded-b-lg p-6">

                    @if ($tramite)
                        <div class="flex flex-col sm:flex-row justify-between text-sm md:text-base">
                            <span class="text-gray-700 font-semibold">Trámite:</span>
                            <span class="text-[#03192B] uppercase">
                                {{ strtoupper($tramite->nombre) }}
                            </span>
                        </div>
                        <hr class="my-3 border-gray-300">
                    @endif

                    <div class="flex flex-col sm:flex-row justify-between text-sm md:text-base">
                        <span class="text-gray-700 font-semibold">Fecha de creación:</span>
                        <span class="text-[#03192B] uppercase">
                            {{ $solicitud->created_at->translatedFormat('d \\d\\e F \\d\\e Y \\a \\l\\a\\s H:i') }}
                        </span>
                    </div>

                    <hr class="my-3 border-gray-300">

                    <div class="flex flex-col sm:flex-row justify-between text-sm md:text-base">
                        <span class="text-gray-700 font-semibold">Nombres completos:</span>
                        <span class="text-[#03192B] uppercase">
                            {{ $solicitud->nombres }} {{ $solicitud->apellidos }}
                        </span>
                    </div>

                    <hr class="my-3 border-gray-300">

                    <div class="flex flex-col sm:flex-row justify-between text-sm md:text-base">
                        <span class="text-gray-700 font-semibold">Documento personal de identificación:</span>
                        <span class="text-[#03192B] uppercase">
                            {{ $solicitud->cui }}
                        </span>
                    </div>

                    <hr class="my-3 border-gray-300">

                    <div class="flex flex-col sm:flex-row justify-between text-sm md:text-base">
                        <span class="text-gray-700 font-semibold">Estado actual:</span>
                        <span class="text-[#03192B] uppercase">
                            {{ $solicitud->estado->nombre }}
                        </span>
                    </div>

                </div>


                <div class="border border-gray-300 mt-3">
                     <h3
                    class="text-center text-xl md:text-2xl font-bold
                    bg-[#070F9E] text-white
                    py-3 rounded-t-lg"
                >
                    Estado de su proceso
                </h3>

                <hr class="border-white/40 mb-6">

                <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
                    @foreach($estados as $estado)

                    @php
                        $completado = $estado->id<=$solicitud->estado_id;
                    @endphp

                    <div class="flex flex-col items-center gap-2">
                        <div class="text-3xl">
                            @if($completado)
                            <i class="fas fa-check-circle text-green-400"></i>
                            @else
                            <i class="fas fa-times-circle text-red-400"></i>
                            @endif
                        </div>

                    <span class="text-sm font-semibold uppercase">
                        {{ $estado->nombre }}
                    </span>

                    </div>

                    @endforeach
                </div>

                </div>

               



            </div>


            
        @endif

    </div>
</div>

<div class="container w-1/2 my-12 bg-gray-100">
    <div class="rounded-lg bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
        <div>
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-10 w-10 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-2xl">¡Gracias {{ $supplier_business_name }}!</p>
                <p class="font-boold text-xl">Su oferta fue enviada correctamente.</p>
                </div>
            </div>
            <div>
                <br>
                <p class="text-sm font-bold">Fecha última oferta: {{ Date::parse($answered_at)->format('d-m-Y H:i') }}</p>
                <br>
                <p class="text-sm text-justify">
                    Revisa tu bandeja de entrada. Un mensaje de confirmación de tu oferta fue enviado a tu dirección de correo electrónico.
                    Si no recibiste el correo, por favor, ponte en contacto con nosotros.
                </p>
                <br>
                <p class="text-sm text-justify">Recuerda que puedes <span class="font-bold">modificar o cancelar tu oferta  (antes del {{ Date::parse($tendering_end_date)->format('d-m-Y H:i') }})</span> a través del link adjunto en el correo electrónico.</p>
                <br>
                <p class="text-sm text-right font-bold italic">¡Gracias por tu interés! Atte: CHP e hijos</p>
            </div>
        </div>
    </div>
</div>

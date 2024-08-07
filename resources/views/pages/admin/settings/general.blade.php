<x-admin.app-default app_title="" page_title="{{ $title }}">
    @section('content')
        <div class="mx-2 mt-4 md:mx-8">
            <div class="mb-4 grid w-full grid-cols-2 gap-4">

                <div class="col-span-2 md:col-span-1">
                    <div
                        class="rounded-md bg-apae-white p-6 text-apae-gray-dark shadow-md dark:bg-apae-gray-dark dark:text-apae-white">
                        <h1 class="mb-2 text-[1.4rem] font-bold">Extensões</h1>
                        <form action="{{ route('admin.settings.general.update-extensions') }}" method="post"
                            class="flex flex-wrap gap-4">
                            @csrf
                            @method('PUT')
                            @foreach ($settings as $stg)
                                @if ($stg['setting_type'] === 'extensions')
                                    <div class="flex w-full flex-wrap p-1">
                                        <label for="{{ $stg['setting_name'] }}"
                                            class="w-full text-[1rem]">{{ ucwords(str_replace('_', ' ', $stg['setting_name'])) }}</label>
                                        <input type="text" id="{{ $stg['setting_name'] }}"
                                            name="{{ $stg['setting_name'] }}" value="{{ $stg['setting_value'] }}"
                                            class="w-full rounded-[4px] !border-none bg-apae-gray/10 px-2 py-1 !outline-none">
                                    </div>
                                @endif
                            @endforeach

                            <div class="flex w-full flex-wrap justify-center gap-4 py-3">
                                <button type="submit"
                                    class="w-full rounded-sm bg-apae-green px-6 py-1 text-apae-white shadow-md dark:bg-apae-gray">
                                    Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <div
                        class="rounded-md bg-apae-white p-6 text-apae-gray-dark shadow-md dark:bg-apae-gray-dark dark:text-apae-white">
                        <h1 class="mb-2 text-[1.4rem] font-bold">Diretórios (Path)</h1>
                        <form action="{{ route('admin.settings.general.update-paths') }}" method="post"
                            class="flex flex-wrap gap-4">
                            @csrf
                            @method('PUT')
                            @foreach ($settings as $stg)
                                @if ($stg['setting_type'] === 'path')
                                    <div class="flex w-full flex-wrap p-1">
                                        <label for="{{ $stg['setting_name'] }}"
                                            class="w-full text-[1rem]">{{ ucwords(str_replace('_', ' ', $stg['setting_name'])) }}</label>
                                        <input type="text" id="{{ $stg['setting_name'] }}"
                                            name="{{ $stg['setting_name'] }}" value="{{ $stg['setting_value'] }}"
                                            class="w-full rounded-[4px] !border-none bg-apae-gray/10 px-2 py-1 !outline-none">
                                    </div>
                                @endif
                            @endforeach

                            <div class="flex w-full flex-wrap justify-center gap-4 py-3">
                                <button type="submit"
                                    class="w-full rounded-sm bg-apae-green px-6 py-1 text-apae-white shadow-md dark:bg-apae-gray">
                                    Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-span-2 md:col-span-1">
                    <div
                        class="w-full rounded-md bg-apae-white p-6 text-apae-gray-dark shadow-md dark:bg-apae-gray-dark dark:text-apae-white">
                        <h1 class="mb-2 text-[1.4rem] font-bold">Servidor de E-mail</h1>
                        <form action="{{ route('admin.settings.general.update-mail-server') }}" method="post"
                            class="flex flex-wrap gap-4">
                            @csrf
                            @method('PUT')
                            @foreach ($settings as $keyS => $stg)
                                @if ($stg['setting_type'] === 'mail')
                                    <div class="flex w-full flex-wrap p-1">
                                        <label for="email" class="w-full text-[1rem]">Remetente</label>
                                        <input type="text"
                                            class="w-full rounded-[4px] !border-none bg-apae-gray/10 px-2 py-1 !outline-none"
                                            value="{{ $stg['setting_value']['email'] }}" name="email" id="email">
                                    </div>

                                    <div class="flex w-full flex-wrap p-1">
                                        <label for="subject" class="w-full text-[1rem]">Assunto</label>
                                        <input type="text"
                                            class="w-full rounded-[4px] !border-none bg-apae-gray/10 px-2 py-1 !outline-none"
                                            value="{{ $stg['setting_value']['subject'] }}" name="subject" id="subject">
                                    </div>

                                    @foreach ($stg['setting_value']['mail_settings'] as $key => $mailSettings)
                                        <div class="flex w-full flex-wrap p-1">
                                            <label for="{{ $key }}"
                                                class="w-full text-[1rem]">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                            <input type="text"
                                                class="w-full rounded-[4px] !border-none bg-apae-gray/10 px-2 py-1 !outline-none"
                                                value="{{ $mailSettings }}" id="{{ $key }}"
                                                name="{{ $key }}">
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach

                            <div class="flex w-full flex-wrap justify-center gap-4 py-3">
                                <button type="submit"
                                    class="w-full rounded-sm bg-apae-green px-6 py-1 text-apae-white shadow-md dark:bg-apae-gray">
                                    Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-span-2 w-full md:col-span-1">
                    <div
                        class="rounded-md bg-apae-white p-6 text-apae-gray-dark shadow-md dark:bg-apae-gray-dark dark:text-apae-white">
                        <h1 class="mb-2 text-[1.4rem] font-bold">Pix (QRCode)</h1>
                        <form action="{{ route('admin.settings.general.update-qrcode') }}" method="post"
                            enctype="multipart/form-data" class="flex flex-wrap gap-4">
                            @csrf
                            @method('PUT')
                            <div class="flex w-full flex-wrap p-1">
                                <label for="description" class="w-full text-[1rem]">Descrição</label>
                                <input type="text" name="description" value="" id="image_name"
                                    class="w-full rounded-[4px] !border-none bg-apae-gray/10 px-2 py-1 !outline-none">
                            </div>
                            <div class="flex w-full flex-wrap p-1">
                                <label for="image_name" class="w-full text-[1rem]">QRcode (imagem)</label>
                                <input type="file" name="image_name" value="" id="image_name"
                                    class="w-full rounded-[4px] !border-none bg-apae-gray/10 px-2 py-1 !outline-none">
                            </div>

                            <div class="justify-left flex w-full flex-wrap items-center p-1">
                                <img src="{{ env('APP_URL') }}/images/qrcode/QR CODE PIX DA APAE.jpeg.jpg"
                                    class="h-52 w-52">
                            </div>

                            <div class="flex w-full flex-wrap justify-center gap-4 py-3">
                                <button type="submit"
                                    class="w-full rounded-sm bg-apae-green px-6 py-1 text-apae-white shadow-md dark:bg-apae-gray">
                                    Salvar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @endsection
</x-admin.app-default>

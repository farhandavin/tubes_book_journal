<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Sesi Aktif') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Ini adalah daftar perangkat yang sedang login ke akun Anda.') }}
        </p>
    </header>

    <div class="mt-6 space-y-6">
        @if($sessions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perangkat</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Aktif</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($sessions as $session)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        {{ $session->device_name }}
                                    </div>
                                    <div class="text-xs text-gray-500 ml-7 truncate w-40" title="{{ $session->user_agent }}">
                                        {{ Str::limit($session->user_agent, 30) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $session->ip_address }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                    @if($session->is_current_device)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Sedang Digunakan
                                        </span>
                                    @else
                                        <span class="text-gray-400 text-xs">Login lain</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-sm text-gray-500">Tidak ada data sesi ditemukan.</p>
        @endif
    </div>
</section>
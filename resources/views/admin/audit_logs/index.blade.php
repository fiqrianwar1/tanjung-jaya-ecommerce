<x-app-layout>
    @section('title', 'Audit Logs')

    <x-slot name="header">Audit Logs</x-slot>

    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Sistem Audit Trail</h2>
            <p class="text-sm text-slate-500">Log rekaman aktivitas sistem untuk keamanan dan pemantauan.</p>
        </div>
    </div>

    <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 mb-6">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-1">Cari Aktivitas</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama tindakan atau target ID..." class="w-full rounded-lg border-slate-300 focus:border-emerald-500 focus:ring-emerald-500 shadow-sm text-sm">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-slate-800 hover:bg-slate-900 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">Cari</button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.audit-logs.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-5 py-2 rounded-lg text-sm font-medium transition border border-slate-200">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <div x-data="{ logModalOpen: false, selectedLog: null }" class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-semibold w-48">Waktu (Timestamp)</th>
                        <th class="px-6 py-4 font-semibold">Pengguna</th>
                        <th class="px-6 py-4 font-semibold">Tindakan (Action)</th>
                        <th class="px-6 py-4 font-semibold">Target ID</th>
                        <th class="px-6 py-4 font-semibold text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50/70 transition-all duration-200 hover:-translate-y-0.5 hover:shadow-sm relative z-0 hover:z-10 bg-white">
                        <td class="px-6 py-4 text-slate-500 font-mono text-xs">
                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                        </td>
                        <td class="px-6 py-4 font-medium text-slate-800">
                            {{ $log->user->name ?? 'Sistem / Anonim' }}
                            <div class="text-xs text-slate-500">{{ $log->user->role ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 text-slate-700 border border-slate-200 shadow-sm">
                                {{ $log->action }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-mono">
                            {{ $log->target_id ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button @click="selectedLog = {{ json_encode(['date' => $log->created_at->format('Y-m-d H:i:s'), 'action' => $log->action, 'user' => $log->user->name ?? 'Sistem / Anonim', 'oldData' => $log->old_data ? json_encode(json_decode($log->old_data), JSON_PRETTY_PRINT) : 'null', 'newData' => $log->new_data ? json_encode(json_decode($log->new_data), JSON_PRETTY_PRINT) : 'null']) }}; logModalOpen = true" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-50 text-slate-600 hover:bg-slate-700 hover:text-white transition-colors border border-slate-200" title="Lihat Payload">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-slate-500">
                            Tidak ada rekaman log aktivitas.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $logs->links() }}
        </div>

        <!-- Detail Modal (Alpine.js) -->
        <template x-teleport="body">
            <div x-show="logModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="logModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-slate-900/60 backdrop-blur-sm" aria-hidden="true" @click="logModalOpen = false"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="logModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-3xl p-8 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-3xl">
                        <div class="flex justify-between items-start mb-5">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                    Payload Data Viewer
                                </h3>
                                <p class="text-sm text-slate-500 mt-1" x-text="'Tindakan: ' + (selectedLog ? selectedLog.action : '') + ' oleh ' + (selectedLog ? selectedLog.user : '')"></p>
                            </div>
                            <button @click="logModalOpen = false" class="text-slate-400 hover:text-rose-500 transition-colors p-1 bg-slate-50 hover:bg-rose-50 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mt-6" x-if="selectedLog">
                            <div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 flex items-center">
                                    <span class="w-2 h-2 rounded-full bg-rose-500 mr-2"></span> Data Lama
                                </div>
                                <div class="bg-slate-900 rounded-xl p-4 overflow-x-auto border border-slate-700 shadow-inner max-h-[400px] overflow-y-auto custom-scrollbar">
                                    <pre class="text-xs text-rose-300 font-mono" x-text="selectedLog ? selectedLog.oldData : ''"></pre>
                                </div>
                            </div>
                            
                            <div>
                                <div class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2 flex items-center">
                                    <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Data Baru
                                </div>
                                <div class="bg-slate-900 rounded-xl p-4 overflow-x-auto border border-slate-700 shadow-inner max-h-[400px] overflow-y-auto custom-scrollbar">
                                    <pre class="text-xs text-emerald-300 font-mono" x-text="selectedLog ? selectedLog.newData : ''"></pre>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 text-center text-xs text-slate-400 font-mono" x-text="'[ ' + (selectedLog ? selectedLog.date : '') + ' ]'"></div>
                    </div>
                </div>
            </div>
        </template>
        
        <style>
            .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
            .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
            .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #475569; border-radius: 10px; }
        </style>
    </div>
</x-app-layout>

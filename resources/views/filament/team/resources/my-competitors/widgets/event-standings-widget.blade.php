<x-filament::section>
    <x-slot name="heading">
        Klasemen Sementara
    </x-slot>

    @if(empty($standings))
        <div class="p-4 text-center text-gray-500">
            Data klasemen belum tersedia.
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            @foreach($standings as $group => $teams)
                <div class="border rounded-xl overflow-hidden shadow-sm">
                    <div class="bg-gray-50 px-4 py-2 border-b font-bold text-sm text-gray-700">
                        Group {{ $group }}
                    </div>
                    <table class="w-full text-xs text-left">
                        <thead class="bg-gray-100 text-gray-500 uppercase font-bold">
                            <tr>
                                <th class="px-3 py-2 w-8">#</th>
                                <th class="px-3 py-2">Tim</th>
                                <th class="px-2 py-2 text-center">P</th>
                                <th class="px-2 py-2 text-center">GD</th>
                                <th class="px-3 py-2 text-right">Pts</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($teams as $team)
                                {{-- Highlight jika ini tim saya --}}
                                <tr class="{{ isset($myTeamId) && $team->id == $myTeamId ? 'bg-amber-50' : 'hover:bg-gray-50' }}">
                                    <td class="px-3 py-2 font-mono text-gray-500">{{ $loop->iteration }}</td>
                                    <td class="px-3 py-2 flex items-center gap-2">
                                        @if($team->logo)
                                            <img src="{{ Storage::url($team->logo) }}" class="w-5 h-5 rounded-full object-cover">
                                        @endif
                                        <span class="font-medium truncate max-w-[120px] {{ isset($myTeamId) && $team->id == $myTeamId ? 'text-amber-700 font-bold' : '' }}">
                                            {{ $team->name }}
                                        </span>
                                    </td>
                                    <td class="text-center px-2">{{ $team->stats['played'] }}</td>
                                    <td class="text-center px-2 {{ $team->stats['goal_diff'] < 0 ? 'text-red-500' : 'text-green-600' }}">
                                        {{ $team->stats['goal_diff'] > 0 ? '+'.$team->stats['goal_diff'] : $team->stats['goal_diff'] }}
                                    </td>
                                    <td class="text-right px-3 font-bold">{{ $team->stats['points'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>
    @endif
</x-filament::section>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Results') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between mb-4">
                        <form action="{{ route('results.search') }}">
                            <div class="flex mb-4">
                                <x-input id="keyword" class="block w-60 h-8 mr-2" type="text" name="keyword" :value="request('keyword')" autofocus placeholder="Enter Keywords..." />
                                <x-button class="h-8 w-24 justify-center">
                                    {{ __('Search') }}
                                </x-button>
                            </div>
                        </form>
                        <div>
                            <a href="{{ route('results.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150'">Import CSV</a>
                        </div>
                    </div>
                    <div>
                        <table class="w-full table-auto">
                            <thead class="border-b-2 text-left">
                                <tr>
                                    <th class="pb-4">No.</th>
                                    <th class="pb-4">Keyword</th>
                                    <th class="pb-4">Total Advertisers</th>
                                    <th class="pb-4">Total Links</th>
                                    <th class="pb-4">Search Summary</th>
                                    <th class="pb-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($results->isNotEmpty())
                                @php
                                    $startNo = $results->firstItem();
                                @endphp
                                @foreach($results as $key => $result)
                                <tr>
                                    <td class="pt-4">{{ $key + $startNo }}</td>
                                    <td class="pt-4">{{ $result->keyword }}</td>
                                    <td class="pt-4">{{ $result->total_advertisers }}</td>
                                    <td class="pt-4">{{ $result->total_links }}</td>
                                    <td class="pt-4">{{ $result->search_summary }}</td>
                                    <td class="pt-4">
                                        <a href="{{ route('results.show', $result->id) }}" class="text-blue-800 hover:underline">View</a>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr class="text-center">
                                    <td colspan="6" class="text-red-600 pt-4">There is no result to display.</td>
                                </tr>
                               @endif
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $results->links() }}
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('results.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150'">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                          Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

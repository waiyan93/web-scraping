<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Keyword: "{{ $result->keyword }}"
        </h2>
    </x-slot>

    <div class="py-12" id="app">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-4">
                        <div class="flex mb-4">
                            <p class="mr-10">Total Advertisers: </p>
                            <p>{{ $result->total_advertisers }}</p>
                        </div>
                        <div class="flex mb-4">
                            <p class="mr-20">Total Links: </p>
                            <p>{{ $result->total_links }}</p>
                        </div>
                        <div class="flex mb-4">
                            <p class="mr-8">Search Summary: </p>
                            <p>{{ $result->search_summary }}</p>
                        </div>
                        <div class="flex mb-4">
                            <p class="mr-28">HTML: </p>
                            <a href="{{ route('results.html', $result->id) }}" class="text-blue-800 hover:underline flex items-center" target="_blank">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                  </svg>
                                View HTML
                            </a>
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

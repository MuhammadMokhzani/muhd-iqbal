<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Senarai Pesanan') }}
        </h2>
    </x-slot>
    <style>

    </style>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <section class="py-1">
                        <div class="grid md:grid-cols-2 my-2">
                            <div class="mt-3">
                                <a href="/orders/create"
                                    class='items-center mx-5 bg-green-500 hover:bg-gray-700 rounded-lg shadow-xl font-medium text-white px-4 py-2'>
                                    {{ __('Tambah Pesanan') }}
                                </a>
                            </div>

                            <div class="text-right">
                                <form action="/orders">
                                    <input type="text" name="search" placeholder="Carian..."
                                        class="items-center mx-1 rounded-lg shadow-xl font-medium px-4 py-2"
                                        value="{{ request('search') }}">
                                        <a href="/orders" class="text-2xl" title="Tunjuk semua">&#8635;</a>
                                </form>
                            </div>
                        </div>
                        <div class="w-full xl:w-full mb-12 xl:mb-0 px-4 mx-auto">
                            <div
                                class="relative flex flex-col min-w-0 break-words bg-white w-full mb-6 shadow-lg rounded">
                                <div class="block w-full overflow-x-auto">
                                    <table class="items-center bg-transparent w-full border-collapse ">
                                        <thead>
                                            <tr>
                                                <th
                                                    class="bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-base uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                                    {{ __('No') }}
                                                </th>
                                                <th
                                                    class="px-6 bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-base uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left">
                                                    {{ __('Pelanggan') }}
                                                </th>
                                                <th
                                                    class="bg-blueGray-50 text-blueGray-500 align-middle border border-solid border-blueGray-100 py-3 text-base uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-center">
                                                    {{ __('Status') }}
                                                </th>
                                                @if (request('payment'))
                                                    <th class="bg-blueGray-50 text-blueGray-500 text-center align-middle border border-solid border-blueGray-100 py-3 text-sm uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold">
                                                        <span class="text-red-600">{{ __('Tunggakan') }}</span> <span class="font-bold">/</span> {{ __('Jumlah') }}
                                                    </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr onclick="window.location='/orders/view/{{ $order->id }}'"
                                                    class="hover:bg-gray-100 cursor-pointer">
                                                    <th
                                                        class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-sm whitespace-nowrap p-4">

                                                        @if ($order->date >= env('POS_START'))
                                                            <div
                                                                class="w-3 h-3 inline-block
                                                            @if ($order->due == $order->grand_total) bg-red-600
                                                            @elseif ($order->due > 0) bg-yellow-500
                                                            @else bg-green-600 @endif
                                                            ">
                                                            </div>
                                                        @endif
                                                        {{ order_num($order->id) }}
                                                    </th>
                                                    <td
                                                        class="flex border-t-0 px-6 align-middle border-l-0 border-r-0 text-sm whitespace-nowrap p-4">
                                                        <div id="branch-label"
                                                            class="w-5 h-5 mr-2 rounded-full bg-{{ $order->branch->color_code }}-600">
                                                        </div>
                                                        {{ $order->customer->name }}
                                                        <div id="urgent-{{ $order->id }}"
                                                            class="ml-2 items-center bg-red-600 leading-none text-white rounded-full p-1 shadow text-sm font-bold hidden">
                                                            <span class="inline-flex px-1">{{ __('URGENT') }}</span>
                                                        </div>
                                                    </td>
                                                    <td
                                                        class="border-t-0 px-6 text-center align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-2">

                                                        @php
                                                            $status = $is_done = $is_printing = $is_approved = $is_design = $is_pending = 0;
                                                            $count = count($order->order_item);
                                                            $urgent = 0;
                                                        @endphp

                                                        @foreach ($order->order_item as $item)
                                                            @php
                                                                if ($item->is_done) {
                                                                    $is_done++;
                                                                } elseif ($item->is_printing) {
                                                                    $is_printing++;
                                                                } elseif ($item->is_approved) {
                                                                    $is_approved++;
                                                                } elseif ($item->is_design) {
                                                                    $is_design++;
                                                                } else {
                                                                    $is_pending++;
                                                                }

                                                                if ($item->is_urgent) {
                                                                    $urgent++;
                                                                }

                                                            @endphp
                                                        @endforeach

                                                        @unless($count == 0)
                                                            @php $status = $status/$count @endphp

                                                            @if ($is_pending)
                                                                <div
                                                                    class="inline-flex items-center bg-red-600 leading-none text-white rounded-full p-1 shadow text-sm font-bold">
                                                                    <span
                                                                        class="inline-flex text-white rounded-full h-6 w-6 justify-center items-center text-base">{{ $is_pending }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($is_design)
                                                                <div
                                                                    class="inline-flex items-center bg-yellow-400 leading-none text-white rounded-full p-1 shadow text-sm font-bold">
                                                                    <span
                                                                        class="inline-flex text-white bg-yellow-400 rounded-full h-6 w-6 justify-center items-center text-base">{{ $is_design }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($is_approved)
                                                                <div
                                                                    class="inline-flex items-center bg-yellow-700 leading-none text-white rounded-full p-1 shadow text-sm font-bold">
                                                                    <span
                                                                        class="inline-flex text-white bg-yellow-700 rounded-full h-6 w-6 justify-center items-center text-base font-bold">{{ $is_approved }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($is_printing)
                                                                <div
                                                                    class="inline-flex items-center bg-purple-600 leading-none text-white rounded-full p-1 shadow text-sm font-bold">
                                                                    <span
                                                                        class="inline-flex text-white bg-purple-600 rounded-full h-6 w-6 justify-center items-center text-base">{{ $is_printing }}</span>
                                                                </div>
                                                            @endif
                                                            @if ($is_done)
                                                                <div
                                                                    class="inline-flex items-center bg-green-600 leading-none text-white rounded-full p-1 shadow text-sm font-bold">
                                                                    <span
                                                                        class="inline-flex text-white bg-green-600 rounded-full h-6 w-6 justify-center items-center text-base font-bold">{{ $is_done }}</span>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div
                                                                class="inline-flex items-center bg-gray-600 leading-none text-white rounded-full p-1 shadow text-sm font-bold">
                                                                <span
                                                                    class="inline-flex px-1">{{ __('Tiada Item') }}</span>
                                                            </div>
                                                        @endunless
                                                    </td>
                                                    @if (request('payment'))
                                                        <td class="text-center">
                                                            <div class="text-sm"><span class="text-red-600 font-extrabold">{{ RM($order->due) }}</span> <span class="font-bold">/</span> {{ RM($order->grand_total) }}</div>
                                                        </td>
                                                    @endif
                                                </tr>
                                                @if ($urgent > 0)
                                                    <script>
                                                        document.getElementById("urgent-{{ $order->id }}").style.display = 'block';
                                                    </script>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            {{ $orders->withQueryString()->links() }}
                        </div>
                        <div class="m-5 grid md:grid-cols-2">
                            <div>
                                @foreach ($branches as $branch)
                                    <div onclick="window.location='/orders/location/{{ $branch->id }}'"
                                        class="inline-flex items-center bg-white leading-none text-{{ $branch->color_code }}-600 rounded-full p-2 shadow text-sm cursor-pointer">
                                        <span
                                            class="inline-flex bg-{{ $branch->color_code }}-600 text-white rounded-full h-6 px-3 justify-center items-center text-"></span>
                                        <span class="inline-flex px-2">{{ ucwords($branch->shortname) }}</span>
                                    </div>
                                @endforeach
                            </div>
                            <div>
                                <div class="flex flex-col-reverse md:flex-row-reverse mt-5 gap-3">
                                    <div onclick="window.location='/orders/item/status/is_done'"
                                        class="inline-flex items-center bg-green-600 leading-none text-white rounded-full p-1 shadow text-sm font-bold cursor-pointer">
                                        <span class="inline-flex px-1">{{ __('Selesai') }}</span>
                                    </div>
                                    <div onclick="window.location='/orders/item/status/is_approved'"
                                        class="inline-flex items-center bg-yellow-700 leading-none text-white rounded-full p-1 shadow text-sm font-bold cursor-pointer">
                                        <span class="inline-flex px-1">{{ __('Production') }}</span>
                                    </div>
                                    <div onclick="window.location='/orders/item/status/is_design'"
                                        class="inline-flex items-center bg-yellow-400 leading-none text-white rounded-full p-1 shadow text-sm font-bold cursor-pointer">
                                        <span class="inline-flex px-1">{{ __('Design') }}</span>
                                    </div>
                                    <div onclick="window.location='/orders/item/status/is_pending'"
                                        class="inline-flex items-center bg-red-600 leading-none text-white rounded-full p-1 shadow text-sm font-bold cursor-pointer">
                                        <span class="inline-flex px-1">{{ __('Pending') }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-row md:flex-row-reverse mt-5 gap-3">
                                    <div onclick="window.location='/orders?payment=paid'"
                                        class="inline-flex flex-grow md:flex-grow-0 items-center bg-green-600 leading-none text-white p-1 shadow text-sm font-bold cursor-pointer">
                                        <span class="inline-flex px-1">{{ __('Paid') }}</span>
                                    </div>
                                    <div onclick="window.location='/orders?payment=partial'"
                                        class="inline-flex flex-grow md:flex-grow-0 items-center bg-yellow-500 leading-none text-white p-1 shadow text-sm font-bold cursor-pointer">
                                        <span class="inline-flex px-1">{{ __('Partial') }}</span>
                                    </div>
                                    <div onclick="window.location='/orders?payment=unpaid'"
                                        class="inline-flex flex-grow md:flex-grow-0 items-center bg-red-600 leading-none text-white p-1 shadow text-sm font-bold cursor-pointer">
                                        <span class="inline-flex px-1">{{ __('Unpaid') }}</span>
                                    </div>
                                    <div class="text-sm font-bold">Tertunggak: RM{{ RM($dues) }} </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="mx-5">
                        <a href="/orders/no-pickup"
                            class="mr-5 bg-white border border-gray-600 hover:bg-blue-700 hover:text-white text-black font-bold py-2 px-6">Senarai
                            belum pickup</a>
                    </div>
                    <x-dashboard-link />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

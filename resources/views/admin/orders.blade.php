<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard - Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        @include('errors.list')
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <!-- This example requires Tailwind CSS v2.0+ -->
                    <div class="flex flex-col">
                        <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <div class="overflow-hidden border-b border-gray-200 shadow sm:rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-xs font-extrabold tracking-wider text-left text-gray-500 uppercase">
                                                    Name
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-xs font-extrabold tracking-wider text-left text-gray-500 uppercase">
                                                    Role
                                                </th>
                                                @if (auth()->user()->isAdmin)
                                                <th scope="col" class="px-6 py-3 text-xs font-extrabold tracking-wider text-left text-gray-500 uppercase">
                                                    <span class="sr-only">Action</span>
                                                </th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($orders as $order)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="ml-4">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ ucwords($order->name) }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ ucwords($order->type) }} @if($order->type=='service') Provider @endif</div>
                                                    @if($order->services)
                                                    @foreach ($order->services as $service)
                                                    <div class="text-sm text-gray-500">{{ ucfirst($service->name) }}</div>
                                                    @endforeach
                                                    @endif
                                                </td>
                                                @if (auth()->user()->isAdmin)
                                                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                                    <form id="delete-user" method="POST" action="{{ route('delete-user', ['id'=>$order->id]) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a class="px-4 py-2 font-bold text-white bg-red-500 rounded hover:bg-red-700" href="{{ route('delete-user', ['id'=>$order->id]) }}" onclick="event.preventDefault(); this.closest('form').submit();">
                                                            Delete
                                                        </a>
                                                    </form>
                                                </td>
                                                @endif
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! $orders->links() !!}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

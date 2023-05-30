@extends('components.app_layout_dashboard')

@section('body')
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <h3 class="text-base font-semibold leading-7 text-gray-900">Transaction Information</h3>
        <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Transaction details and update status.</p>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="py-6 grid grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Customer name</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $transaction->user->name }}
                    </dd>
                </div>
                <div class="py-6 grid grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Nomor transaction</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $transaction->numbtrans }}
                    </dd>
                </div>
                <div class="py-6 grid grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Address</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ $transaction->address }}
                    </dd>
                </div>
                <div class="py-6 grid grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Item products</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        @for ($i = 0; $i < $transaction->items->count(); $i++)
                            {{ $transaction->items[$i]->product->name }} ({{ $transaction->items[$i]->qty }} Pcs)
                            @if ($i + 1 == $transaction->items->count())
                                .
                            @else
                                ,
                            @endif
                        @endfor
                    </dd>
                </div>
                <div class="py-6 grid grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Shipping price</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        Rp{{ number_format($transaction->shipping_price, 0) }}
                    </dd>
                </div>
                <div class="py-6 grid grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Total price</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        Rp{{ number_format($transaction->total_price, 0) }}
                    </dd>
                </div>
            </dl>
        </div>

        <form action="/transaction/{{ $transaction->id }}" method="post">
            @csrf
            @method('put')
            <label class="block mt-4 text-sm">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-400">
                    Status
                </span>
                <select
                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 @error('status') border-red-600 @enderror form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                    name="status">
                    <option value="PENDING" {{ old('status', $transaction->status) == 'PENDING' ? 'selected' : '' }}>PENDING
                    </option>
                    <option value="SUCCESS" {{ old('status', $transaction->status) == 'SUCCESS' ? 'selected' : '' }}>SUCCESS
                    </option>
                    <option value="PROGRESS" {{ old('status', $transaction->status) == 'PROGRESS' ? 'selected' : '' }}>
                        PROGRESS</option>
                    <option value="FAILED" {{ old('status', $transaction->status) == 'FAILED' ? 'selected' : '' }}>FAILED
                    </option>
                    <option value="CANCELED" {{ old('status', $transaction->status) == 'CANCELED' ? 'selected' : '' }}>
                        CANCELED</option>
                    <option value="CONFIRMED" {{ old('status', $transaction->status) == 'CONFIRMED' ? 'selected' : '' }}>
                        CONFIRMED</option>
                    <option value="ACCEPTED" {{ old('status', $transaction->status) == 'ACCEPTED' ? 'selected' : '' }}>
                        ACCEPTED</option>
                    <option value="UNPAY" {{ old('status', $transaction->status) == 'UNPAY' ? 'selected' : '' }}>UNPAY
                    </option>
                    <option value="PAYED" {{ old('status', $transaction->status) == 'PAYED' ? 'selected' : '' }}>PAYED
                    </option>
                </select>
                @error('status')
                    <span class="text-xs text-red-600 dark:text-red-400">
                        {{ $message }}
                    </span>
                @enderror
            </label>

            <div class="py-4">
                <button
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                    Save
                </button>
                <a class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-red-600 border border-transparent rounded-lg active:bg-red-600 hover:bg-red-700 focus:outline-none focus:shadow-outline-red"
                    style="padding: 9px 16px" href="{{ route('index') }}">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection

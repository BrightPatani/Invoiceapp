@extends('layouts.app')

@section('title', 'Invoice ' . $invoice->invoice_number)

@section('content')
<div class="min-h-screen py-6 sm:py-8 px-4 sm:px-6 lg:px-8" x-data="{ confirmDelete: false }">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Header -->
            <div class="bg-gradient-to-r from-[#003686] to-blue-800 text-white px-4 sm:px-6 md:px-8 py-4 sm:py-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                    <div>
                        <h2 class="text-[#003686] text-2xl sm:text-3xl font-bold mb-1">Invoice {{ $invoice->invoice_number }}</h2>
                        <p class="text-[#ff6701] text-sm">Invoice details and summary</p>
                    </div>
                    <div class="flex flex-wrap gap-3 no-print justify-center md:justify-end">
                        <a href="{{ route('invoices.edit', $invoice) }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#003686] border-l-4 border-[#FF6701] text-white rounded-lg text-sm font-medium shadow-md hover:shadow-lg hover:bg-opacity-90 transition">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-sm text-white font-medium shadow-md hover:shadow-lg transition">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                        <button onclick="downloadPDF()" 
                                class="inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 rounded-lg text-sm text-white font-medium shadow-md hover:shadow-lg transition">
                            <i class="fas fa-download mr-2"></i>PDF
                        </button>
                    </div>
                </div>
            </div>

            <!-- Print-specific header -->
            <div class="print-only hidden">
                <div class="text-center border-b-2 border-[#003686] pb-4 mb-6">
                    <h1 class="text-3xl font-bold text-[#003686] mb-2">INVOICE</h1>
                    <div class="text-lg font-semibold">{{ $invoice->invoice_number }}</div>
                </div>
            </div>

            <div class="p-4 sm:p-6 md:p-8 print-content">
                <!-- Invoice Details -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 bg-gradient-to-r from-gray-50 to-white p-4 sm:p-6 rounded-xl border border-[#003686]/60">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start mb-2">
                            <i class="fas fa-calendar text-[#FF6701] mr-2"></i>
                            <span class="text-sm font-semibold text-[#003686] uppercase">Invoice Date</span>
                        </div>
                        <div class="text-lg font-bold text-[#003686]">{{ $invoice->invoice_date->format('M d, Y') }}</div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start mb-2">
                            <i class="fas fa-clock text-[#FF6701] mr-2"></i>
                            <span class="text-sm font-semibold text-[#003686] uppercase">Due Date</span>
                        </div>
                        <div class="text-lg font-bold text-[#003686]">{{ $invoice->due_date->format('M d, Y') }}</div>
                    </div>
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start mb-2">
                            <i class="fas fa-hashtag text-[#FF6701] mr-2"></i>
                            <span class="text-sm font-semibold text-[#003686] uppercase">Invoice Number</span>
                        </div>
                        <div class="inline-flex items-center px-4 py-2 bg-[#003686] border-l-4 border-[#FF6701] text-white rounded-lg font-bold">
                            {{ $invoice->invoice_number }}
                        </div>
                    </div>
                </div>

                <!-- From/To Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-gray-50 to-white p-4 sm:p-6 rounded-xl border border-[#003686]/60">
                        <h3 class="text-lg font-bold text-[#003686] mb-4 flex items-center">
                            <i class="fas fa-building text-[#FF6701] mr-2"></i>
                            From (Billed By)
                        </h3>
                        <div class="space-y-2">
                            <div class="text-lg font-bold text-[#003686]">{{ $invoice->from_name }}</div>
                            <div class="text-[#003686]/70 whitespace-pre-line">{{ $invoice->from_address }}</div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-r from-gray-50 to-white p-4 sm:p-6 rounded-xl border border-[#003686]/60">
                        <h3 class="text-lg font-bold text-[#003686] mb-4 flex items-center">
                            <i class="fas fa-user text-[#FF6701] mr-2"></i>
                            Bill To (Client)
                        </h3>
                        <div class="space-y-2">
                            <div class="text-lg font-bold text-[#003686]">{{ $invoice->to_name }}</div>
                            <div class="text-[#003686]/70 whitespace-pre-line">{{ $invoice->to_address }}</div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mb-8">
                    <h3 class="text-lg font-bold text-[#003686] mb-4 flex items-center">
                        <i class="fas fa-list text-[#FF6701] mr-2"></i>
                        Invoice Items
                    </h3>
                    <div class="overflow-x-auto rounded-lg border-2 border-[#003686]/60 shadow-md">
                        <table class="w-full min-w-[600px]">
                            <thead class="bg-gradient-to-r from-[#003686] to-blue-800 text-white">
                                <tr>
                                    <th class="px-4 sm:px-6 py-3 text-left font-semibold">Description</th>
                                    <th class="px-4 sm:px-6 py-3 text-center font-semibold w-20">Qty</th>
                                    <th class="px-4 sm:px-6 py-3 text-right font-semibold w-32">Unit Price</th>
                                    <th class="px-4 sm:px-6 py-3 text-right font-semibold w-32">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                @foreach($invoice->items as $item)
                                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                                    <td class="px-4 sm:px-6 py-3 text-[#003686]">{{ $item->description }}</td>
                                    <td class="px-4 sm:px-6 py-3 text-center font-medium text-[#003686]">{{ $item->quantity }}</td>
                                    <td class="px-4 sm:px-6 py-3 text-right font-medium text-[#003686]">${{ number_format($item->price, 2) }}</td>
                                    <td class="px-4 sm:px-6 py-3 text-right font-bold text-green-600">${{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Totals Section -->
                <div class="flex justify-end mb-8">
                    <div class="w-full md:w-2/3 lg:w-1/2">
                        <div class="bg-gradient-to-br from-gray-50 to-white p-4 sm:p-6 rounded-xl border border-[#003686]/60 shadow-lg">
                            <h4 class="font-bold text-[#003686] mb-4 flex items-center">
                                <i class="fas fa-calculator text-[#FF6701] mr-2"></i>
                                Invoice Summary
                            </h4>
                            <div class="space-y-3 mb-4">
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-[#003686] font-medium">Subtotal:</span>
                                    <span class="font-bold text-[#003686]">${{ number_format($invoice->subtotal, 2) }}</span>
                                </div>
                                @if($invoice->discount_amount > 0)
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-[#003686] font-medium">Discount ({{ $invoice->discount_percent }}%):</span>
                                    <span class="font-bold text-red-600">-${{ number_format($invoice->discount_amount, 2) }}</span>
                                </div>
                                @endif
                                @if($invoice->tax_amount > 0)
                                <div class="flex justify-between py-2 border-b border-gray-200">
                                    <span class="text-[#003686] font-medium">Tax ({{ $invoice->tax_percent }}%):</span>
                                    <span class="font-bold text-[#003686]">${{ number_format($invoice->tax_amount, 2) }}</span>
                                </div>
                                @endif
                            </div>
                            <div class="bg-gradient-to-r from-[#003686] to-blue-800 text-white px-4 sm:px-6 py-4 rounded-lg shadow-md">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">TOTAL:</span>
                                    <span class="font-bold text-2xl">${{ number_format($invoice->total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col md:flex-row justify-between items-center pt-6 md:pt-8 border-t border-gray-200 space-y-4 md:space-y-0 no-print">
                    <a href="{{ route('invoices.index') }}" 
                       class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-200 hover:bg-gray-300 text-[#003686] rounded-lg font-medium transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Invoices
                    </a>
                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="{{ route('invoices.edit', $invoice) }}" 
                           class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-[#003686] border-l-4 border-[#FF6701] text-white rounded-lg font-medium shadow-md hover:shadow-lg transition">
                            <i class="fas fa-edit mr-2"></i>Edit Invoice
                        </a>
                        <button onclick="window.print()" 
                                class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                        <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    @click.prevent="confirmDelete = true"
                                    class="inline-flex items-center px-4 sm:px-6 py-2 sm:py-3 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium shadow-md hover:shadow-lg transition">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div x-show="confirmDelete" 
                     class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 no-print"
                     x-cloak>
                    <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl border border-[#003686]/60">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-exclamation-triangle text-red-500 text-2xl mr-3"></i>
                            <h3 class="text-lg font-bold text-[#003686]">Confirm Deletion</h3>
                        </div>
                        <p class="text-[#003686]/70 mb-6">Are you sure you want to delete this invoice? This action cannot be undone.</p>
                        <div class="flex justify-end gap-3">
                            <button @click="confirmDelete = false" 
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-[#003686] rounded-lg font-medium transition">
                                Cancel
                            </button>
                            <button @click="document.querySelector('form[action*=\'destroy\']').submit()" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

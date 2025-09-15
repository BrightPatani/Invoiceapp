@extends('layouts.app')

@section('title', 'All Invoices')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-100">
    <!-- Header Section -->
    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-[#003686] mb-1">All Invoices</h2>
                <p class="text-sm text-[#ff6701]">Manage and track your invoices</p>
            </div>
        </div>
    </div>

    <!-- Table Container (hidden on small screens) -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full">
            <!-- Table Header -->
            <thead>
                <tr class="bg-[#003686] text-white">
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Invoice #</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Client</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Due Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Total</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($invoices as $invoice)
                <tr class="hover:bg-gray-50 transition-all duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-[#003686] border border-blue-200">
                            {{ $invoice->invoice_number }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#003686]">
                        {{ $invoice->to_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#003686]">
                        {{ $invoice->invoice_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-[#003686]">
                        {{ $invoice->due_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-green-600">
                        ${{ number_format($invoice->total, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('invoices.show', $invoice) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-[#003686] text-white text-xs font-medium rounded-md shadow-sm hover:bg-[#002a5c] transition">
                                <i class="fas fa-eye mr-1"></i> View
                            </a>
                            <a href="{{ route('invoices.edit', $invoice) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-[#ff6701] text-white text-xs font-medium rounded-md shadow-sm hover:bg-[#e65d00] transition">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1.5 bg-red-500 text-white text-xs font-medium rounded-md shadow-sm hover:bg-red-600 transition">
                                    <i class="fas fa-trash mr-1"></i> Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-4">
                            <i class="fas fa-file-invoice text-2xl text-[#ff6701]"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
                        <p class="text-gray-500 mb-4">Get started by creating your first invoice</p>
                        <a href="{{ route('invoices.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-[#ff6701] text-white font-medium rounded-lg shadow-md hover:bg-[#e65d00] transition">
                            <i class="fas fa-plus mr-2"></i> Create your first invoice
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile view card display -->
    <div class="md:hidden divide-y divide-gray-200">
        @forelse($invoices as $invoice)
        <div class="p-4 flex flex-col space-y-2">
            <div class="flex justify-between">
                <span class="text-sm font-medium text-[#003686]">#{{ $invoice->invoice_number }}</span>
                <span class="text-green-600 font-bold">${{ number_format($invoice->total, 2) }}</span>
            </div>
            <div class="text-sm text-gray-700">{{ $invoice->to_name }}</div>
            <div class="flex justify-between text-xs text-gray-500">
                <span>Invoice: {{ $invoice->invoice_date->format('M d, Y') }}</span>
                <span>Due: {{ $invoice->due_date->format('M d, Y') }}</span>
            </div>
            <div class="flex flex-wrap gap-2 mt-2">
                <a href="{{ route('invoices.show', $invoice) }}" 
                   class="flex-1 text-center px-3 py-1.5 bg-[#003686] text-white text-xs rounded-md hover:bg-[#002a5c] transition">
                    <i class="fas fa-eye mr-1"></i> View
                </a>
                <a href="{{ route('invoices.edit', $invoice) }}" 
                   class="flex-1 text-center px-3 py-1.5 bg-[#ff6701] text-white text-xs rounded-md hover:bg-[#e65d00] transition">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="flex-1"
                      onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full text-center px-3 py-1.5 bg-red-500 text-white text-xs rounded-md hover:bg-red-600 transition">
                        <i class="fas fa-trash mr-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-4">
                <i class="fas fa-file-invoice text-2xl text-[#ff6701]"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
            <p class="text-gray-500 mb-4">Get started by creating your first invoice</p>
            <a href="{{ route('invoices.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-[#ff6701] text-white font-medium rounded-lg shadow-md hover:bg-[#e65d00] transition">
                <i class="fas fa-plus mr-2"></i> Create your first invoice
            </a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600">
                <div class="mb-2 sm:mb-0">
                    Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
                </div>
                <div class="pagination-wrapper">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

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

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <!-- Table Header -->
            <thead>
                <tr class="bg-gradient-to-r from-[#003686] to-[#003686] text-[#003686]">
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
                <tr class="hover:bg-gradient-to-r hover:from-[#ff6701] hover:to-blue-50 transition-all duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-[#003686] border border-blue-200">
                                {{ $invoice->invoice_number }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-[#003686]">{{ $invoice->to_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-[#003686]">{{ $invoice->invoice_date->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-[#003686]">{{ $invoice->due_date->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-lg font-bold text-green-600">${{ number_format($invoice->total, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('invoices.show', $invoice) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-[#003686] hover:bg-[#003686] text-white text-xs font-medium rounded-md transition-colors duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-eye mr-1"></i>
                                View
                            </a>
                            <a href="{{ route('invoices.edit', $invoice) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-[#ff6701] hover:bg-[#ff6701] text-white text-xs font-medium rounded-md transition-colors duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-edit mr-1"></i>
                                Edit
                            </a>
                            <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Are you sure you want to delete this invoice?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white text-xs font-medium rounded-md transition-colors duration-200 shadow-sm hover:shadow-md">
                                    <i class="fas fa-trash mr-1"></i>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12">
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-[#ff6701]-100 mb-4">
                                <i class="fas fa-file-invoice text-2xl text-[#ff6701]"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
                            <p class="text-gray-500 mb-4">Get started by creating your first invoice</p>
                            <a href="{{ route('invoices.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-brand-[#ff6701] to-[#ff6701] hover:from-[#ff6701] hover:to-[#ff6701]-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                                <i class="fas fa-plus mr-2"></i>
                                Create your first invoice
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($invoices->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-[#0003686]">
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
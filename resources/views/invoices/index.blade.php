@extends('layouts.app')

@section('title', 'All Invoices')

@section('content')
<div class="bg-white rounded-xl shadow-lg border border-gray-100">
    <!-- Header Section -->
    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1">All Invoices</h2>
                <p class="text-sm text-gray-600">Manage and track your invoices</p>
            </div>
            <a href="{{ route('invoices.create') }}" 
               class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center space-x-2">
                <i class="fas fa-plus"></i>
                <span>Create New Invoice</span>
            </a>
        </div>
    </div>

    <!-- Table Container -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <!-- Table Header -->
            <thead>
                <tr class="bg-gradient-to-r from-blue-900 to-blue-800 text-white">
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
                <tr class="hover:bg-gradient-to-r hover:from-orange-50 hover:to-blue-50 transition-all duration-200">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                {{ $invoice->invoice_number }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">{{ $invoice->to_name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $invoice->invoice_date->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-600">{{ $invoice->due_date->format('M d, Y') }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-lg font-bold text-green-600">${{ number_format($invoice->total, 2) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('invoices.show', $invoice) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors duration-200 shadow-sm hover:shadow-md">
                                <i class="fas fa-eye mr-1"></i>
                                View
                            </a>
                            <a href="{{ route('invoices.edit', $invoice) }}" 
                               class="inline-flex items-center px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium rounded-md transition-colors duration-200 shadow-sm hover:shadow-md">
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
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-orange-100 mb-4">
                                <i class="fas fa-file-invoice text-2xl text-orange-500"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No invoices found</h3>
                            <p class="text-gray-500 mb-4">Get started by creating your first invoice</p>
                            <a href="{{ route('invoices.create') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-brand-orange to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
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
                <div class="text-sm text-gray-700">
                    Showing {{ $invoices->firstItem() }} to {{ $invoices->lastItem() }} of {{ $invoices->total() }} results
                </div>
                <div class="pagination-wrapper">
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    /* Custom styles using your brand colors */
    .pagination-wrapper .pagination {
        display: flex;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .pagination-wrapper .page-link {
        color: #003686;
        background-color: #fff;
        border: 1px solid #d1d5db;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination-wrapper .page-link:hover {
        background-color: #FF6701;
        color: white;
        border-color: #FF6701;
    }
    
    .pagination-wrapper .page-item.active .page-link {
        background-color: #003686;
        border-color: #003686;
        color: white;
    }
    
    .pagination-wrapper .page-item:first-child .page-link {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }
    
    .pagination-wrapper .page-item:last-child .page-link {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
    
    /* Custom gradients for brand colors */
    .bg-brand-blue {
        background-color: #003686;
    }
    
    .bg-brand-orange {
        background-color: #FF6701;
    }
    
    .text-brand-blue {
        color: #003686;
    }
    
    .text-brand-orange {
        color: #FF6701;
    }
</style>
@endsection
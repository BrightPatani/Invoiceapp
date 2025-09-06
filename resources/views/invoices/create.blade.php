@extends('layouts.app')

@section('content')
<div class="min-h-screen py-8" x-data="invoiceForm()">
    <div class="max-w-6xl mx-auto px-4">
        <form id="invoiceForm" action="{{ route('invoices.store') }}" method="POST" @submit.prevent="validateAndSubmit">
            @csrf
            
            <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100">
                <!-- Header Section -->
                <div class="bg-gradient-to-r from-[#003686] to-blue-800 px-8 py-6 flex justify-between items-center">
                    <div>
                        <h1 class="text-[#003686] text-3xl font-bold mb-1">Create Invoice</h1>
                        <p class="text-[#003686]/50 text-sm">Generate a new invoice for your client</p>
                    </div>
                    <a href="{{ route('invoices.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-[#003686] hover:bg-opacity-30 text-white rounded-lg font-medium transition-all duration-200 backdrop-blur-sm border-l-4 border-[#FF6701]">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Invoices
                    </a>
                </div>

                <div class="p-8">
                    <!-- Invoice Details -->
                    <div class="flex justify-between items-start mb-8 bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border border-gray-100">
                        <div class="w-1/2 space-y-6">
                            <div class="group">
                                <label for="invoice_date" class="text-sm font-semibold text-[#003686] mb-2 flex items-center">
                                    <i class="fas fa-calendar text-[#FF6701] mr-2"></i>
                                    Invoice Date
                                </label>
                                <input type="date" 
                                       id="invoice_date" 
                                       name="invoice_date" 
                                       x-model="form.invoice_date"
                                       value="{{ old('invoice_date', date('Y-m-d')) }}"
                                       class="p-3 w-full rounded-lg text-[#003686] border-2  border-[#003686] focus:ring-2 focus:ring-[#FF6701] focus:border-[#FF6701] transition-all duration-200 bg-white shadow-sm"
                                       required>
                            </div>
                            <div class="group">
                                <label for="due_date" class="text-sm font-semibold text-[#003686] mb-2 flex items-center">
                                    <i class="fas fa-clock text-[#FF6701] mr-2"></i>
                                     Due Date
                                </label>
                                <input type="date" 
                                       id="due_date" 
                                       name="due_date" 
                                       x-model="form.due_date"
                                       value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}"
                                       class="p-3 w-full rounded-lg text-[#003686] border-2 border-[#003686] focus:ring-2 focus:ring-[#FF6701] focus:border-[#FF6701] transition-all duration-200 bg-white shadow-sm"
                                       required>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="bg-[#003686] border-l-4 border-[#ff6701] text-white p-4 rounded-lg shadow-md">
                                <div class="text-sm opacity-90 mb-1">Invoice Number</div>
                                <div class="text-xl font-bold" x-text="form.invoice_no"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Items Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-bold text-[#003686] mb-4 flex items-center">
                            <i class="fas fa-list text-[#FF6701] mr-2"></i>
                            Invoice Items
                        </h3>
                        
                        <!-- Items Table Header -->
                        <div class="bg-gradient-to-r from-[#003686] to-blue-800 border-2 border-[#003686]/60 text-white rounded-t-lg shadow-md">
                            <div class="grid grid-cols-12 gap-4 px-6 py-4 font-semibold">
                                <div class="col-span-2 flex items-center text-[#003686]">
                                    <i class="fas fa-hashtag mr-2 text-[#ff6701]"></i>Qty
                                </div>
                                <div class="col-span-5 flex items-center text-[#003686]">
                                    <i class="fas fa-file-text mr-2 text-[#ff6701]"></i>Description
                                </div>
                                <div class="col-span-2 flex items-center text-[#003686]">
                                    <i class="fas fa-dollar-sign mr-2 text-[#ff6701]"></i>Price
                                </div>
                                <div class="col-span-2 flex items-center text-[#003686]">
                                    <i class="fas fa-calculator mr-2 text-[#ff6701]"></i>Amount
                                </div>
                                <div class="col-span-1 text-center text-[#003686]">Action</div>
                            </div>
                        </div>

                        <!-- Items Container -->
                        <div id="itemsContainer" class="border-2 border-t-0 border-[#003686]/60 min-h-[200px] bg-white rounded-b-lg shadow-md">
                            <template x-for="(item, index) in form.items" :key="index">
                                <div class="item-row border-b border-gray-100 hover:bg-gradient-to-r hover:from-[#FF6701] hover:from-[1%] hover:to-[#003686] hover:to-[99%] hover:bg-opacity-5 transition-all duration-200" :data-item-id="index + 1">
                                    <div class="grid grid-cols-12 gap-4 px-6 py-4 items-center">
                                        <div class="col-span-2 text-[#ff6701]">
                                            <input type="number" 
                                                   :name="'items[' + (index + 1) + '][quantity]'"
                                                   x-model.number="item.quantity"
                                                   min="1"
                                                   class="p-3 w-full rounded-lg border-2 border-[#003686]/60 text-center focus:ring-2 focus:ring-[#FF6701] focus:border-[#FF6701] transition-all duration-200 bg-white shadow-sm font-medium"
                                                   @input="calculateItemAmount(index)"
                                                   required>
                                        </div>
                                        <div class="col-span-5 text-[#ff6701]">
                                            <input type="text" 
                                                   :name="'items[' + (index + 1) + '][description]'"
                                                   x-model="item.description"
                                                   placeholder="Enter item description..."
                                                   class="p-3 w-full rounded-lg border-2 border-[#003686]/60 focus:ring-2 focus:ring-[#FF6701] focus:border-[#FF6701] transition-all duration-200 bg-white shadow-sm"
                                                   required>
                                        </div>
                                        <div class="col-span-2 text-[#ff6701]">
                                            <input type="number" 
                                                   :name="'items[' + (index + 1) + '][price]'"
                                                   x-model.number="item.price"
                                                   step="0.01" 
                                                   min="0"
                                                   placeholder="0.00"
                                                   class="p-3 w-full rounded-lg border-2 border-[#003686]/60 text-center focus:ring-2 focus:ring-[#FF6701] focus:border-[#FF6701] transition-all duration-200 bg-white shadow-sm font-medium"
                                                   @input="calculateItemAmount(index)"
                                                   required>
                                        </div>
                                        <div class="col-span-2 text-center text-[#ff6701]">
                                            <div class="bg-gray-50 p-3 rounded-lg border-2 border-[#003686]/60">
                                                <span x-text="'$' + item.amount.toFixed(2)" class="font-bold text-green-600 text-lg"></span>
                                            </div>
                                        </div>
                                        <div class="col-span-1 text-center text-[#ff6701]">
                                            <button type="button" 
                                                    @click="removeItem(index)" 
                                                    class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition-all duration-200">
                                                <i class="fas fa-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <input type="hidden" :name="'items[' + (index + 1) + '][amount]'" x-model="item.amount">
                                </div>
                            </template>
                        </div>

                        <!-- Add Item Button -->
                        <div class="mt-4">
                            <button type="button" 
                                    @click="addItem"
                                    class="inline-flex items-center px-4 py-3 bg-[#003686] border-l-4 border-[#ff7601] text-white rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105">
                                <i class="fas fa-plus mr-2"></i>
                                Add New Item
                            </button>
                        </div>
                    </div>

                    <!-- Bottom Section: From/To and Totals -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
                        <!-- From/To Section -->
                        <div class="lg:col-span-2 space-y-8">
                            <!-- Discount and Tax -->
                            <div class="bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border border-[#003686]/60">
                                <h4 class="font-bold text-[#003686] mb-4 flex items-center">
                                    <i class="fas fa-percentage text-[#FF6701] mr-2"></i>
                                    Adjustments
                                </h4>
                                <div class="grid grid-cols-2 gap-6">
                                    <div>
                                        <label for="discount_percent" class="block text-sm font-semibold text-[#003686] mb-2">Discount (%)</label>
                                        <input type="number" 
                                               id="discount_percent" 
                                               name="discount_percent" 
                                               x-model.number="form.discount_percent"
                                               step="0.01" min="0" max="100"
                                               placeholder="0.00"
                                               @input="calculateTotals"
                                               class="p-3 w-full rounded-lg border-2 border-[#003686]/60 focus:ring-2 focus:ring-[#FF6701] focus:border-[#FF6701] transition-all duration-200 bg-white shadow-sm">
                                    </div>
                                    <div>
                                        <label for="tax_percent" class="block text-sm font-semibold text-[#003686] mb-2">Tax (%)</label>
                                        <input type="number" 
                                               id="tax_percent" 
                                               name="tax_percent" 
                                               x-model.number="form.tax_percent"
                                               step="0.01" min="0" max="100"
                                               placeholder="0.00"
                                               @input="calculateTotals"
                                               class="p-3 w-full rounded-lg border-2 border-[#003686]/60 focus:ring-2 focus:ring-[#FF6701] focus:border-[#FF6701] transition-all duration-200 bg-white shadow-sm">
                                    </div>
                                </div>
                            </div>

                            <!-- From Section -->
                            <div class="bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border border-[#003686]/60">
                                <h4 class="font-bold text-[#003686] mb-4 flex items-center">
                                    <i class="fas fa-building text-[#ff6701] mr-2"></i>
                                    From (Your Business)
                                </h4>
                                <div class="space-y-4">
                                    <input type="text" 
                                           name="from_name" 
                                           x-model="form.from_name"
                                           value="{{ old('from_name') }}"
                                           placeholder="Your business name"
                                           class="p-3 w-full rounded-lg border-2 border-[#003686]/60 focus:ring-2 focus:ring-[#003686] focus:border-[#003686] transition-all duration-200 bg-white shadow-sm font-medium"
                                           required>
                                    <textarea name="from_address" 
                                              rows="3"
                                              x-model="form.from_address"
                                              placeholder="Your business address"
                                              class="p-3 w-full rounded-lg border-2 border-[#003686]/60 resize-none focus:ring-2 focus:ring-[#003686] focus:border-[#003686] transition-all duration-200 bg-white shadow-sm"
                                              required>{{ old('from_address') }}</textarea>
                                </div>
                            </div>

                            <!-- To Section -->
                            <div class="bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border border-[#003686]">
                                <h4 class="font-bold text-[#003686] mb-4 flex items-center">
                                    <i class="fas fa-user text-[#FF6701] mr-2"></i>
                                    Bill To (Client)
                                </h4>
                                <div class="space-y-4">
                                    <input type="text" 
                                           name="to_name" 
                                           x-model="form.to_name"
                                           value="{{ old('to_name') }}"
                                           placeholder="Client name"
                                           class="p-3 w-full rounded-lg border-2 border-[#003686] focus:ring-2 focus:ring-[#FF6701] focus:border-[#FF6701] transition-all duration-200 bg-white shadow-sm font-medium"
                                           required>
                                    <textarea name="to_address" 
                                              rows="3"
                                              x-model="form.to_address"
                                              placeholder="Client address"
                                              class="p-3 w-full rounded-lg border-2 border-[#003686] resize-none focus:ring-2 focus:ring-[#FF6701] focus:border-[#FF6701] transition-all duration-200 bg-white shadow-sm"
                                              required>{{ old('to_address') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Totals Section -->
                        <div class="lg:col-span-1">
                            <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-xl border border-[#003686] shadow-lg sticky top-4">
                                <h4 class="font-bold text-[#003686] mb-6 flex items-center">
                                    <i class="fas fa-calculator text-[#ff6701] mr-2"></i>
                                    Invoice Summary
                                </h4>
                                
                                <div class="space-y-4 mb-6">
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span class="text-[#003686] font-medium">Subtotal:</span>
                                        <span x-text="'$' + form.subtotal.toFixed(2)" class="font-bold text-[#003686]"></span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span x-text="'Discount (' + form.discount_percent + '%):'" class="text-[#003686] font-medium"></span>
                                        <span x-text="'-$' + form.discount_amount.toFixed(2)" class="font-bold text-red-600"></span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 border-b border-gray-200">
                                        <span x-text="'Tax (' + form.tax_percent + '%):'" class="text-[#003686] font-medium"></span>
                                        <span x-text="'$' + form.tax_amount.toFixed(2)" class="font-bold text-[#003686]"></span>
                                    </div>
                                </div>
                                
                                <div class="bg-gradient-to-r from-[#003686] to-blue-800 text-[#003686] px-6 py-4 rounded-lg shadow-md">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-lg">TOTAL:</span>
                                        <span x-text="'$' + form.total.toFixed(2)" class="font-bold text-2xl"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex items-center justify-between bg-gradient-to-r from-gray-50 to-white p-6 rounded-xl border border-gray-100">
                        <a href="{{ route('invoices.index') }}" 
                           class="inline-flex items-center px-6 py-3 bg-gray-200 hover:bg-gray-300 text-[] rounded-lg font-medium transition-all duration-200">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </a>
                        
                        <button type="submit" 
                                x-bind:disabled="isSubmitting"
                                :class="{ 'opacity-50 cursor-not-allowed': isSubmitting }"
                                class="inline-flex items-center px-8 py-3 bg-[#003686] text-white rounded-lg font-bold transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105 border-l-4 border-[#ff6701]">
                            <i class="fas fa-save mr-2" :class="{ 'fa-spinner fa-spin': isSubmitting }"></i>
                            <span x-text="isSubmitting ? 'Creating Invoice...' : 'Create Invoice'"></span>
                        </button>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" id="subtotal" name="subtotal" x-model="form.subtotal">
                    <input type="hidden" id="discount_amount" name="discount_amount" x-model="form.discount_amount">
                    <input type="hidden" id="tax_amount" name="tax_amount" x-model="form.tax_amount">
                    <input type="hidden" id="total" name="total" x-model="form.total">
                </div>
            </div>
        </form>
    </div>

    <script>
        function invoiceForm() {
            return {
                isSubmitting: false,
                form: {
                    invoice_no: 'INV' + new Date().getFullYear() + '-' + String(Math.floor(Math.random() * 1000)).padStart(4, '0'),
                    invoice_date: "{{ old('invoice_date', date('Y-m-d')) }}",
                    due_date: "{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}",
                    discount_percent: {{ old('discount_percent', 0) }},
                    tax_percent: {{ old('tax_percent', 0) }},
                    from_name: "{{ old('from_name') }}",
                    from_address: "{{ old('from_address') }}",
                    to_name: "{{ old('to_name') }}",
                    to_address: "{{ old('to_address') }}",
                    items: [
                        {
                            quantity: 1,
                            description: '',
                            price: 0,
                            amount: 0
                        }
                    ],
                    subtotal: 0,
                    discount_amount: 0,
                    tax_amount: 0,
                    total: 0
                },

                addItem() {
                    this.form.items.push({
                        quantity: 1,
                        description: '',
                        price: 0,
                        amount: 0
                    });
                    this.calculateTotals();
                },

                removeItem(index) {
                    if (this.form.items.length > 1) {
                        this.form.items.splice(index, 1);
                        this.calculateTotals();
                    }
                },

                calculateItemAmount(index) {
                    const item = this.form.items[index];
                    item.amount = (item.quantity || 0) * (item.price || 0);
                    this.calculateTotals();
                },

                calculateTotals() {
                    this.form.subtotal = this.form.items.reduce((sum, item) => sum + (item.amount || 0), 0);
                    this.form.discount_amount = this.form.subtotal * (this.form.discount_percent / 100 || 0);
                    const taxableAmount = this.form.subtotal - this.form.discount_amount;
                    this.form.tax_amount = taxableAmount * (this.form.tax_percent / 100 || 0);
                    this.form.total = this.form.subtotal - this.form.discount_amount + this.form.tax_amount;
                },

                validateAndSubmit() {
                    if (this.form.items.length === 0) {
                        alert('Please add at least one item to the invoice.');
                        return;
                    }

                    const requiredFields = [
                        this.form.invoice_date,
                        this.form.due_date,
                        this.form.from_name,
                        this.form.from_address,
                        this.form.to_name,
                        this.form.to_address,
                        ...this.form.items.map(item => item.description),
                        ...this.form.items.map(item => item.quantity),
                        ...this.form.items.map(item => item.price)
                    ];

                    if (requiredFields.some(field => !field || (typeof field === 'string' && !field.trim()))) {
                        alert('Please fill in all required fields.');
                        return;
                    }

                    this.isSubmitting = true;
                    document.getElementById('invoiceForm').submit();
                }
            }
        }
    </script>
</div>
@endsection
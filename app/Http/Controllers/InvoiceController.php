<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('items')->latest()->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'from_name' => 'required|string|max:255',
            'from_address' => 'required|string',
            'to_name' => 'required|string|max:255',
            'to_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'from_name' => $request->from_name,
            'from_address' => $request->from_address,
            'to_name' => $request->to_name,
            'to_address' => $request->to_address,
            'subtotal' => $request->subtotal,
            'discount_percent' => $request->discount_percent ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'tax_percent' => $request->tax_percent ?? 0,
            'tax_amount' => $request->tax_amount ?? 0,
            'total' => $request->total,
        ]);

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'amount' => $item['quantity'] * $item['price'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load('items');
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items');
        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date',
            'from_name' => 'required|string|max:255',
            'from_address' => 'required|string',
            'to_name' => 'required|string|max:255',
            'to_address' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'from_name' => $request->from_name,
            'from_address' => $request->from_address,
            'to_name' => $request->to_name,
            'to_address' => $request->to_address,
            'subtotal' => $request->subtotal,
            'discount_percent' => $request->discount_percent ?? 0,
            'discount_amount' => $request->discount_amount ?? 0,
            'tax_percent' => $request->tax_percent ?? 0,
            'tax_amount' => $request->tax_amount ?? 0,
            'total' => $request->total,
        ]);

        // Delete existing items and recreate
        $invoice->items()->delete();

        foreach ($request->items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'amount' => $item['quantity'] * $item['price'],
            ]);
        }

        return redirect()->route('invoices.show', $invoice)->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
    }
}
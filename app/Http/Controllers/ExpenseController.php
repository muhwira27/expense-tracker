<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExpenseController extends Controller
{
    // Menampilkan semua pengeluaran
    public function index()
    {
        $expenses = Expense::orderBy('date', 'desc')->get();
        return Inertia::render('Expenses/Index', ['expenses' => $expenses]);
    }

    // Menampilkan form untuk membuat pengeluaran baru
    public function create()
    {
        return Inertia::render('Expenses/Create');
    }

    // Menyimpan pengeluaran baru
    public function store(Request $request)
    {
        Expense::create($request->all());
        return redirect()->route('expenses.index');
    }

    // Menampilkan detail pengeluaran
    public function show(Expense $expense)
    {
        return Inertia::render('Expenses/Show', ['expense' => $expense]);
    }

    // Menampilkan form untuk mengedit pengeluaran
    public function edit(Expense $expense)
    {
        return Inertia::render('Expenses/Edit', ['expense' => $expense]);
    }

    // Memperbarui pengeluaran
    public function update(Request $request, Expense $expense)
    {
        $expense->update($request->all());
        return redirect()->route('expenses.index');
    }

    // Menghapus pengeluaran
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect()->route('expenses.index');
    }
}

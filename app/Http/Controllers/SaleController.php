<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Requests\SaleStoreRequest;
use App\Models\Installment;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function create(): View
    {
        return view('sale.create');
    }

    public function edit(string $id): View
    {
        return view('sale.edit',[
            'sale' => Sale::find($id)
        ]);
    }

    public function store(SaleStoreRequest $request)
    {
        $validated = $request->validated();

        $totalPrice = array_reduce($validated['item'], function ($carry, $item) {
            return $carry += $item['price'];
        }, 0);

        $sale = new Sale;
        $sale->user_id = $request->user()->id;
        $sale->client = $validated['client'];
        $sale->payment_method = $validated['payment_method'];
        $sale->total_price = $totalPrice;

        $sale->save();

        foreach ($validated['item'] as $item) {
            $sale->items()->create([
                'sale_id' => $sale->id,
                'product' => $item['product'],
                'price' => $item['price'],
            ]);
        }

        foreach ($validated['installment'] as $installment) {
            $sale->installments()->create([
                'sale_id' => $sale->id,
                'due_date' => $installment['date'],
                'value' => $installment['value'],
                'observations' => $installment['observations']
            ]);
        }

        return redirect('/dashboard');
    }

    public function update(string $id, SaleStoreRequest $request)
    {
        $validated = $request->validated();

        $totalPrice = array_reduce($validated['item'], function ($carry, $item) {
            return $carry += $item['price'];
        }, 0);

        $sale = Sale::find($id);
        $sale->client = $validated['client'];
        $sale->payment_method = $validated['payment_method'];
        $sale->total_price = $totalPrice;
        $sale->items()->delete();
        $sale->installments()->delete();

        $sale->save();

        foreach ($validated['item'] as $item) {
            $sale->items()->create([
                'sale_id' => $sale->id,
                'product' => $item['product'],
                'price' => $item['price'],
            ]);
        }

        foreach ($validated['installment'] as $installment) {
            $sale->installments()->create([
                'sale_id' => $sale->id,
                'due_date' => $installment['date'],
                'value' => $installment['value'],
                'observations' => $installment['observations']
            ]);
        }

        return redirect('/dashboard');
    }

    public function destroy(string $id)
    {
        Sale::find($id)->delete();
        return redirect('/dashboard');
    }
}

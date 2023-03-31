<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Nova venda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900">
                            {{ __('Informações de venda') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600">
                            {{ __("Salve uma nova venda") }}
                        </p>
                    </header>

                    @if ($errors->any())
                        <div class="alert alert-danger text-sm text-red-600 space-y-1">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="post" action="{{ route('sale.update', $sale->id) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('PATCH')

                            <div>
                                <x-input-label for="client" :value="__('Cliente (opcional)')" />
                                <x-text-input id="client" name="client" type="text" class="mt-1 block w-full" autofocus autocomplete="client" :value="old('client', $sale->client)" />
                                <x-input-error class="mt-2" :messages="$errors->get('client')" />
                            </div>

                            <div class='h-px w-full bg-gray-200'></div>

                            <x-primary-button id='add-item-button' type="button" class='mt-2'>
                                Adicionar produto
                            </x-primary-button>
                            <table class='w-full table-auto'>
                                <thead>
                                    <tr>
                                        <th class='text-left font-medium text-sm text-gray-700'>Produto</th>
                                        <th class='text-left font-medium text-sm text-gray-700'>Preço</th>
                                    </tr>
                                </thead>
                                <tbody id='item-table'>
                                    <template id='item-template'>
                                        <tr>
                                            <td>
                                                <x-text-input product name="item[*][product]" type="text" class="mt-1 block w-full" autocomplete="product" />
                                            </td>
                                            <td>
                                                <x-text-input price name="item[n][price]" type="number" min="0" step="0.01" class="mt-1 block w-full" autocomplete='price' />
                                            </td>
                                            <td valign='bottom'>
                                                <x-danger-button remove data-index='n' type='button' class='h-11'>Remover</x-danger-button>
                                            </td>
                                        </tr>
                                    </template>

                                        @foreach ($sale->items as $key => $item)
                                            <tr>
                                                <td>
                                                    <x-text-input product name="item[{{ $key }}][product]" type="text" class="mt-1 block w-full" autocomplete="product" :value="old('item['.$key.'][product]', $item->product)" />
                                                </td>
                                                <td>
                                                    <x-text-input price name="item[{{ $key }}][price]" type="number" min="0" step="0.01" class="mt-1 block w-full" autocomplete='price' :value="old('item['.$key.'][price]', $item->price)"/>
                                                </td>
                                                <td valign='bottom'>
                                                    <x-danger-button remove data-index='{{ $key }}' type='button' class='h-11'>Remover</x-danger-button>
                                                </td>
                                            </tr>
                                        @endforeach

                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td class='font-bold'>Total:</td>
                                        <td data-total='0.00' id='total-price' class='font-bold'>R$ 0.00</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class='h-px w-full bg-gray-200'></div>

                            <div>
                                <x-input-label for="payment_method" :value="__('Método de pagamento')" />
                                <x-select-input id="payment_method" name="payment_method" class="mt-1 block w-full" :value="old('payment_method', $sale->payment_method)">
                                    <option value='credit-card'>Cartão de Crédito</option>
                                    <option value='debit-card'>Cartão de Débito</option>
                                    <option value='boleto'>Boleto</option>
                                    <option value='pix'>Pix</option>
                                </x-select-input>
                                <x-input-error class="mt-2" :messages="$errors->get('payment_method')" />
                            </div>

                            <div class='flex items-end gap-2'>
                                <div class='flex-grow'>
                                    <x-input-label for="installments_quantity" :value="__('Quantidade de parcelas (máximo 24)')" />
                                    <x-text-input id="installments_quantity" name="installments_quantity" type="number" min="1" max="24" step="1" class="mt-1 block w-full" :placeholder='1' :value="old('installments_quantity', $sale->installments()->count())"/>
                                </div>
                                <div>
                                    <x-primary-button id='generate-installments-button' type='button' class='h-11'>Gerar parcelas</x-secondary-button>
                                </div>
                            </div>


                            <ul id='installments-list'>
                                <template id='installment-template'>
                                    <li class='flex gap-2 mb-2'>
                                        <div>
                                            <x-input-label :value="__('Data')" />
                                            <x-text-input date name="installment[*][date]" type="date" class="mt-1 block w-full" />
                                        </div>
                                        <div>
                                            <x-input-label :value="__('Valor')" />
                                            <x-text-input amount name="installment[*][value]" type="number" min="0" step="0.01" class="mt-1 block w-full" />
                                        </div>
                                        <div>
                                            <x-input-label :value="__('Observações')" />
                                            <x-text-input observations name="installment[*][observations]" type="text" class="mt-1 block w-full" />
                                        </div>
                                    </li>
                                </template>

                                @foreach ($sale->installments as $key => $installment)
                                    <li class='flex gap-2 mb-2'>
                                        <div>
                                            <x-input-label :value="__('Data')" />
                                            <x-text-input date name="installment[{{ $key }}][date]" type="date" class="mt-1 block w-full" :value="old('installment['.$key.'][date]', $installment->due_date)"/>
                                        </div>
                                        <div>
                                            <x-input-label :value="__('Valor')" />
                                            <x-text-input amount name="installment[{{ $key }}][value]" type="number" min="0" step="0.01" class="mt-1 block w-full" :value="old('installment['.$key.'][value]', $installment->value)"/>
                                        </div>
                                        <div>
                                            <x-input-label :value="__('Observações')" />
                                            <x-text-input observations name="installment[{{ $key }}][observations]" type="text" class="mt-1 block w-full" :value="old('installment['.$key.'][observations]', $installment->observations)"/>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <ul id='installment-total-error' class='text-sm text-red-600 space-y-1 hidden'>
                                <li>{{ __('Os valores não fecham') }}</li>
                            </ul>

                            <div class='h-px w-full bg-gray-200'></div>

                            <x-primary-button>{{ __('Salvar') }}</x-primary-button>
                    </form>

                    <form method="post" action="{{ route('sale.destroy', $sale->id) }}" class="mt-6 space-y-6">
                        @csrf
                        @method('DELETE')
                    <x-danger-button>{{ __('Deletar') }}</x-danger-button>

                </div>
            </div>
        </div>
    </div>

    @vite(['resources/js/sale.js'])
</x-app-layout>

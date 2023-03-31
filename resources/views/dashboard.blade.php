<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Sales') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <x-primary-link-button href='sale'>
                        {{ __('Adicionar venda') }}
                    </x-primary-link-button>

                    <table class='w-full mt-8'>
                        <thead>
                            <tr>
                                <th class='text-start'>Funcionário</th>
                                <th class='text-start'>Cliente</th>
                                <th class='text-start'>Valor</th>
                                <th class='text-start'>Quantidade de parcelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sales as $sale)
                                <tr>
                                    <td>{{ $sale->user->name }}</td>
                                    <td>{{ $sale->client }}</td>
                                    <td>{{ $sale->total_price }}</td>
                                    <td>{{ $sale->installments()->count() }}</td>
                                    <td>
                                        <x-primary-link-button href="{{ route('sale.edit', $sale->id) }}">Editar</x-primary-link-button>
                                    </td>
                                    <td>
                                        <form method="post" action="{{ route('sale.destroy', $sale->id) }}">
                                            @csrf
                                            @method('DELETE')

                                            <x-danger-button>Deletar</x-danger-button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

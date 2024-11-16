<div>
    <form>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Produktnummer</th>
                    <th>Navn</th>
                    <th>Beskrivelse</th>
                    <th>Pris</th>
                    <th>Margin</th>
                    <th>Periode</th>
                    <th>Timebank</th>
                    <th>Antall</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($alacarteItems as $item)
                    <tr>
                        <td>{{ $item->product_number }}</td>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->price }}</td>
                        <td>{{ $item->margine ?? 'N/A' }}</td>
                        <td>{{ $item->pr ?? 'N/A' }}</td>
                        <td>{{ $item->timebank ?? 'N/A' }}</td>
                        <td>
                            <input type="number"
                                   class="form-control"
                                   wire:input.lazy="updateQuantity({{ $item->id }}, $event.target.value)"
                                   value="{{ $quantities[$item->id] ?? 0 }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </form>
</div>

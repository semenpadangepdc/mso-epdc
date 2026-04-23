@can('manage material')
    <select name="material_master_id[]" class="form-select">
        @foreach($materialMasters as $m)
            <option value="{{ $m->id }}">{{ $m->name }}</option>
        @endforeach
    </select>
@endcan

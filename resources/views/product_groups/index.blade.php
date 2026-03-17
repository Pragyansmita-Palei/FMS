@extends('layouts.app')

@section('content')

<div class="container-fluid">

    <div class="card border-0 shadow-sm rounded-4">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center px-4 py-3">
            <h5 class="mb-0 fw-semibold">Product Groups</h5>

            <a href="{{ route('product-groups.create') }}" class="btn btn-green">
                + Add Group
            </a>
        </div>

        {{-- <form action="{{ route('product-groups.import') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" class="form-control form-control-sm" required>
    <button class="btn btn-success btn-sm mt-2">Import</button>
</form> --}}

{{-- <a href="{{ route('product-groups.export') }}" class="btn btn-outline-primary btn-sm">
    Export Excel
</a> --}}
 {{-- <a href="{{ route('product-groups.sample') }}"
       class="btn btn-outline-secondary btn-sm">
        Download Sample
    </a> --}}

        <div class="border-top"></div>

        {{-- FULL BORDER BOX (Product Groups → table → pagination) --}}
        <div class="px-4 py-3">

            <div class="border rounded-3 overflow-hidden">

                {{-- Search row --}}
                <div class="px-3 py-2">
                    <form method="GET" action="{{ route('product-groups.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       class="form-control form-control-sm"
                                       placeholder="Search group name">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="border-top"></div>

                {{-- Table --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">#</th>
                                <th>Group Name</th>
                                <th>Main Products</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($groups as $g)
                                <tr>
                                    <td class="ps-3">{{ $loop->iteration }}</td>
                                    <td class="fw-semibold">{{ $g->name }}</td>

                                    {{-- MAIN PRODUCTS --}}
                                    <td>
                                        @if($g->main_product)
                                            {{ $g->main_product }}
                                        @else
                                            <span class="text-muted">No main product</span>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td style="color: {{ $g->status == 1 ? 'green' : 'red' }}; font-weight: 600;">
                                        {{ $g->status == 1 ? 'Available' : 'Out of Stock' }}
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="text-end pe-3">
                                        <a href="{{ route('product-groups.edit', $g) }}"
                                           class="text-decoration-none me-2">✏️</a>

                                        <form method="POST"
                                              action="{{ route('product-groups.destroy', $g) }}"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="btn p-0 border-0 bg-transparent text-danger"
                                                    onclick="return confirm('Delete this group?')">
                                                🗑️
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        No product group found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination inside same full border --}}
                <div class="border-top d-flex justify-content-end px-3 py-2">
                    {{ $groups->links('pagination::bootstrap-5') }}
                </div>

            </div>

        </div>

    </div>

</div>

@endsection

<tr>
    <td>
        <div style="padding-left: {{ $level * 30 }}px;">
            @if($level > 0)
                <i class="bi bi-arrow-return-right text-muted me-2"></i>
            @endif
            <span class="fw-semibold">{{ $category->name }}</span>
        </div>
    </td>
    <td><code>{{ $category->slug }}</code></td>
    <td>
        @if($category->parent)
            <span class="badge bg-secondary">{{ $category->parent->name }}</span>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
    <td>
        <span class="badge bg-{{ $category->products_count > 0 ? 'primary' : 'secondary' }}">
            {{ $category->products_count }} products
        </span>
    </td>
    <td>
        <div class="btn-group btn-group-sm" role="group">
            <a href="{{ route('admin.categories.edit', $category->id) }}" 
               class="btn btn-outline-primary" 
               title="Edit">
                <i class="bi bi-pencil"></i>
            </a>
            <button type="button" 
                    class="btn btn-outline-danger delete-btn" 
                    data-id="{{ $category->id }}"
                    data-name="{{ $category->name }}"
                    title="Delete">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </td>
</tr>

@if(isset($category->children_tree))
    @foreach($category->children_tree as $child)
        @include('admin.categories.partials.category-row', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif

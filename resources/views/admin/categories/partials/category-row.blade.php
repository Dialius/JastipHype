<tr class="transition hover:bg-gray-50 dark:hover:bg-gray-800/50">
    <td class="whitespace-nowrap px-5 py-4 md:px-6">
        <div style="padding-left: {{ $level * 24 }}px;" class="flex items-center gap-2">
            @if($level > 0)
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                </svg>
            @endif
            <span class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $category->name }}</span>
        </div>
    </td>
    <td class="whitespace-nowrap px-5 py-4 md:px-6">
        <code class="rounded bg-gray-100 px-2 py-1 text-sm text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $category->slug }}</code>
    </td>
    <td class="whitespace-nowrap px-5 py-4 md:px-6">
        @if($category->parent)
            <span class="inline-flex rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                {{ $category->parent->name }}
            </span>
        @else
            <span class="text-sm text-gray-500 dark:text-gray-400">-</span>
        @endif
    </td>
    <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
        <span class="inline-flex rounded-full {{ $category->products_count > 0 ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400' : 'bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400' }} px-2.5 py-0.5 text-xs font-medium">
            {{ $category->products_count }} products
        </span>
    </td>
    <td class="whitespace-nowrap px-5 py-4 text-center md:px-6">
        <div class="flex items-center justify-center gap-2">
            <a href="{{ route('admin.categories.edit', $category->id) }}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-blue-50 hover:text-blue-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-blue-500/10 dark:hover:text-blue-400" title="Edit">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                </svg>
            </a>
            <button type="button" class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-200 text-gray-500 transition hover:bg-red-50 hover:text-red-600 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-red-500/10 dark:hover:text-red-400 delete-btn" data-id="{{ $category->id }}" data-name="{{ $category->name }}" title="Delete">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                </svg>
            </button>
        </div>
    </td>
</tr>

@if(isset($category->children_tree))
    @foreach($category->children_tree as $child)
        @include('admin.categories.partials.category-row', ['category' => $child, 'level' => $level + 1])
    @endforeach
@endif

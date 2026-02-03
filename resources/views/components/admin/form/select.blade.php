{{--
    Admin Form Select Component
    
    Usage:
    <x-admin.form.select 
        name="category_id" 
        label="Category"
        :options="$categories"
        optionValue="id"
        optionLabel="name"
        placeholder="Select a category"
        :error="$errors->first('category_id')"
        required
    />
--}}

@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'placeholder' => 'Select an option',
    'options' => [],
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'error' => null,
    'help' => null,
    'required' => false,
    'disabled' => false,
    'multiple' => false,
    'searchable' => false,
])

@php
    $hasError = !empty($error);
    $selectedValue = old($name, $value);
    
    $selectClasses = 'w-full appearance-none rounded-lg border bg-transparent py-3 pl-4 pr-10 font-medium outline-none transition 
                      focus:border-admin-primary focus:ring-1 focus:ring-admin-primary 
                      disabled:cursor-not-allowed disabled:opacity-50
                      dark:border-form-strokedark dark:bg-boxdark dark:focus:border-admin-primary dark:text-white';
    
    $selectClasses .= $hasError 
        ? ' border-meta-1 text-meta-1 focus:border-meta-1 focus:ring-meta-1' 
        : ' border-gray-300 text-gray-900 dark:text-white';
@endphp

<div {{ $attributes->merge(['class' => 'mb-4.5']) }}>
    @if($label)
        <label for="{{ $name }}" class="mb-2.5 block text-sm font-medium text-gray-900 dark:text-white">
            {{ $label }}
            @if($required)
                <span class="text-meta-1">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        <select 
            name="{{ $name }}"
            id="{{ $name }}"
            @if($required) required @endif
            @if($disabled) disabled @endif
            @if($multiple) multiple @endif
            class="{{ $selectClasses }}"
        >
            @if($placeholder && !$multiple)
                <option value="" disabled {{ empty($selectedValue) ? 'selected' : '' }}>
                    {{ $placeholder }}
                </option>
            @endif
            
            @foreach($options as $option)
                @php
                    $optVal = is_array($option) || is_object($option) 
                        ? (is_object($option) ? $option->{$optionValue} : $option[$optionValue])
                        : $option;
                    $optLbl = is_array($option) || is_object($option) 
                        ? (is_object($option) ? $option->{$optionLabel} : $option[$optionLabel])
                        : $option;
                    $isSelected = $multiple 
                        ? (is_array($selectedValue) && in_array($optVal, $selectedValue))
                        : ($selectedValue == $optVal);
                @endphp
                <option value="{{ $optVal }}" {{ $isSelected ? 'selected' : '' }}>
                    {{ $optLbl }}
                </option>
            @endforeach
        </select>
        
        <!-- Dropdown Arrow -->
        <span class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-gray-400">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </span>
    </div>
    
    @if($help && !$hasError)
        <p class="mt-1.5 text-xs text-gray-500 dark:text-bodydark2">{{ $help }}</p>
    @endif
    
    @if($hasError)
        <p class="mt-1.5 text-xs text-meta-1">{{ $error }}</p>
    @endif
</div>

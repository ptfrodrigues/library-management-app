@props(['id', 'title', 'action', 'fields'])

<div id="{{ $id }}" class="fixed inset-0 z-50 @if($errors->any()) flex @else hidden @endif bg-black bg-opacity-50 flex items-center justify-center">
    <div class="bg-background rounded-lg shadow-xl p-8 max-w-3xl w-full max-h-[80vh] overflow-y-auto border border-secondary">
        <h2 class="text-3xl font-display font-bold mb-6 text-primary">{{ $title }}</h2>
        <form action="{{ $action }}" method="POST" class="space-y-6">
            @csrf
            @foreach($fields as $field)
                <div class="space-y-2">
                    <label for="{{ $field['name'] }}" class="block text-sm font-medium text-text">{{ $field['label'] }}</label>
                    @if($field['type'] === 'select')
                        <select id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="form-select w-full rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error($field['name']) border-tertiary @enderror" required>
                            @foreach($field['options'] as $value => $label)
                                @if($label)
                                    <option value="{{ $value }}" {{ old($field['name']) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endif
                            @endforeach
                        </select>
                    @elseif($field['type'] === 'textarea')
                        <textarea id="{{ $field['name'] }}" name="{{ $field['name'] }}" rows="3" class="form-textarea w-full rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error($field['name']) border-tertiary @enderror" required>{{ old($field['name']) }}</textarea>
                    @else
                        <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" value="{{ old($field['name']) }}" 
                               class="form-input w-full rounded-md border-secondary bg-background text-text shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 @error($field['name']) border-tertiary @enderror" 
                               @if($field['name'] === 'password') minlength="8" @endif
                               @if(isset($field['min'])) min="{{ $field['min'] }}" @endif 
                               @if(isset($field['max'])) max="{{ $field['max'] }}" @endif
                               required>
                    @endif
                    @error($field['name'])
                        <p class="text-sm text-tertiary">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" onclick="closeCreateModal('{{ $id }}')" class="px-6 py-3 bg-accent text-white rounded-lg hover:bg-accent-dark transition-colors duration-300">
                    Close
                </button>
                <button type="submit" class="px-6 py-3 bg-primary text-white rounded-lg hover:bg-secondary transition-colors duration-300">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
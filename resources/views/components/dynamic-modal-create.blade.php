@props(['id', 'title', 'action', 'fields'])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
   <div class="bg-white rounded-lg shadow-lg p-6 max-w-3xl w-full max-h-[80vh] overflow-y-auto">
       <h2 class="text-lg font-semibold text-gray-800 mb-4">{{ $title }}</h2>
       <form action="{{ $action }}" method="POST">
           @csrf
           @foreach($fields as $field)
               <div class="mb-4">
                   <label for="{{ $field['name'] }}" class="block text-sm font-medium text-gray-700">{{ $field['label'] }}</label>
                   @if($field['type'] === 'select')
                       <select id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                           @foreach($field['options'] as $value => $label)
                               @if($label)
                                   <option value="{{ $value }}">{{ $label }}</option>
                               @endif
                           @endforeach
                       </select>
                   @else
                       <input type="{{ $field['type'] }}" id="{{ $field['name'] }}" name="{{ $field['name'] }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" @if(isset($field['min'])) min="{{ $field['min'] }}" @endif @if(isset($field['max'])) max="{{ $field['max'] }}" @endif>
                   @endif
               </div>
           @endforeach
           <div class="mt-4 text-right">
               <button type="button" onclick="closeModal('{{ $id }}')" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 mr-2">
                   Close
               </button>
               <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                   Create
               </button>
           </div>
       </form>
   </div>
</div>


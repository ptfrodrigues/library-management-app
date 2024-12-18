<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">{{ $title }}</h1>
                    
                    @if(count($books) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="py-2 px-4 border-b">Title</th>
                                        <th class="py-2 px-4 border-b">Genre</th>
                                        <th class="py-2 px-4 border-b">Language</th>
                                        <th class="py-2 px-4 border-b">ISBN</th>
                                        <th class="py-2 px-4 border-b">Publication Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($books as $book)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $book->title }}</td>
                                            <td class="py-2 px-4 border-b">{{ $book->genre }}</td>
                                            <td class="py-2 px-4 border-b">{{ $book->language }}</td>
                                            <td class="py-2 px-4 border-b">{{ $book->isbn }}</td>
                                            <td class="py-2 px-4 border-b">{{ $book->publication_year }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            {{ $books->links() }}
                        </div>
                    @else
                        <p>No books available at the moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


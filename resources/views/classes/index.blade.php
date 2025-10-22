<x-app-layout>
<h1 class="text-2xl font-bold mb-4">Your Classes</h1>
<ul>
@foreach($classes as $class)
<li class="mb-2">
    <a href="{{ route('classes.show',$class->id) }}" class="text-blue-600 hover:underline">{{ $class->name }}</a>
</li>
@endforeach
</ul>
</x-app-layout>

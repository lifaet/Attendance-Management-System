<aside class="w-64 bg-white border-r hidden md:block">
    <div class="h-full flex flex-col">
        <div class="p-4 border-b">
            <a href="{{ route('dashboard') }}" class="text-lg font-bold text-indigo-600">Everi State</a>
        </div>

        <nav class="p-4 flex-1 overflow-y-auto">
            <ul class="space-y-2">
                <li>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 text-sm font-medium rounded hover:bg-indigo-50 {{ request()->routeIs('dashboard') ? 'bg-indigo-50' : '' }}">
                        <span class="ml-2">Dashboard</span>
                    </a>
                </li>

                @if(auth()->user()->role === 'admin')
                <li>
                    <a href="{{ route('users.index') }}" class="flex items-center p-2 text-sm font-medium rounded hover:bg-indigo-50 {{ request()->routeIs('users.*') ? 'bg-indigo-50' : '' }}">
                        <span class="ml-2">Users</span>
                    </a>
                </li>
                @endif

                <li>
                    <a href="{{ route('classes.index') }}" class="flex items-center p-2 text-sm font-medium rounded hover:bg-indigo-50 {{ request()->routeIs('classes.*') ? 'bg-indigo-50' : '' }}">
                        <span class="ml-2">Classes</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('classes.index') }}" class="flex items-center p-2 text-sm font-medium rounded hover:bg-indigo-50">
                        <span class="ml-2">Take Attendance</span>
                    </a>
                </li>

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left p-2 text-sm font-medium rounded hover:bg-red-50 text-red-600">Logout</button>
                    </form>
                </li>
            </ul>
        </nav>

        <div class="p-4 border-t text-xs text-gray-500">
            Logged in as <strong>{{ auth()->user()->name }}</strong>
        </div>
    </div>
</aside>
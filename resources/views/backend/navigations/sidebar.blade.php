<ul class="space-y-2">
    @foreach ($navigations as $nav)
        <li>
            <div class="font-bold text-gray-700 flex items-center space-x-2">
                <span>{{ $nav->title }}</span>
                @if($nav->nav_icon)
                    <i class="{{ $nav->nav_icon }}"></i>
                @endif
            </div>

            {{-- @if ($nav->children->count()) --}}
                <ul class="ml-4 mt-1 text-sm text-gray-600 space-y-1">
                    @foreach ($nav->children as $child)
                        <li>
                            <a 
                                href="{{ url($child->url) }}"
                                class="{{ request()->routeIs($child->route) ? 'text-blue-600 font-semibold' : '' }}">
                                {{ $child->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            {{-- @endif --}}
        </li>
    @endforeach
</ul>

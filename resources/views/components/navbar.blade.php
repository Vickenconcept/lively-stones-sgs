<nav class="fixed top-0 z-40 w-full bg-slate-900 border-b border-slate-300 ">
    <div class="px-3 py-3 lg:px-5 lg:pl-3">
        <div class="flex items-center justify-between">
            <div class="flex items-center justify-start rtl:justify-end">
                <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar"
                    type="button"
                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 ">
                    <span class="sr-only">Open sidebar</span>
                    <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd"
                            d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                        </path>
                    </svg>
                </button>
                <a href="https://flowbite.com" class="flex ms-2 md:me-24 hidden md:flex">
                    <img src="{{ asset('images/logo.svg') }}" class="h-8 me-3" alt="FluenceGrid Logo" />
                </a>
                <div>
                    <h2 class="text-sm  md:text-2xl font-medium ml-5 capitalize">
                        @yield('title')
                    </h2>
                </div>
            </div>
            <div class="flex items-center space-x-5">
               

                {{-- <div class="flex items-center ms-3">
                    <div class="flex items-center space-x-2"  aria-expanded="false" data-dropdown-toggle="dropdown-user" id="profile_avatar">
                        <button type="button" id="profile_avatar"
                            class="flex text-sm bg-gray-200 rounded-md focus:ring-4 focus:ring-gray-300 ">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-10 h-10 rounded-md"
                                src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">
                        </button>
                        <div class="bg-white rounded-md px-3 py-1 w-48 truncate cursor-pointer hidden md:block" >
                            <p class="text-sm font-semibold capitalize"> {{ auth()->user()->name }}</p>
                            <p class="text-xs">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow "
                        id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                            <p class="text-sm text-gray-900 " role="none">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-sm font-medium text-gray-900 truncate " role="none">
                                {{ auth()->user()->email }}
                            </p>
                        </div>
                        <ul class="py-1" role="none">
                            <li>
                                <a href="{{ route('home') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100  "
                                    role="menuitem">Dashboard</a>
                            </li>
                            <li>
                                <a href="{{ route('profile') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 ">
                                    <span class="text-sm ">Profile</span>
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('auth.logout') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100  "
                                    role="menuitem">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
</nav>

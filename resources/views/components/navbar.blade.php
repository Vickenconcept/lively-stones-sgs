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
                    <h2 class="text-sm  md:text-2xl font-medium ml-5 capitalize text-gray-50">
                        @yield('title')
                    </h2>
                </div>
            </div>
            <div class="flex items-center space-x-5">


                <div class="flex items-center ms-3">
                    <div class="flex items-center space-x-2"  aria-expanded="false" data-dropdown-toggle="dropdown-user" id="profile_avatar">
                        <button type="button" id="profile_avatar"
                            class="">
                            <span class="sr-only">Open user menu</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-8 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                              </svg>
                              
                        </button>
                        <div class=" rounded-md px-3 py-1 w-48 truncate cursor-pointer hidden md:block text-white" >
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
                            @role('super-admin')
                            <li>
                                <a href="{{ route('home') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100  "
                                    role="menuitem">Dashboard</a>
                            </li>
                            @endrole
                            {{-- <li>
                                <a href="{{ route('profile') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 ">
                                    <span class="text-sm ">Profile</span>
                                </a>
                            </li> --}}

                            <li>
                                <a href="{{ route('auth.logout') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100  "
                                    role="menuitem">Sign out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

<aside id="logo-sidebar"
    class="fixed top-0 left-0 z-40 w-64 h-screen  transition-transform -translate-x-full bg-slate-900  sm:translate-x-0  p-3 "
    aria-label="Sidebar">
    <div class="bg-slate-50 h-full rounded-xl px-3 pb-4 ">
        <div class="py-5 mb-2 border-b-2 border-slate-300 flex items-center justify-between">
            <a href="/home" class="flex items-center">
                <img src="{{ asset('images/logo.svg') }}" class="h-10 me-3" alt="Lively Stones Logo" />
            </a>
            <button type="button" data-drawer-hide="logo-sidebar" aria-controls="logo-sidebar"
                class="sm:hidden inline-flex items-center p-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200">
                <span class="sr-only">Close sidebar</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="h-[85%] pb-4 overflow-y-auto ">
            <ul class="space-y-1 font-medium">
                @role('super-admin')
                    <li>
                        <a href="{{ route('home') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('home') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                            <i class='bx bx-home-smile text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            <span class="">Dashboard</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('session_years.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('session_years.index') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                            <i class='bx bxs-time-five text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            <span class="">Session years</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('terms.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('terms.index') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                            <i class='bx bxs-school text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            <span class="">Terms</span>
                        </a>
                    </li>
                @endrole

                <li>
                    <a href="{{ route('classrooms.index') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('classrooms.index') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                        <i class='bx bxs-graduation text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                        <span class="">Classrooms</span>
                    </a>
                </li>

                @role(['super-admin', 'admin'])
                    <li>
                        <a href="{{ route('batches.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('batches.index') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                            <i class='bx bxs-group text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            <span class="">Batches</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('subjects.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('subjects.index') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                            <i class='bx bxs-book text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            <span class="">Subjects</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('class_subject_terms.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('class_subject_terms.index') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                            <i class='bx bxs-book-alt text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            <span class="">Class subject</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('students.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('students.index') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                            <i class='bx bxs-group text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            <span class="">Students</span>
                        </a>
                    </li>
                @endrole


                <hr class="border border-slate-300">
                @role('super-admin')
                    <li>
                        <a href="{{ route('scratch-codes.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('scratch-codes.index') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                            <i class='bx bx-barcode text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            <span class="">Scratch codes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('users.index') }}"
                            class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out {{ request()->routeIs('users.index') ? 'bg-gradient-to-r from-slate-900 from-70%  to-slate-400 font-medium text-white hover:bg-gradient-to-br from-slate-900 from-70%  to-slate-400' : '' }}">
                            <i class='bx bxs-group text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                            <span class="">Staff</span>
                        </a>
                    </li>
                @endrole


                <li class="">
                    <a href="{{ route('auth.logout') }}"
                        class="flex items-center p-2 text-gray-900 rounded-lg  hover:bg-slate-900/50 group transition duration-500 ease-in-out">
                        <i class='bx bx-exit text-xl mr-2 text-gray-700 bg-slate-200 px-1 py-0.5 rounded-md'></i>
                        <span class="text-sm capitalize">Log out</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>

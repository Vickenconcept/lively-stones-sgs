 <x-guest-layout>
     <div class="min-w-screen min-h-screen bg-slate-300  flex items-center justify-center px-1 md:px-5 py-5">
         <div class=" text-gray-500  w-full  " style="max-width:1000px">


             <div class="items-end w-[90%] mx-auto  bg-blue-950 rounded-3xl p-20 hidden lg:flex">

             </div>
             <div class="items-end w-[96%] mx-auto bg-slate-900 rounded-3xl p-20 hidden lg:flex lg:-mt-36">

             </div>

             <div class="md:flex items-center lg:items-end 1-full  bg-gray-50 rounded-3xl px-3 md:px-10 lg:-mt-36 shadow-xl">
                 <div class="w-full md:w-1/2 hidden md:flex">
                     <img src="{{ asset('images/login-image.png') }}" alt="">
                 </div>

                 <div class="w-full md:w-1/2">
                     <form action="{{ route('auth.login') }}" method="post"
                         class="w-full py-10 px-1 md:px-10  rounded-2xl ">

                         @csrf

                         <div class="text-center mb-10  ">
                             <div class="flex items-center justify-center mb-5 space-x-1">
                                 <img src="{{ asset('images/logo.svg') }}" alt="">
                             </div>
                             <h1 class="font-bold text-2xl text-gray-900">Sign in to your Account</h1>
                             <p class="text-gray-800">Please enter your details</p>
                         </div>

                         <x-session-msg />


                         <div>
                             <div class="flex -mx-3">
                                 <div class="w-full px-3 mb-5">
                                     <label for="" class="text-xs font-semibold px-1 text-gray-800">Email</label>
                                     <div class="flex">
                                         <div
                                             class="w-10 z-10 pl-1 text-center pointer-events-none flex items-center justify-center">
                                             <i class="mdi mdi-email-outline text-gray-400 text-lg"></i>
                                         </div>
                                         <input type="email"
                                             class="w-full -ml-10 pl-10 pr-3 py-2 rounded-lg border-2 border-gray-800 bg-slate-200 outline-none focus:border-slate-900 ring-0"
                                             placeholder="email@example.com" name="email"
                                             value="{{ old('email') }}">
                                     </div>
                                 </div>
                             </div>


                             <div class="flex -mx-3">
                                 <div class="w-full px-3 mb-5">
                                     <label for="" class="px-1 text-xs font-semibold text-gray-800">Password</label>
                                     <div class="">
                                         <div
                                             class="z-10 flex items-center justify-center w-10 pl-1 text-center pointer-events-none">
                                             <i class="text-lg text-gray-400 mdi mdi-lock-outline"></i>
                                         </div>
                                         <div class="relative">
                                             <input type="password"
                                                 class="block w-full py-2 pl-5 pr-10 text-sm text-gray-900 border-2  rounded-lg border-gray-800 bg-slate-200  outline-none focus:border-slate-900 ring-0 "
                                                 placeholder="Enter password" name="password" id="password" />


                                             <button type="button" onclick="showPassword()"
                                                 class="text-gray-500 hover:text-gray-700 absolute end-2.5 bottom-1  font-medium rounded-lg text-sm  py-.5">
                                                 <i class="bx bx-show-alt text-2xl" id="show"></i>
                                                 <i class="bx bx-hide text-2xl" id="hide"
                                                     style="display: none"></i>
                                             </button>
                                         </div>
                                     </div>
                                 </div>

                             </div>

                             <div class="flex -mx-3">
                                 <div class="w-full px-3 mb-5">
                                     <button
                                         class="block w-full  mx-auto bg-slate-900 hover:bg-slate-900 focus:bg-slate-900  text-white rounded-lg px-6 py-3 font-medium flex justify-between transition duration-500 ease-in-out">
                                         <span>
                                             <span id="hiddenText" class="hidden">
                                                 <i class='bx bx-loader-alt animate-spin'></i>
                                             </span>
                                             <span>Login</span>
                                         </span>
                                         <span>
                                             <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="size-6">
                                                 <path stroke-linecap="round" stroke-linejoin="round"
                                                     d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3" />
                                             </svg>

                                         </span>
                                     </button>
                                 </div>
                             </div>
                             <div class="text-right hover:text-slate-900 ring-0 font-semibold underline text-sm text-gray-800">
                                 <a href="{{ route('password.request') }}">Forgot Password</a>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>
         </div>
     </div>

     <script>
         function showPassword() {
             var passwordField = document.getElementById('password');
             var show = document.getElementById('show');
             var hide = document.getElementById('hide');
             if (passwordField.type === 'password') {
                 passwordField.type = 'text';
                 show.style.display = 'none'
                 hide.style.display = 'block'
             } else {
                 passwordField.type = 'password';
                 show.style.display = 'block'
                 hide.style.display = 'none'
             }
         }
     </script>
 </x-guest-layout>

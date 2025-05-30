@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto py-16">
    <h4 class="text-2xl font-semibold mb-8">Hey, Welcome Back</h4>
    <div id="stats" class="grid gird-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="bg-black/60 to-white/5 p-6 rounded-lg">
            <div class="flex flex-row space-x-4 items-center">
                <div id="stats-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                      </svg>
                </div>
                <div>
                    <p class="text-indigo-300 text-sm font-medium uppercase leading-4">Students</p>
                    <p class="text-white font-bold text-2xl inline-flex items-center space-x-2">
                        <span>{{ $studentsCount }}</span>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                              </svg>
                              
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-slate-900 p-6 rounded-lg">
            <div class="flex flex-row space-x-4 items-center">
                <div id="stats-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      
                </div>
                <div>
                    <p class="text-teal-300 text-sm font-medium uppercase leading-4">Subjects</p>
                    <p class="text-white font-bold text-2xl inline-flex items-center space-x-2">
                        <span>{{ $subjectsCount }}</span>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                              </svg>
                              
                        </span>
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-black/60 p-6 rounded-lg">
            <div class="flex flex-row space-x-4 items-center">
                <div id="stats-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                      </svg>
                      
                </div>
                <div>
                    <p class="text-blue-300 text-sm font-medium uppercase leading-4">Classes</p>
                    <p class="text-white font-bold text-2xl inline-flex items-center space-x-2">
                        <span>{{ $classesCount }}</span>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                              </svg>
                              
                        </span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div id="last-users">
        <h1 class="font-bold py-4 uppercase">Last 24h users</h1>
        <div class="overflow-x-scroll">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-slate-900 text-gray-50">
                    <th class="text-left py-3 px-2 rounded-l-lg">Name</th>
                    <th class="text-left py-3 px-2">Email</th>
                    <th class="text-left py-3 px-2">Group</th>
                    <th class="text-left py-3 px-2 rounded-r-lg">Status</th>
                </thead>
                <tr class="border-b border-gray-700">
                    <td class="py-3 px-2 font-bold">
                        <div class="inline-flex space-x-3 items-center">
                            <span><img class="rounded-full w-8 h-8" src="https://images.generated.photos/tGiLEDiAbS6NdHAXAjCfpKoW05x2nq70NGmxjxzT5aU/rs:fit:256:256/czM6Ly9pY29uczgu/Z3Bob3Rvcy1wcm9k/LnBob3Rvcy92M18w/OTM4ODM1LmpwZw.jpg" alt=""></span>
                            <span>Thai Mei</span>
                        </div>
                    </td>
                    <td class="py-3 px-2">thai.mei@abc.com</td>
                    <td class="py-3 px-2">User</td>
                    <td class="py-3 px-2">Approved</td>
                </tr>
                
            </table>
        </div>
    </div>
</div>
@endsection
<x-guest-layout>
    <div class="py-5 px-10 !font-['Manrope']">
        @php
            $className = strtoupper($student->classroom->name); // Convert to uppercase for consistency
        @endphp
        <div class=" bg-white mx-auto max-w-[595px] p-5">
            <div id="content" class="max-w-[595px] mx-auto bg-white py-8">
                <div class="flex justify-center mb-2">
                    <img src="{{ asset('images/coat-of-arm.jpeg') }}" alt="coat of arm" class=" w-28">
                </div>
                <div class="mb-3">
                    <h3 class="uppercase font-bold text-center text-sm">Anambra state ministry of education</h3>
                    <h3 class="uppercase font-bold text-center text-sm">
                        @if (in_array($className, ['JSS1', 'JSS 1', 'JSS2', 'JSS 2', 'JSS3', 'JSS 3']))
                            <span>Junior</span>
                        @elseif(in_array($className, ['SS1', 'SS 1', 'SS2', 'SS 2', 'SS3', 'SS 3']))
                            <span>Senior</span>
                        @else
                            <span>Unknown Category</span>
                        @endif
                        Secondary school
                    </h3>
                    <h3 class="uppercase font-bold text-center text-sm">{{ $result->term->name ?? 'N/A' }} term result
                    </h3>
                </div>

                <div class="text-sm">
                    <div class="flex items-center space-x-2">
                        <p class="capitalize font-semibold">Name of school:</p>
                        <p class="capitalize font-semibold tracking-wider">De gracious lively stones academy</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <p class="capitalize font-semibold">Name of Student:</p>
                        <p class="capitalize font-semibold tracking-wider"> {{ $student->name }}</p>
                    </div>
                </div>

                {{-- <p class="mb-4"><strong>Classroom:</strong> {{ $student->classroom->name ?? 'N/A' }}</p> --}}


                @if ($result)
                    <div class="text-sm mb-4">
                        <div class="flex justify-between items-center border-b">
                            {{-- <p>Grade: {{ $result->grade }}</p> --}}
                            <p class="font-semibold">Position: <span class=" font-medium">{{ $result->position }}</span>
                            <p class="font-semibold">Class: <span
                                    class=" font-medium">{{ $student->classroom->name ?? 'N/A' }}</span>
                            </p>
                        </div>
                        <div class="flex justify-between items-center border-b">
                            <p class="font-semibold">Total Score: <span
                                    class=" font-medium">{{ $result->total_score }}</span></p>
                            <p class="font-semibold">Average: <span class=" font-medium">{{ $result->average }}</span>
                            </p>
                        </div>
                        <div class="flex justify-between items-center border-b">
                            <p class="font-semibold">Year: <span
                                    class=" font-medium">{{ $result->sessionYear->name ?? 'N/A' }}</span></p>
                            <p class="font-semibold">Term: <span
                                    class=" font-medium">{{ $result->term->name ?? 'N/A' }}</span></p>
                        </div>
                    </div>
                @else
                    <p>No result found for this term and session.</p>
                @endif



                {{-- <h3 class="text-xl font-semibold mt-6 mb-2">Subject Scores</h3> --}}
                <div class="grid grid-cols-5 border">
                    <div class="col-span-3">
                        @if ($scores->count())
                            <table class="w-full border border-gray-300  text-xs">
                                <thead class="">
                                    <tr>
                                        <th class="p-2 border text-left">Subject</th>
                                        <th class="p-2 border">CA1 </th>
                                        <th class="p-2 border">CA2 </th>
                                        <th class="p-2 border">Exam </th>
                                        <th class="p-2 border">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($scores as $score)
                                        <tr>
                                            <td class="p-2 border font-semibold">{{ $score->subject->name ?? 'N/A' }}
                                            </td>
                                            <td class="p-2 border">{{ $score->ca1_score }}</td>
                                            <td class="p-2 border">{{ $score->ca2_score }}</td>
                                            <td class="p-2 border">{{ $score->exam_score }}</td>
                                            <td class="p-2 border">{{ $score->total_score }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No subject scores available for this student.</p>
                        @endif
                    </div>

                    <div class="text-xs col-span-2 ">
                        <div class="border p-2 ">
                            <p class="font-semibold mb-2">Key grades</p>
                            <div class="grid grid-cols-2 gap-1">
                                <p class="">
                                    <span class="font-semibold">A</span>
                                    <span>=</span>
                                    <span class="font-semibold">70 - 100</span>
                                </p>
                                <p class="">
                                    <span class="font-semibold">B</span>
                                    <span>=</span>
                                    <span class="font-semibold">70 - 100</span>
                                </p>
                                <p class="">
                                    <span class="font-semibold">C</span>
                                    <span>=</span>
                                    <span class="font-semibold">70 - 100</span>
                                </p>
                                <p class="">
                                    <span class="font-semibold">D</span>
                                    <span>=</span>
                                    <span class="font-semibold">70 - 100</span>
                                </p>
                                <p class="">
                                    <span class="font-semibold">E</span>
                                    <span>=</span>
                                    <span class="font-semibold">70 - 100</span>
                                </p>
                                <p class="">
                                    <span class="font-semibold">F</span>
                                    <span>=</span>
                                    <span class="font-semibold">70 - 100</span>
                                </p>
                            </div>
                        </div>
                        <div class="border px-2 p-5 space-y-5 ">
                            <div class=" ">
                                <p class="font-semibold">Form teacher's report: </p>
                                <br>
                                <hr>
                                <br>
                                <hr>
                                <br>
                                <hr>
                            </div>
                            <div class=" ">
                                <p class="font-semibold">Princeipal's report: </p>
                                <br>
                                <hr>
                                <br>
                                <hr>
                                <br>
                                <hr>
                            </div>
                        </div>

                    </div>
                </div>


                @if ($termId == 3)
                    <div class="mt-6  p-4 rounded mt-80">
                        <h2 class=" text-xs mb-4">Combined Term Scores</h2>
                        <div class="flex justify-center mb-2">
                            <img src="{{ asset('images/coat-of-arm.jpeg') }}" alt="coat of arm" class=" w-28">
                        </div>
                        <div class="mb-3">
                            <h3 class="uppercase font-bold text-center text-sm">Anambra state ministry of education</h3>
                            <h3 class="uppercase font-bold text-center text-sm">
                                @if (in_array($className, ['JSS1', 'JSS 1', 'JSS2', 'JSS 2', 'JSS3', 'JSS 3']))
                                    <span>Junior</span>
                                @elseif(in_array($className, ['SS1', 'SS 1', 'SS2', 'SS 2', 'SS3', 'SS 3']))
                                    <span>Senior</span>
                                @else
                                    <span>Unknown Category</span>
                                @endif
                                Secondary school
                            </h3>

                            <h3 class="uppercase font-bold text-center text-sm">Cummulative
                                result
                            </h3>
                        </div>

                        <div class="text-sm mb-1">
                            <div class="flex items-center space-x-2">
                                <p class="capitalize font-semibold">Name of school:</p>
                                <p class="capitalize font-semibold tracking-wider">De gracious lively stones academy</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <p class="capitalize font-semibold">Name of Student:</p>
                                <p class="capitalize font-semibold tracking-wider"> {{ $student->name }}</p>
                            </div>
                        </div>

                        <div class="text-sm mb-4">
                            <p class="font-semibold border-b ">postion: <span
                                    class="font-medium">{{ $cummulativePosition }}</span></p>
                            <div class="flex items-center justify-between border-b">
                                <p class="font-semibold">Total: <span class="font-medium">{{ $totalSum }}</span>
                                </p>
                                <p class="font-semibold">Avarage: <span class="font-medium">{{ $averageScore }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-5 border">
                            <div class="col-span-3 ">
                                <table class="w-full text-sm ">
                                    <thead class="">
                                        <tr>
                                            <th class="p-2 text-left border">Subject</th>
                                            <th class="p-2 text-left border">1st Term</th>
                                            <th class="p-2 text-left border">2nd Term</th>
                                            <th class="p-2 text-left border">3rd Term</th>
                                            <th class="p-2 text-left border">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($groupedScores as $subjectData)
                                            <tr class="border-t">
                                                <td class="p-2 border font-semibold">{{ $subjectData['subject'] }}
                                                </td>
                                                <td class="p-2 border">
                                                    {{ $subjectData['first'] != 0 ? $subjectData['first'] : 'N/A' }}
                                                </td>
                                                <td class="p-2 border">
                                                    {{ $subjectData['second'] != 0 ? $subjectData['second'] : 'N/A' }}
                                                </td>
                                                <td class="p-2 border">
                                                    {{ $subjectData['third'] != 0 ? $subjectData['third'] : 'N/A' }}
                                                </td>
                                                <td class="p-2 border font-semibold">
                                                    {{ $subjectData['first'] + $subjectData['second'] + $subjectData['third'] }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="p-2 border text-center text-gray-500">No
                                                    scores
                                                    found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-xs col-span-2">
                                <div class="border p-2 ">
                                    <p class="font-semibold mb-2">Key grades</p>
                                    <div class="grid grid-cols-2 gap-1">
                                        <p class="">
                                            <span class="font-semibold">A</span>
                                            <span>=</span>
                                            <span class="font-semibold">70 - 100</span>
                                        </p>
                                        <p class="">
                                            <span class="font-semibold">B</span>
                                            <span>=</span>
                                            <span class="font-semibold">70 - 100</span>
                                        </p>
                                        <p class="">
                                            <span class="font-semibold">C</span>
                                            <span>=</span>
                                            <span class="font-semibold">70 - 100</span>
                                        </p>
                                        <p class="">
                                            <span class="font-semibold">D</span>
                                            <span>=</span>
                                            <span class="font-semibold">70 - 100</span>
                                        </p>
                                        <p class="">
                                            <span class="font-semibold">E</span>
                                            <span>=</span>
                                            <span class="font-semibold">70 - 100</span>
                                        </p>
                                        <p class="">
                                            <span class="font-semibold">F</span>
                                            <span>=</span>
                                            <span class="font-semibold">70 - 100</span>
                                        </p>
                                    </div>
                                </div>

                                <div class="border px-2 p-5 space-y-5 ">
                                    <div class=" ">
                                        <p class="font-semibold">Form teacher's report: </p>
                                        <br>
                                        <hr>
                                        <br>
                                        <hr>
                                        <br>
                                        <hr>
                                    </div>
                                    <div class=" ">
                                        <p class="font-semibold">Princeipal's report: </p>
                                        <br>
                                        <hr>
                                        <br>
                                        <hr>
                                        <br>
                                        <hr>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                @endif

                <div class="mt-4 ">
                    <button id="download" class="cursor-pointer hover:underline">Download as PDF</button>
                </div>
            </div>
        </div>
    </div>


    {{-- <div>
        <button onclick="window.print()">Print Page</button>

    </div> --}}


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

    <script>
        document.getElementById("download").addEventListener("click", () => {
            const element = document.getElementById("content");
            const opt = {
                margin: 0.5,
                filename: 'result.pdf',
                image: {
                    type: 'jpeg',
                    quality: 1
                }, // Use 'png' for lossless compression
                html2canvas: {
                    scale: 2
                }, // Increase scale for better quality
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                }
            };

            html2pdf().set(opt).from(element).save();
        });
    </script>

</x-guest-layout>

<x-guest-layout>


    <h2 class="text-2xl font-bold mb-2">Results for {{ $student->name }}</h2>
    <p class="mb-4"><strong>Classroom:</strong> {{ $student->classroom->name ?? 'N/A' }}</p>


    <h2 class="text-xl font-bold">Term Result</h2>
    @if ($result)
        <p>Total Score: {{ $result->total_score }}</p>
        <p>Average: {{ $result->average }}</p>
        <p>Grade: {{ $result->grade }}</p>
        <p>Position: {{ $result->position }}</p>
        <p>Session Year: {{ $result->sessionYear->name ?? 'N/A' }}</p>
        <p>Term: {{ $result->term->name ?? 'N/A' }}</p>
    @else
        <p>No result found for this term and session.</p>
    @endif



    <h3 class="text-xl font-semibold mt-6 mb-2">Subject Scores</h3>
    @if ($scores->count())
        <table class="w-full border border-gray-300 mb-6 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">Subject</th>
                    <th class="p-2 border">CA1 Score</th>
                    <th class="p-2 border">CA2 Score</th>
                    <th class="p-2 border">Exam Score</th>
                    <th class="p-2 border">Total Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($scores as $score)
                    <tr>
                        <td class="p-2 border">{{ $score->subject->name ?? 'N/A' }}</td>
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


    @if ($termId == 3)
        <div class="mt-6 bg-gray-100 p-4 rounded">
            <h2 class="font-bold text-lg mb-4">Combined Term Scores</h2>

            <table class="w-full text-sm mt-2 border">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-2 text-left">Subject</th>
                        <th class="p-2 text-left">1st Term</th>
                        <th class="p-2 text-left">2nd Term</th>
                        <th class="p-2 text-left">3rd Term</th>
                        <th class="p-2 text-left">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($groupedScores as $subjectData)
                        <tr class="border-t">
                            <td class="p-2">{{ $subjectData['subject'] }}</td>
                            <td class="p-2">{{ $subjectData['first'] != 0 ? $subjectData['first'] : 'N/A'  }}</td>
                            <td class="p-2">{{ $subjectData['second'] != 0 ? $subjectData['second'] : 'N/A'  }}</td>
                            <td class="p-2">{{ $subjectData['third'] != 0 ? $subjectData['third'] : 'N/A'  }}</td>
                            <td class="p-2 font-bold">
                                {{ $subjectData['first'] + $subjectData['second'] + $subjectData['third'] }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-2 text-center text-gray-500">No scores found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            <p>Total: {{ $totalSum }}</p>
            <p>Avarage: {{ $averageScore }}</p>
            <p>postion: {{ $cummulativePosition }}</p>
        </div>
    @endif



</x-guest-layout>

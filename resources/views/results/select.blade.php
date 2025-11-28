<x-guest-layout>
    <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow ">
        <h2 class="text-2xl font-semibold mb-6 text-center">View Student Result</h2>
        <x-session-msg />

        <form action="{{ route('results.student') }}" method="GET">
            @csrf

            <div class="mb-4">
                <label for="scratch_card_code" class="block text-gray-700 font-semibold">Scratch card code</label>
                <input type="text" name="scratch_card_code" id="scratch_card_code" class="form-control"
                    placeholder="scratch code" required autocomplete="off">
            </div>

            <div class="mb-4">
                <label for="session_year_id" class="block text-gray-700 font-semibold">Session Year</label>
                <select name="session_year_id" id="session_year_id" class="form-control" required>
                    <option value="">-- Select Session --</option>
                    @foreach ($sessions as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="term_id" class="block text-gray-700 font-semibold">Term</label>
                <select name="term_id" id="term_id" class="form-control" required disabled>
                    <option value="">-- Select Term --</option>
                    @foreach ($terms as $term)
                        <option value="{{ $term->id }}">{{ $term->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="classroom_id" class="block text-gray-700 font-semibold">Class</label>
                <select name="classroom_id" id="classroom_id" class="form-control" disabled>
                    <option value="">-- Select Class --</option>
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4 hidden" id="student_select_wrapper">
                <label for="student_id" class="block text-gray-700 font-semibold">Students (Batch optional)</label>
                <select name="student_id" id="student_id" class="form-control" disabled>
                    <option value="">-- Select Student --</option>
                </select>
            </div>

            <button type="submit" class="btn">
                View Result
            </button>
        </form>
    </div>


    {{-- <script>
        document.getElementById('classroom_id').addEventListener('change', function() {
            const classroomId = this.value;
            const studentSelect = document.getElementById('student_id');
            studentSelect.innerHTML = '<option value="">-- Loading Students --</option>';

            if (classroomId) {
                fetch(`/get-students/${classroomId}`)
                    .then(response => response.json())
                    .then(data => {
                        studentSelect.innerHTML = '<option value="">-- Select Student --</option>';
                        data.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.text = student.name;
                            studentSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching students:', error);
                        studentSelect.innerHTML = '<option value="">-- Error loading students --</option>';
                    });
            } else {
                studentSelect.innerHTML = '<option value="">-- Select Student --</option>';
            }
        });
    </script> --}}

    <script>
        const sessionSelect = document.getElementById('session_year_id');
        const termSelect = document.getElementById('term_id');
        const classSelect = document.getElementById('classroom_id');
        const studentSelect = document.getElementById('student_id');
        const studentWrapper = document.getElementById('student_select_wrapper');

        sessionSelect.addEventListener('change', () => {
            const hasSession = !!sessionSelect.value;
            termSelect.disabled = !hasSession;
            classSelect.disabled = true;
            studentSelect.disabled = true;
            studentWrapper.classList.add('hidden');
        });

        termSelect.addEventListener('change', () => {
            const hasTerm = !!termSelect.value;
            classSelect.disabled = !hasTerm;
            studentSelect.disabled = true;
            studentWrapper.classList.add('hidden');
        });

        classSelect.addEventListener('change', function() {
            const classroomId = this.value;
            const sessionYearId = sessionSelect.value;
            const termId = termSelect.value;
            if (classroomId && sessionYearId && termId) {
                studentWrapper.classList.remove('hidden');
                studentSelect.disabled = false;
                studentSelect.innerHTML = '<option value="">-- Loading Students --</option>';
                const params = new URLSearchParams({ classroom_id: classroomId, session_year_id: sessionYearId, term_id: termId });
                fetch(`/students/with-results?${params.toString()}`)
                    .then(response => response.json())
                    .then(data => {
                        studentSelect.innerHTML = '<option value="">-- Select Student --</option>';
                        data.forEach(student => {
                            const option = document.createElement('option');
                            option.value = student.id;
                            option.text = student.name;
                            studentSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error fetching students:', error);
                        studentSelect.innerHTML = '<option value="">-- Error loading students --</option>';
                    });
            } else {
                studentWrapper.classList.add('hidden');
                studentSelect.disabled = true;
                studentSelect.innerHTML = '<option value="">-- Select Student --</option>';
            }
        });
    </script>

</x-guest-layout>

<x-guest-layout>
    @php
        $genderLabel = data_get($student, 'gender.value', 'N/A');
        $studentName = $student->name;
        $schoolName = config('app.name', 'School Management System');
        $classDisplay = $resultData['session']['class_name'] ?? '';
        $classSlug = strtoupper(str_replace(' ', '', $classDisplay));
        $categoryLabel = 'Secondary School';
        if (str_contains($classSlug, 'JSS')) {
            $categoryLabel = 'Junior Secondary School';
        } elseif (str_contains($classSlug, 'SSS') || str_contains($classSlug, 'SS')) {
            $categoryLabel = 'Senior Secondary School';
        }
        $termTitle = $resultData['session']['term_name'] ?? 'Term';
    @endphp

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: #f3f4f6;
        }

        .result-wrapper {
            padding: 20px;
        }

        .result-container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
        }

        .emblem {
            display: flex;
            justify-content: center;
            margin-bottom: 10px;
        }

        .emblem img {
            width: 90px;
            height: auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .gov-label {
            text-transform: uppercase;
            font-weight: 700;
            color: #111827;
            font-size: 12px;
            margin-bottom: 4px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .document-title {
            font-size: 16px;
            color: #374151;
            margin-bottom: 10px;
        }

        .session-info {
            font-size: 12px;
            color: #6b7280;
        }

        .student-info {
            background: #f8fafc;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
        }

        .student-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-info td {
            padding: 5px;
            vertical-align: top;
        }

        .student-details h2 {
            font-size: 18px;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .student-details p {
            margin-bottom: 4px;
            color: #4b5563;
            font-size: 11px;
        }

        .performance-summary {
            text-align: right;
        }

        .grade-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #3b82f6;
            color: white;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .performance-stats {
            font-size: 10px;
            color: #6b7280;
        }

        .section {
            background: #fff;
            border: 1px solid #e2e8f0;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #f1f5f9;
            padding: 8px 12px;
            border-bottom: 1px solid #e2e8f0;
        }

        .section-header h3 {
            font-size: 13px;
            color: #1e40af;
            margin: 0;
        }

        .section-content {
            padding: 12px;
        }

        .scores-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
        }

        .scores-table th,
        .scores-table td {
            padding: 6px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        .scores-table th {
            background: #f8fafc;
            font-weight: bold;
            color: #374151;
            font-size: 9px;
        }

        .scores-table tr:nth-child(even) {
            background: #fafbfc;
        }

        .grade-excellent {
            color: #059669;
            font-weight: bold;
        }

        .grade-good {
            color: #0284c7;
            font-weight: bold;
        }

        .grade-average {
            color: #d97706;
            font-weight: bold;
        }

        .grade-poor {
            color: #dc2626;
            font-weight: bold;
        }

        .grade-key {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 6px;
            font-size: 11px;
        }

        .grade-key span {
            font-weight: 600;
            color: #111827;
        }

        .behavioral-item {
            padding: 4px 0;
            border-bottom: 1px solid #f1f5f9;
            font-size: 10px;
        }

        .behavioral-item:last-child {
            border-bottom: none;
        }

        .behavioral-item table {
            width: 100%;
            border-collapse: collapse;
        }

        .behavioral-item td {
            padding: 2px;
        }

        .trait-name {
            font-size: 10px;
            color: #374151;
        }

        .rating-dots {
            text-align: right;
        }

        .dot {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin: 0 1px;
        }

        .dot-filled {
            background: #3b82f6;
        }

        .dot-empty {
            background: #e5e7eb;
        }

        .attendance-grid table {
            width: 100%;
            border-collapse: collapse;
        }

        .attendance-grid td {
            padding: 10px;
            text-align: center;
            border: 1px solid #e2e8f0;
        }

        .attendance-value {
            font-size: 18px;
            font-weight: bold;
            color: #1e40af;
        }

        .attendance-label {
            font-size: 10px;
            color: #6b7280;
        }

        .comment-content {
            background: #f8fafc;
            padding: 10px;
            border: 1px solid #e2e8f0;
            font-size: 11px;
            line-height: 1.5;
            min-height: 40px;
        }

        .remarks-grid {
            display: grid;
            grid-template-columns: 170px 1fr;
            row-gap: 10px;
            column-gap: 8px;
            font-size: 11px;
        }

        .remark-label {
            font-weight: 600;
            text-transform: capitalize;
            color: #111827;
        }

        .remark-line {
            border-bottom: 1px dashed #cbd5f5;
            min-height: 24px;
            display: flex;
            align-items: flex-end;
            padding-bottom: 3px;
            color: #374151;
            font-weight: 500;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #e2e8f0;
            font-size: 10px;
            color: #6b7280;
        }

        .download-actions {
            text-align: center;
            margin-top: 20px;
        }

        .download-button {
            background: #1d4ed8;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .download-button:hover {
            background: #1e40af;
        }

        .alert {
            margin: 40px auto;
            max-width: 600px;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
        }
    </style>

    @if (!$result)
        <div class="alert">
            No result found for the selected term and session.
        </div>
    @else
        <div class="result-wrapper">
            <div class="result-container" id="result-export-root">
                <div class="header">
                    <div class="emblem">
                        <img src="{{ asset('images/coat-of-arm.jpeg') }}" alt="Coat of arm">
                    </div>
                    <p class="gov-label">Anambra State Ministry of Education</p>
                    <div class="school-name">{{ $schoolName }}</div>
                    <div class="document-title">{{ $categoryLabel }} • {{ $termTitle }} Term Result</div>
                    <div class="session-info">
                        {{ $resultData['session']['session_name'] }} &mdash;
                        {{ $resultData['session']['term_name'] }} |
                        Class: {{ $resultData['session']['class_name'] }} {{ $batchName }}
                    </div>
                </div>

                <div class="student-info">
                    <table>
                        <tr>
                            <td style="width: 70%;">
                                <div class="student-details">
                                    <h2>{{ $studentName }}</h2>
                                    <p><strong>Student ID:</strong> {{ $student->registration_number ?? 'N/A' }}</p>
                                    <p><strong>Class:</strong> {{ $resultData['session']['class_name'] ?? 'N/A' }}
                                        {{ $batchName ? '(' . $batchName . ')' : '' }}
                                    </p>
                                    <p><strong>Position:</strong>
                                        {{ $resultData['performance']['rank'] ?? 'N/A' }}
                                        @if ($totalStudents)
                                            <span class="text-xs text-gray-500">/ {{ $totalStudents }}</span>
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td style="width: 30%;">
                                <div class="performance-summary">
                                    <div class="grade-circle">{{ $resultData['performance']['grade'] }}</div>
                                    <div class="performance-stats">
                                        <div>
                                            <strong>{{ $resultData['performance']['average_score'] }}%</strong>
                                            Average
                                        </div>
                                        <div>
                                            <strong>{{ $resultData['performance']['assessed_subjects'] }}/{{ $resultData['performance']['total_subjects'] }}</strong>
                                            Subjects
                                        </div>
                                        <div>
                                            <strong>#{{ $resultData['performance']['rank'] }}</strong> Rank
                                        </div>
                                        <div>
                                            <strong>{{ $result->total_score }}</strong> Total Score
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="section">
                    <div class="section-header">
                        <h3>Grade Interpretation</h3>
                    </div>
                    <div class="section-content">
                        <div class="grade-key">
                            <div><span>A</span> : 70 - 100</div>
                            <div><span>B</span> : 60 - 69</div>
                            <div><span>C</span> : 50 - 59</div>
                            <div><span>D</span> : 45 - 49</div>
                            <div><span>E</span> : 40 - 44</div>
                            <div><span>F</span> : 0 - 39</div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-header">
                        <h3>Academic Performance</h3>
                    </div>
                    <div class="section-content">
                        @if (count($resultData['scores']))
                            <table class="scores-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>CA</th>
                                        <th>Exam</th>
                                        <th>Total</th>
                                        <th>Grade</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultData['scores'] as $index => $score)
                                        @php
                                            $grade = $score['grade'] ?? 'N/A';
                                            $gradeClass = match (true) {
                                                in_array($grade, ['A+', 'A']) => 'grade-excellent',
                                                in_array($grade, ['B+', 'B']) => 'grade-good',
                                                in_array($grade, ['C+', 'C', 'D']) => 'grade-average',
                                                default => 'grade-poor',
                                            };
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $score['subject_name'] }}</td>
                                            <td>{{ $score['ca'] ?? '-' }}</td>
                                            <td>{{ $score['exam'] ?? '-' }}</td>
                                            <td class="font-bold">{{ $score['total_score'] ?? '-' }}</td>
                                            <td class="{{ $gradeClass }}">{{ $grade }}</td>
                                            <td>{{ $score['remark'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No subject scores available for this student.</p>
                        @endif
                    </div>
                </div>

                @if (!empty($resultData['behavioral_traits']))
                    <div class="section">
                        <div class="section-header">
                            <h3>Behavioral Assessment</h3>
                        </div>
                        <div class="section-content">
                            @foreach ($resultData['behavioral_traits'] as $trait)
                                <div class="behavioral-item">
                                    <table>
                                        <tr>
                                            <td class="trait-name">{{ $trait['name'] }}</td>
                                            <td class="rating-dots">
                                                @foreach ($trait['dots'] as $filled)
                                                    <span class="dot {{ $filled ? 'dot-filled' : 'dot-empty' }}"></span>
                                                @endforeach
                                                <span style="margin-left: 5px; font-size: 9px;">{{ $trait['rating'] }}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="section">
                    <div class="section-header">
                        <h3>Attendance Summary</h3>
                    </div>
                    <div class="section-content">
                        <div class="attendance-grid">
                            <table>
                                <tr>
                                    <td>
                                        <div class="attendance-value">
                                            {{ $resultData['attendance']['school_opened'] ?? '--' }}
                                        </div>
                                        <div class="attendance-label">School Opened</div>
                                    </td>
                                    <td>
                                        <div class="attendance-value">
                                            {{ $resultData['attendance']['times_present'] ?? '--' }}
                                        </div>
                                        <div class="attendance-label">Times Present</div>
                                    </td>
                                    <td>
                                        <div class="attendance-value">
                                            {{ $resultData['attendance']['percentage'] ?? '--' }}{{ $resultData['attendance']['percentage'] ? '%' : '' }}
                                        </div>
                                        <div class="attendance-label">Attendance Rate</div>
                                    </td>
                                    <td>
                                        <div class="attendance-value">
                                            {{ $resultData['attendance']['punctuality'] ?? '--' }}
                                        </div>
                                        <div class="attendance-label">Punctuality</div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <div class="section-header">
                        <h3>Remarks & Next Term Information</h3>
                    </div>
                    <div class="section-content">
                        <div class="remarks-grid">
                            <div class="remark-label">Class Teacher's Remark</div>
                            <div class="remark-line">
                                {{ $resultData['comments']['teacher'] ?? '' }}
                            </div>
                            <div class="remark-label">Head Teacher's Remark</div>
                            <div class="remark-line">
                                {{ $resultData['comments']['head_teacher'] ?? '' }}
                            </div>
                            <div class="remark-label">Next Term Begins</div>
                            <div class="remark-line">&nbsp;</div>
                            <div class="remark-label">Principal's Signature</div>
                            <div class="remark-line">&nbsp;</div>
                        </div>
                    </div>
                </div>

                @if ($termId == 3 && !empty($groupedScores))
                    <div class="section">
                        <div class="section-header">
                            <h3>Cumulative Summary</h3>
                        </div>
                        <div class="section-content">
                            <div class="student-info" style="margin-bottom: 10px;">
                                <strong>Position:</strong> {{ $cummulativePosition ?? 'N/A' }} /
                                {{ $totalStudents }}
                                <br>
                                <strong>Total:</strong> {{ $totalSum ?? 'N/A' }} |
                                <strong>Average:</strong> {{ $averageScore ?? 'N/A' }}
                            </div>
                            <table class="scores-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>First Term</th>
                                        <th>Second Term</th>
                                        <th>Third Term</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($groupedScores as $index => $subjectData)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $subjectData['subject'] }}</td>
                                            <td>{{ $subjectData['first'] ?: 'N/A' }}</td>
                                            <td>{{ $subjectData['second'] ?: 'N/A' }}</td>
                                            <td>{{ $subjectData['third'] ?: 'N/A' }}</td>
                                            <td class="font-bold">
                                                {{ ($subjectData['first'] ?? 0) + ($subjectData['second'] ?? 0) + ($subjectData['third'] ?? 0) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                <div class="footer">
                    <p>Generated on {{ $generatedAt }}</p>
                    <p>{{ $schoolName }} &mdash; Academic Result System</p>
                </div>
            </div>

            <div class="download-actions">
                <button id="download-result" class="download-button">Download as PDF</button>
            </div>
        </div>
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>
    <script>
        document.getElementById('download-result')?.addEventListener('click', () => {
            const element = document.getElementById('result-export-root');

            if (!element) {
                return;
            }

            const options = {
                margin: 0.3,
                filename: '{{ \Illuminate\Support\Str::slug($studentName) }}-result.pdf',
                image: {
                    type: 'png',
                    quality: 1
                },
                html2canvas: {
                    scale: 2,
                    useCORS: true,
                    letterRendering: true,
                    backgroundColor: '#ffffff'
                },
                jsPDF: {
                    unit: 'in',
                    format: 'a4',
                    orientation: 'portrait'
                },
                pagebreak: {
                    mode: ['avoid-all', 'css', 'legacy']
                }
            };

            html2pdf().set(options).from(element).save();
        });
    </script>
</x-guest-layout>

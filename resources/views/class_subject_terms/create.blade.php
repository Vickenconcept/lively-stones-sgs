@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto p-6">
    <h1 class="text-xl font-bold mb-4">Assign Subject(s) to Class(es) for Term(s)</h1>

    <form action="{{ route('class_subject_terms.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div x-data="tagSelect({ options: @js($classrooms->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values()), input: 'classroom_ids' })">
                <label class="block font-medium mb-2">Classrooms (multi-select)</label>
                <div class="tag-select">
                    <div class="tag-dropdown relative">
                        <input x-model="query" @input="filter()" @focus="open = true" type="text" placeholder="Search classrooms..." class="tag-select-input"/>
                        <div x-show="open" class="tag-dropdown-list">
                            <template x-if="filtered.length === 0">
                                <div class="tag-dropdown-item text-gray-400">No options found</div>
                            </template>
                            <template x-for="opt in filtered" :key="opt.id">
                                <div class="tag-dropdown-item" @click="add(opt)"><span x-text="opt.name"></span></div>
                            </template>
                        </div>
                    </div>
                    <div class="tag-select-tags mt-2">
                        <template x-for="sel in Array.from(selected.values())" :key="sel.id">
                            <span class="tag-chip">
                                <span x-text="sel.name"></span>
                                <button type="button" @click="remove(sel.id)">×</button>
                            </span>
                        </template>
                    </div>
                    <template x-for="id in Array.from(selected.keys())" :key="id">
                        <input type="hidden" :name="input + '[]'" :value="id">
                    </template>
                </div>
                @error('classroom_ids')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="tagSelect({ options: @js($subjects->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values()), input: 'subject_ids' })">
                <label class="block font-medium mb-2">Subjects (multi-select)</label>
                <div class="tag-select">
                    <div class="tag-dropdown relative">
                        <input x-model="query" @input="filter()" @focus="open = true" type="text" placeholder="Search subjects..." class="tag-select-input"/>
                        <div x-show="open" class="tag-dropdown-list">
                            <template x-if="filtered.length === 0">
                                <div class="tag-dropdown-item text-gray-400">No options found</div>
                            </template>
                            <template x-for="opt in filtered" :key="opt.id">
                                <div class="tag-dropdown-item" @click="add(opt)"><span x-text="opt.name"></span></div>
                            </template>
                        </div>
                    </div>
                    <div class="tag-select-tags mt-2">
                        <template x-for="sel in Array.from(selected.values())" :key="sel.id">
                            <span class="tag-chip">
                                <span x-text="sel.name"></span>
                                <button type="button" @click="remove(sel.id)">×</button>
                            </span>
                        </template>
                    </div>
                    <template x-for="id in Array.from(selected.keys())" :key="id">
                        <input type="hidden" :name="input + '[]'" :value="id">
                    </template>
                </div>
                @if($subjects->isEmpty())
                    <p class="text-yellow-700 bg-yellow-50 border border-yellow-200 rounded px-3 py-2 text-sm mt-2">
                        No subjects available. <a href="{{ route('subjects.create') }}" class="underline">Create a subject</a> first.
                    </p>
                @endif
                @error('subject_ids')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div x-data="tagSelect({ options: @js($terms->map(fn($m) => ['id' => $m->id, 'name' => $m->name])->values()), input: 'term_ids' })">
                <label class="block font-medium mb-2">Terms (multi-select)</label>
                <div class="tag-select">
                    <div class="tag-dropdown relative">
                        <input x-model="query" @input="filter()" @focus="open = true" type="text" placeholder="Search terms..." class="tag-select-input"/>
                        <div x-show="open" class="tag-dropdown-list">
                            <template x-if="filtered.length === 0">
                                <div class="tag-dropdown-item text-gray-400">No options found</div>
                            </template>
                            <template x-for="opt in filtered" :key="opt.id">
                                <div class="tag-dropdown-item" @click="add(opt)"><span x-text="opt.name"></span></div>
                            </template>
                        </div>
                    </div>
                    <div class="tag-select-tags mt-2">
                        <template x-for="sel in Array.from(selected.values())" :key="sel.id">
                            <span class="tag-chip">
                                <span x-text="sel.name"></span>
                                <button type="button" @click="remove(sel.id)">×</button>
                            </span>
                        </template>
                    </div>
                    <template x-for="id in Array.from(selected.keys())" :key="id">
                        <input type="hidden" :name="input + '[]'" :value="id">
                    </template>
                </div>
                @error('term_ids')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block font-medium mb-2">Session Year (single select)</label>
                <select name="session_year_id" class="form-control">
                    @foreach($sessionYears as $session)
                        <option value="{{ $session->id }}">{{ $session->name }}</option>
                    @endforeach
                </select>
                @error('session_year_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <button type="submit" class="btn">Save</button>
        </div>
    </form>

    <style>
        .tag-select { border: 1px solid #d1d5db; border-radius: 6px; padding: 8px; background: #ffffff; }
        .tag-select-input { width: 100%; border: none; outline: none; padding: 6px 8px; background: #ffffff; }
        .tag-select-tags { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 8px; }
        .tag-chip { background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 9999px; padding: 4px 10px; display: inline-flex; align-items: center; gap: 6px; }
        .tag-chip button { border: none; background: transparent; cursor: pointer; font-weight: bold; }
        .tag-dropdown { position: relative; }
        .tag-dropdown-list { position: absolute; z-index: 50; background: #ffffff; border: 1px solid #e5e7eb; border-radius: 6px; margin-top: 4px; width: 100%; max-height: 220px; overflow-y: auto; }
        .tag-dropdown-item { padding: 8px 10px; cursor: pointer; }
        .tag-dropdown-item:hover { background: #f9fafb; }
        .form-control { background: #ffffff; }
    </style>

    <script>
        (() => {
            const register = () => {
                Alpine.data('tagSelect', ({ options, input }) => ({
                    all: options || [],
                    input,
                    selected: new Map(),
                    filtered: [],
                    query: '',
                    open: false,
                    filter() {
                        const q = this.query.trim().toLowerCase();
                        const selectedIds = new Set(Array.from(this.selected.keys()).map(String));
                        this.filtered = this.all.filter(o => !selectedIds.has(String(o.id)) && (!q || String(o.name).toLowerCase().includes(q)));
                    },
                    add(opt) {
                        this.selected.set(String(opt.id), { id: String(opt.id), name: opt.name });
                        this.query = '';
                        this.filter();
                        this.open = true;
                    },
                    remove(id) {
                        this.selected.delete(String(id));
                        this.filter();
                        this.open = true;
                    },
                    init() {
                        this.filter();
                        document.addEventListener('click', (e) => {
                            if (!this.$root.contains(e.target)) this.open = false;
                        });
                    }
                }));
            };
            if (window.Alpine) register();
            document.addEventListener('alpine:init', register);
        })();
    </script>
</div>
@endsection

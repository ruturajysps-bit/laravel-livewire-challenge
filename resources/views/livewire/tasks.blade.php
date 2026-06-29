<?php

use App\Models\Task;
use Livewire\Volt\Component;

new class extends Component {
    public string $title = '';

    public function addTask(): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
        ]);

        Task::create($validated);

        $this->reset('title');
    }

    public function toggle(int $id): void
    {
        $task = Task::findOrFail($id);

        $task->update([
            'completed' => ! $task->completed,
        ]);
    }

    public function with(): array
    {
        return [
            'tasks' => Task::latest()->get(),
        ];
    }
}; ?>

<div>
    <h1>Tasks</h1>

    <form wire:submit="addTask">
        <input type="text" wire:model="title" placeholder="What needs to be done?">
        <button type="submit">Add Task</button>

        @error('title')
            <p style="color: red;">{{ $message }}</p>
        @enderror
    </form>

    <ul>
        @forelse ($tasks as $task)
            <li wire:key="{{ $task->id }}">
                <input
                    type="checkbox"
                    wire:click="toggle({{ $task->id }})"
                    @checked($task->completed)
                >
                <span style="{{ $task->completed ? 'text-decoration: line-through;' : '' }}">
                    {{ $task->title }}
                </span>
            </li>
        @empty
            <li>No tasks yet.</li>
        @endforelse
    </ul>
</div>

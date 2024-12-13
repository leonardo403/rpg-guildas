<form wire:submit.prevent="savePlayer">
    <input type="text" wire:model="name" placeholder="Nome do Jogador">
    <select wire:model="class_id">
        <option value="">Selecione uma Classe</option>
        @foreach($classes as $class)
            <option value="{{ $class->id }}">{{ $class->name }}</option>
        @endforeach
    </select>
    <input type="number" wire:model="xp" min="1" max="100" placeholder="XP">
    <button type="submit">Salvar Jogador</button>
</form>

<div>
    <h2>Distribuir Jogadores em Guildas</h2>

    <button wire:click="distributePlayers">Distribuir Jogadores</button>

    @foreach($guilds as $index => $guild)
        <div style="margin-top: 20px; border: 1px solid #ccc; padding: 10px;">
            <h3>Guilda {{ $index + 1 }}</h3>
            <p>XP Total: {{ $guild['xp'] }}</p>
            <ul>
                @foreach($guild['players'] as $player)
                    <li>
                        {{ $player->name }} ({{ $player->class->name }}) - XP: {{ $player->xp }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>

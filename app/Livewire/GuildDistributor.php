<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Player;

class GuildDistributor extends Component
{
    public $maxPlayersPerGuild = 4; // Número máximo de jogadores por guilda
    public $guilds = []; // Guildas balanceadas
    public $players; // Jogadores confirmados
    public $classId;

    public function mount()
    {
        // Carregar jogadores confirmados ao montar o componente
        $this->players = Player::where('confirmed', true)->get();
    }

    public function distributePlayers()
    {
        // Inicializar guildas
        $this->guilds = [];

        // Agrupar jogadores por classe e ordenar por XP
        $groupedByClass = $this->players->groupBy('class_id')->map(function ($players) {
            return $players->sortByDesc('xp'); // Ordena por XP decrescente
        });

        // Alocar jogadores obrigatórios para as guildas
        foreach ($groupedByClass as $classId => $players) {
            foreach ($players as $player) {
                $guildAllocated = false;

                // Tente adicionar o jogador a uma guilda existente
                foreach ($this->guilds as &$guild) {
                    if ($this->canAddPlayerToGuild($player, $guild)) {
                        $guild['players'][] = $player;
                        $guild['xp'] += $player->xp;
                        $guild['classes'][] = $player->class_id;
                        $guildAllocated = true;
                        break;
                    }
                }

                // Se nenhuma guilda disponível, crie uma nova
                if (!$guildAllocated) {
                    $this->guilds[] = [
                        'players' => [$player],
                        'xp' => $player->xp,
                        'classes' => [$player->class_id],
                    ];
                }
            }
        }

        // Balancear XP entre guildas com jogadores restantes
        $this->balanceRemainingPlayers();
    }

    private function canAddPlayerToGuild($player, &$guild)
    {
        // Verificar se a guilda já está cheia
        if (count($guild['players']) >= $this->maxPlayersPerGuild) {
            return false;
        }

        // Garantir que classes obrigatórias sejam atendidas
        $requiredClasses = [1, 4]; // Exemplo: Guerreiro (1), Clérigo (4)
        if (in_array($player->class_id, $requiredClasses) && !in_array($player->class_id, $guild['classes'])) {
            return true;
        }

        // Caso contrário, permitir adição se houver espaço
        return true;
    }

    private function balanceRemainingPlayers()
    {
        $allPlayers = $this->players->pluck('id')->toArray();
        $allocatedPlayers = collect($this->guilds)->pluck('players.*.id')->flatten()->toArray();
        $remainingPlayers = array_diff($allPlayers, $allocatedPlayers);

        foreach ($remainingPlayers as $playerId) {
            $player = $this->players->firstWhere('id', $playerId);

            // Encontrar a guilda com menor XP
            $leastXPGuild = collect($this->guilds)->sortBy('xp')->first();

            // Adicionar o jogador à guilda com menor XP
            $leastXPGuild['players'][] = $player;
            $leastXPGuild['xp'] += $player->xp;
        }
    }

    public function render()
    {
        return view('livewire.guild-distributor', [
            'guilds' => $this->guilds,
        ]);
    }
}

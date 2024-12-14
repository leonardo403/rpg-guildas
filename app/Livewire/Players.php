<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Classes;
use App\Models\Player;

class Players extends Component
{

    public $name;
    public $classId;
    public $xp;

    public function render()
    {
        return view('livewire.players');
    }

    public function savePlayer()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'xp' => 'required|integer|between:1,100',
        ]);

        Player::create([
            'name' => $this->name,
            'class_id' => $this->classId,
            'xp' => $this->xp,
        ]);

        session()->flash('message', 'Jogador cadastrado com sucesso!');
    }
}

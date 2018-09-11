<?php

require 'data.php';

class football {
    protected $data;

    function __construct($data) {
        $this->data = $data;
    }

    public function match($c1, $c2) {
        $c1 = isset($this->data[$c1]) ? $this->data[$c1] : $this->error($c1);
        $c2 = isset($this->data[$c2]) ? $this->data[$c2] : $this->error($c2);

        $c1_probability = $this->probability($c1);
        $c2_probability = $this->probability($c2);

        $max_rand = $c1_probability+$c2_probability;

        list($c1_score, $c2_score) = 0;

        for ($i=0; $i<=$this->games($c1)+$this->games($c2); $i++) {
            rand(1, $max_rand) <= $c1_probability ? $c1_score++ : $c2_score++;
        }

        return array((int)$c1_score, (int)$c2_score);
    }

    protected function games($command) {
        return floor(($command['goals']['scored']+$command['goals']['skiped'])/$command['games']);
    }

    protected function win($command) {
        $games_percent = ($command['games']-$command['draw'])/100;
        return floor(($command['win']/$games_percent)-($command['defeat']/$games_percent));
    }

    protected function atack($command) {
        return floor($command['goals']['scored']/(($command['goals']['scored']+$command['goals']['skiped'])/100));
    }

    protected function probability($command) {
        return $this->atack($command)+$this->win($command);
    }

    protected function error($index) {
        exit('Ошибка: не найдена команда с индексом: ' . $index);
    }
}

function match($c1, $c2) {
    global $data;

    $football = new football($data);

    return $football->match($c1, $c2);
}

// Test
print_r(match(1, 6));
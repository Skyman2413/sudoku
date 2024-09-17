<?php

class SudokuSolver{
    private array $grid;


    public function getGrid(): array
    {
        return $this->grid;
    }
    public function __construct(array $a)
    {
        $this->grid = $a;
    }

    public function solve(): bool
    {
        if(!$this->validate()) return false;
        $flag = true;
        for($i = 0; $i < 9; $i ++){
            for ($j=0; $j < 9; $j++){
                if($this->grid[$i][$j] ==0){
                    $flag = false;
                    $x = $i;
                    $y = $j;
                }
            }
        }
        if($flag) return true;

        for ($i = 1; $i < 10; $i++){
            $this->grid[$x][$y] = $i;
            if($this->solve()) return true;
        }
        $this->grid[$x][$y] = 0;
        return false;
    }

    public function reverse_solve(): bool
    {
        if(!$this->validate()) return false;
        $flag = true;
        for($i = 0; $i < 9; $i ++){
            for ($j=0; $j < 9; $j++){
                if($this->grid[$i][$j] ==0){
                    $flag = false;
                    $x = $i;
                    $y = $j;
                }
            }
        }
        if($flag) return true;

        for ($i = 9; $i > 0; $i--){
            $this->grid[$x][$y] = $i;
            if($this->solve()) return true;
        }
        $this->grid[$x][$y] = 0;
        return false;
    }

    private function validate() : bool{

        if(!$this->validate_columns()){
            return false;
        }
        if(!$this->validate_regions()){
            return false;
        }
        if(!$this->validate_rows()){
            return false;
        }
        return true;
    }

    private function validate_columns() : bool
    {
        for ($i = 0; $i < 9; $i++){
            $a = [0,0,0,0,0,0,0,0,0];
            for ($j = 0; $j < 9; $j ++){
                if ($this->grid[$j][$i] == 0) continue;
                $s = $this->grid[$j][$i];
                if ($a[$s-1] != 0){
                    return false;
                }
                $a[$this->grid[$j][$i]-1]++;
            }
        }
        return true;
    }

    private function validate_rows() : bool
    {
        for ($i = 0; $i < 9; $i++){
            $a = [0,0,0,0,0,0,0,0,0];
            for ($j = 0; $j < 9; $j ++){
                if ($this->grid[$i][$j] == 0) continue;
                if ($a[$this->grid[$i][$j]-1] != 0){
                    return false;
                }
                $a[$this->grid[$i][$j]-1]++;
            }
        }
        return true;
    }

    private function validate_regions() : bool
    {
        for($i = 0; $i < 9; $i += 3){
            for($j = 0; $j < 9; $j += 3){
                $a = [0,0,0,0,0,0,0,0,0];
                for ($row = 0; $row < 3; $row ++){
                    for ($column = 0; $column < 3; $column++){
                        if($this->grid[$i + $row][$j + $column] == 0) continue;
                        if ($a[$this->grid[$i + $row][$j + $column]-1] != 0){
                            return false;
                        }
                        $a[$this->grid[$i + $row][$j + $column]-1]++;
                    }
                }
            }
        }
        return true;
    }
}

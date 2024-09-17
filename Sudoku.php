<?php
class Sudoku{
    private array $_map;
    private array $_solution;

    public function getSolution(): array
    {
        return $this->_solution;
    }


    public function getMap(): array
    {
        return $this->_map;
    }

    private function create_empty_map() : array{
        return [[0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0],
            [0,0,0,0,0,0,0,0,0]];
    }

    private function create_base_map():array{
        return [[1,2,3,4,5,6,7,8,9],
                [4,5,6,7,8,9,1,2,3],
                [7,8,9,1,2,3,4,5,6],
                [2,3,4,5,6,7,8,9,1],
                [5,6,7,8,9,1,2,3,4],
                [8,9,1,2,3,4,5,6,7],
                [3,4,5,6,7,8,9,1,2],
                [6,7,8,9,1,2,3,4,5],
                [9,1,2,3,4,5,6,7,8]];
    }
    public function __construct(int $opened, array $a = null)
    {
        if($a == null){
            $this->_map = $this->create_base_map();
            $this->generate_map($opened);
            $this->_solution=$this->_map;
            $this->hide_cells(81 - $opened);
        }
        else{
            $this->_map = $a;
        }
    }

    private function shuffle_map(int $i){
        switch ($i){
            case 0:
                    $this->transpose_matrix();
                    break;
            case 1:
                $this->swap_rows_area();
                break;
            case 2:
                $this->swap_rows_small();
                break;
            case 3:
                $this->swap_colums_area();
                break;
            case 4:
                $this->swap_colums_small();
                break;
        }
    }

    private function generate_map(int $opened){

        for($i = 0; $i < 150; $i++){
            $this->shuffle_map(rand(0,4));
        }

    }

    private function hide_cells(int $n){
        $flook = $this->create_empty_map();
        $iter = 0;
        while ($iter < $n){
            $i = rand(0,8);
            $j = rand(0,8);
            if ($flook[$i][$j] == 0){
                $iter++;
                $flook[$i][$j]++;
                $temp = $this->_map[$i][$j];
                $this->_map[$i][$j] = 0;
                $solver1 = new SudokuSolver($this->_map);
                $solver2 = new SudokuSolver($this->_map);
                $isSolved1 = $solver1->solve();
                $isSolved2 = $solver2->reverse_solve();

                if($isSolved1 && $isSolved2) {
                    $sol1 = $solver1->getGrid();
                    $sol2 = $solver2->getGrid();
                    for ($i = 0; $i < 9; $i++) {
                        for ($j = 0; $j < 9; $j++) {
                            if ($sol1[$i][$j] != $sol2[$i][$j]) {
                                $this->_map[$i][$j] = $temp;
                                $iter--;
                            }
                        }
                    }
                }
                else {
                    $this->_map[$i][$j] = $temp;
                    $iter--;
                }
            }
        }
    }

    private function swap_rows_small(){
        $ar = rand(0,2);
        $l1 = rand(0,2);
        $n = $ar*3 + $l1;
        $l2 = rand(0,2);
        while ($l1==$l2){
            $l2 = rand(0,2);
        }
        $n2 = $ar*3 + $l2;
        $buf = $this->_map[$n];
        $this->_map[$n] = $this->_map[$n2];
        $this->_map[$n2] = $buf;
    }

    private function swap_colums_small(){
        $this->transpose_matrix();
        $this->swap_rows_small();
        $this->transpose_matrix();
    }

    private function swap_rows_area(){
        $ar1 = rand(0,2);
        $ar2 = rand(0,2);
        while ($ar1==$ar2){
            $ar1 = rand(0,2);
        }
        for($i = 0; $i < 3; $i++){
            $n1 = $ar1*3 + $i;
            $n2 = $ar2*3 + $i;
            $buf = $this->_map[$n1];
            $this->_map[$n1] = $this->_map[$n2];
            $this->_map[$n2] = $buf;
        }
    }

    private function swap_colums_area(){
        $this->transpose_matrix();
        $this->swap_rows_area();
        $this->transpose_matrix();
    }


    private function transpose_matrix() {
        $tMap = $this->create_empty_map();
        for($i = 0; $i < 9; $i++){
            for ($j = 0; $j < 9; $j++){
                $tMap[$i][$j] = $this->_map[$j][$i];
                }
        }
        $this->_map =  $tMap;
    }


}
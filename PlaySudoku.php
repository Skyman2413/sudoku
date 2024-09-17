<?php
session_start();
include ("Sudoku.php");
include ("SudokuSolver.php");
$sudoku = (new Sudoku(intval($_GET['difficult']),null));
$sudmap = $sudoku->getMap();
for ($i = 0; $i < 9; $i ++){
    for($j = 0; $j < 9; $j ++){
        echo $sudmap[$i][$j] . " ";
    }
    echo "\n";
}
$sudmap = $sudoku->getSolution();
for ($i = 0; $i < 9; $i ++){
    for($j = 0; $j < 9; $j ++){
        echo $sudmap[$i][$j] . " ";
    }
    echo "\n";
}

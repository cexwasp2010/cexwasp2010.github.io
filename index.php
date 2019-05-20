<?php define('NUMBER_OF_NODES', 7);
define('NUMBER_OF_EDGES_PER_NODE', 2); 
define('IS_DIRECTED_GRAPH', true); 

$adjacentList = array(); 
fillRandomCosts($adjacentList); 
printToScreen($adjacentList); 

$special = $predecessor = array(); 
dijkstra($adjacentList, $special, $predecessor); 

echo 'FINAL> Special: '.implode('-', $special).PHP_EOL
    .'FINAL> Predecessor: '.implode('-', $predecessor).PHP_EOL;

function dijkstra($adjacentList, &$special, &$predecessor)
{
    // Fill C with not used nodes.
    $C = array();
    for ($i = 1; $i < NUMBER_OF_NODES; ++$i) {
        $C[] = $i;
    }

    // Fill special distances.
    for ($i = 1; $i < NUMBER_OF_NODES; ++$i) {
        $special[$i] = distanceFromTo($adjacentList, 0, $i);
        if ($special[$i] < INF) { 
            $predecessor[$i] = 0; 
        } else { 
            $predecessor[$i] = '#'; 
        } 
    } 
    
    echo 'INITIAL> Not used nodes: '.implode('-', $C).PHP_EOL
        .'INITIAL> Special: '.implode('-', $special).PHP_EOL
        .'INITIAL> Predecessor: '.implode('-', $predecessor).PHP_EOL;

    // Study nodes in C to update $special and predecessor vectors.
    while (count($C) > 1) {
        $v = selectNextNodeThatMinimizesSpecial($adjacentList, $C, $special);
        $C = array_diff($C, array($v));

        if ($v == -1) {
            echo 'IMPOSSIBLE TO FIND DIJKSTRA WITH ALL NODES! Cannot achieve all nodes!'.PHP_EOL;
            exit;
        }

        foreach ($C as $w) {
            if ($special[$w] > $special[$v] + distanceFromTo($adjacentList, $v, $w)) {
                $special[$w] = $special[$v] + distanceFromTo($adjacentList, $v, $w);
                $predecessor[$w] = $v;
            }
        }

        echo 'Not used nodes: '.implode('-', $C).PHP_EOL
            .'Special: '.implode('-', $special).PHP_EOL
            .'Predecessor: '.implode('-', $predecessor).PHP_EOL;
    }
}

function selectNextNodeThatMinimizesSpecial($adjacentList, &$C, &$special)
{
    $minCost = INF;
    $minNode = -1;

    for ($i = 0; $i < NUMBER_OF_NODES; ++$i) {
        foreach ($C as $node) {
            if (!in_array($i, $C)
            and isset($adjacentList[$i][$node])
            and $adjacentList[$i][$node] < $minCost) { 
                echo '>>>> Found min edge! $adjacentList['.$i.']['.$node.']='.$adjacentList[$i][$node].PHP_EOL;
                $minCost = $adjacentList[$i][$node];
                $minNode = $node;
            }
        }
    }

    echo '>>>> Next min node to use is '.$minNode.PHP_EOL;

    return $minNode;
}

function distanceFromTo($adjacentList, $from, $to)
{
    if (isset($adjacentList[$from][$to])) {
        return $adjacentList[$from][$to];
    } else {
        return INF;
    }
}

function fillRandomCosts(&$adjacentList)
{
    for ($i = 0; $i < NUMBER_OF_NODES; ++$i) {
        $added = false;
        while (!$added) {
            for ($j = 0; $j < NUMBER_OF_EDGES_PER_NODE; ++$j) {
                $adjacentNode = rand(0, NUMBER_OF_NODES - 1);
                if ($adjacentNode != $i and $adjacentNode != $j) {
                    $adjacentNodeCost = rand(1, 5);
                    $adjacentList[$i][$adjacentNode] = $adjacentNodeCost;
                    if (!IS_DIRECTED_GRAPH) {
                        $adjacentList[$adjacentNode][$i] = $adjacentNodeCost;
                    }
                    $added = true;
                }
            }
        }
    }
}
function printToScreen($adjacentList)
{
    for ($i = 0; $i < NUMBER_OF_NODES; ++$i) { echo $i; foreach ($adjacentList[$i] as $key => $value) {
            echo ' --> '.$key.'('.$value.')';
        }
        echo PHP_EOL;
    }
}

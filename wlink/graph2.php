<?php

include_once 'includes/common.php';
include_once 'includes/logic.php';
include_once 'graphviz/GraphViz.php';

Logic::load();

$graph = new Image_GraphViz();
$i = 0;

foreach ( Logic::$contacts as $contact )
{
    if ( strlen($contact->nombre) > 0 )
    {
        $nodeName = camelCase($contact->nombre);
        $nodeLabel = $contact->nombre;

        if ( strlen($contact->empresa) > 0 )
            $nodeLabel .= "\n" . $contact->empresa;
    }
    elseif ( strlen($contact->empresa) > 0 )
    {
        $nodeName = camelCase($contact->empresa);
        $nodeLabel = $contact->empresa;
    }
    else
    {
        $nodeName = "node$i";
        $nodeLabel = '(nada)';
    }

    $graph->addNode($nodeName, array(
        'label'    => $nodeLabel,
        'shape'    => 'octagon',
        'fontsize' => '8'
    ));

    if ( sizeof($contact->relations) > 0 )
    {
        foreach ( $contact->relations as $relation )
        {
            if ( $relation->meetInPerson )
                $colorRelation = 'black';
            else
                $colorRelation = 'grey';

            if ( strlen($relation->nombre) > 0 )
            {
                $nodeNameRelation = camelCase($relation->nombre);
                $nodeLabelRelation = $relation->nombre;

                if ( strlen($relation->empresa) > 0 )
                    $nodeLabelRelation .= "\n" . $relation->empresa;
            }
            elseif ( strlen($relation->empresa) > 0 )
            {
                $nodeNameRelation = camelCase($relation->empresa);
                $nodeLabelRelation = $relation->empresa;
            }
            else
            {
                $nodeNameRelation = "node$i";
                $nodeLabelRelation = '(nada)';
            }

            if ( !( $graph->hasNode($nodeNameRelation) ) )
            {
                $graph->addNode($nodeNameRelation, array(
                    'label' => $nodeLabelRelation
                ));
            }

            $graph->addEdge(array(
                $nodeName => $nodeNameRelation
            ), array(
                'color' => $colorRelation
            ));
        }
    }

    $i++;
}

$graph->image();
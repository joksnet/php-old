<?php

class Wax_Document_Body_Table_TFoot extends Wax_Document_Body_Element
{
    public function __construct()
    {
        parent::__construct('tfoot');

        $this->isContainer(true);
    }
}
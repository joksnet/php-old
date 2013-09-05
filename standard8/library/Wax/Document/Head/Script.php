<?php

include_once 'Wax/Document/Element.php';

final class Wax_Document_Head_Script extends Wax_Document_Element
{
    const TYPE_TEXT = 'text';
    const TYPE_APLICATION = 'aplication';

    const LANG_JAVASCRIPT = 'javascript';

    public function __construct( $type = self::TYPE_TEXT, $language = self::LANG_JAVASCRIPT )
    {
        parent::__construct('Script');

        $this->setTagType( Wax_Document_Element::TAG_OPEN );
        $this->setAttribute('type', $type . '/' . $language);
        # $this->setAttribute('language', $language);
    }
}
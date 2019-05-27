<?php

namespace SJRoyd\JPK\VAT;

/**
 *
 */
class Parser
{
    protected $xml_record;
    protected $xml_current_field = '';
    protected $xmlHeader;
    private $substringLimit = 1024;

    /**
     * @param string $xml
     * @return string
     */
    public function getVersion($xml)
    {
        $this->loadDataFromXml($xml);
        return $this->xmlHeader['WariantFormularza'];
    }
    /**
     * @param string $xml
     */
    protected function loadDataFromXml($xml){
        $parser = xml_parser_create('UTF-8');
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, false);
        xml_set_object($parser, $this);
        xml_set_element_handler($parser, 'xml_start_element', 'xml_end_element');
        xml_set_character_data_handler($parser, 'xml_cdata');

        if (!xml_parse($parser, substr($xml, 0, $this->substringLimit))) {
            throw new \Exception(
                sprintf(
                    'XML Parse Error: line %d, position %d, error %d (%s)',
                    xml_get_current_line_number($parser),
                    xml_get_current_column_number($parser),
                    xml_get_error_code($parser),
                    xml_error_string(xml_get_error_code($parser))
                )
            );
        }
        xml_parser_free($parser);

        foreach($this->xmlHeader as $k => $v){
            if(strpos($k, ':') !== 0){
                $this->xmlHeader[substr($k, strpos($k, ':'), strlen($k))] = $v;
            }
        }
    }


    /**
     * @param resource $p
     * @param type $text
     */
    protected function xml_cdata($p, $text){
        $str = $this->xml_current_field;
        if(strpos($str, ':') !== false){
            $str = substr($str, strpos($str, ':'), strlen($str));
        }

        if(isset($this->xml_record[$str])){
            $this->xml_record[$str] = $this->xml_record[$str] . trim($text);
        } else {
            $this->xml_record[$str] = trim($text);
        }
    }

    /**
     * @param resource $p
     * @param type $element
     * @param type $attributes
     */
    protected function xml_start_element($p, $element, &$attributes){
        $str = preg_replace('~(.+:)?(.*)~', '$2', $element);
        $this->xml_current_field = $str;
    }

    /**
     * @param resource $p
     * @param type $element
     */
    protected function xml_end_element($p, $element){
        $str = preg_replace('~(.+:)?(.*)~', '$2', $element);

        if($str == 'Naglowek'){
            $this->xmlHeader = $this->xml_record;
        }

        $this->xml_current_field = '';
    }

}

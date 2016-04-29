<?php

	require_once("filetokenizer.class.php");
	require_once("objectparser.class.php");
	require_once("accesslistparser.class.php");
	require_once("natparser.class.php");

	class NaiveParser {
		
		static function parse($uploaded_file) {
			
			$network_objects = array();
			$access_lists = array();
			$nat_rules = array();
		
			$tokenizer = FileTokenizer::getInstance($uploaded_file);
			
			while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOF_MARK) {
				
				switch ($token) {
					
					case ObjectParser::TRIGGER:
						if ($data = ObjectParser::parse()) {
							$network_objects[] = $data;
						}
						break;
						
					case AccessListParser::TRIGGER:
						if ($data = AccessListParser::parse()) {
							$access_lists[] = $data;
						}
						break;
					
					case NATParser::TRIGGER:
						if ($data = NATParser::parse()) {
							$nat_rules[] = $data;
						}
						break;
					
				}
				
			}
			
			return array('objects' => $network_objects,
						'groups' => array(),
						'acl' => $access_lists,
						'nat' => $nat_rules
						);
			
		}
	}
	
?>

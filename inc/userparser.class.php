<?php

	require_once("filetokenizer.class.php");
	require_once("commonobject.class.php");
	require_once("user.class.php");

	class UserParser {
		
		const SCOPE = "username";
		const SUBSCOPE = "attributes";
		const ATTRIBUTE_TYPES = array("vpn-group-policy", "vpn-tunnel-protocol", "service-type");
		
		static function parse() {
				
			$tokenizer = FileTokenizer::getInstance();
				
			$user = new User($tokenizer->nextToken());
			
			while (($token = $tokenizer->nextToken()) !== self::SUBSCOPE) {}
			$tokenizer->nextToken(); //move to EOL
			
			while (self::isAttributeType($token = $tokenizer->nextToken())) {
				$child_type = $token;
				$child_name = "";
					
				while (($token = $tokenizer->nextToken()) !== FileTokenizer::EOL_MARK) {
					$child_name .= $token . " ";
				}
				$user->addChild(new CommonObject(trim($child_name), $child_type));
			}
			
			$tokenizer->previousToken();
			
			return $user;
		}
		
		
		static function isAttributeType($data) {
			
			foreach (self::ATTRIBUTE_TYPES as $type) {
				if ($data === $type) {
					return true;
				}
			}
			return false;
		}
	
	}
	
?>

<?php

class vk {
	public $token 		= 'ea4fbbf93a7c446acc714cd702b5240eb13dfa0a178a53fd305990d82e38cf03571eb31108dfe160fff71&';
	public $client_id 	= 4174456;
	public $group_id	= 20824259;
	public $delta		= 100;
	
	public function post($desc, $photo, $link) {
		if( rand( 0, 99 ) < $this->delta ) {
			$data = json_decode(
				$this->execute(
					'wall.post',
					array(
						'owner_id' 		=> -$this->group_id,
						'from_group' 	=> 1,
						'message' 		=> $desc,
						'attachments' 	=> $link
					)
				)
			);
			if( isset( $data->error ) ) {
				return $this->error($data);
			}
			return $data->response->post_id;
		}
		return 0;
	}
	
	private function execute($method, $params) {
		$ch = curl_init( 'https://api.vk.com/method/' . $method . '?access_token=' . $this->token );
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		$data = curl_exec($ch);
		curl_close($ch);
		
		return $data;
	}
	
	private function error($data) {
		//обработка ошибок
		var_dump($data);
		return false;
	}
}
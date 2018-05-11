<?php
class Mdl_common extends CI_Model
{
 	
	function checkAdminSession(){		 
		if($this->session->userdata('admin_id') == "" ){
			redirect('admin/admin');
		}		  
	}
	
	function check_session(){	
		if($this->session->userdata('customer-user') == "" ){
			redirect('/');
		}
	}
	
		
    function pagiationData($str,$num,$start,$segment,$per_page='20' ){
	 
		$config['base_url'] = base_url().$str;
			
		$config['total_rows'] = $num;
		if($per_page!='')
			$config['per_page'] = $per_page;
		else {
			
			if($this->session->userdata('per_page')=='')
				$this->session->set_userdata('per_page',20);
				
			$config['per_page']=$this->session->userdata('per_page');
		}
		$config['uri_segment'] = $segment;
		$config['first_link']=false;
		$config['last_link']=false;
		$config['full_tag_open'] = '<div id="pagination" style="display:inline;">';
		$config['full_tag_close'] = '</div>';
		$this->pagination->initialize($config); 
	 
		$query = $this->db->last_query()." LIMIT ".$start." , ".$config['per_page'];
		$res = $this->db->query($query);
	
		$data['listArr'] = $res->result_array();
		$data['num'] = $res->num_rows();
		$data['links'] =  $this->pagination->create_links();
		return $data;
 
	}
	
	function per_page_drop(){
		$dropdown = array('20'=>'20 Per Page','40'=>'40 Per Page','60'=>'60 Per Page');
		return $dropdown;
	}	 
	 
	function dropDownAry($sql,$keyField,$valueField,$selectFiled="")
	{
		$dropDown = array();
		
		//if select is required in drop down
		if($selectFiled == "Y")
			$dropDown['Select'] = "Select";
		else if($selectFiled != "")
			$dropDown['Select'] = $selectFiled;
		
		$result = $this->db->query($sql);
		foreach($result->result_array() as $res)
		{
			$key = $res[$keyField];
			$dropDown[$key] = $res[$valueField];
		}
		return $dropDown;
	}
	
	function uploadFile($uploadFile,$filetype,$folder,$fileName='')
	{
		$resultArr = array();
		
		$config['max_size'] = '1024000';
		if($filetype == 'img') 	$config['allowed_types'] = 'gif|jpg|png|jpeg';
		if($filetype == 'All') 	$config['allowed_types'] = 'gif|jpg|png|jpeg|pdf|doc|docx|zip|xls|txt';
		if($filetype == 'swf') 	$config['allowed_types'] = 'swf';
		if($filetype == 'html') 	$config['allowed_types'] = 'html|htm';
		
		if($filetype == 'video') 	$config['allowed_types'] = 'csv|mp4|3gp|vob|flv';
		if($filetype == 'DOC') 	$config['allowed_types'] = 'doc|docx';
		if($filetype == 'XLS') 	$config['allowed_types'] = 'xls|xlsx';
		if($filetype == 'PPT') 	$config['allowed_types'] = 'ppt';
		if($filetype == 'PDF') 	$config['allowed_types'] = 'pdf';

		if(substr($folder,0,17)=='application/views')
			$config['upload_path'] = './'.$folder.'/';
		else
			$config['upload_path'] = './uploads/'.$folder.'/';
			
		if($fileName != "")
			$config['file_name'] = $fileName;
		
		$this->load->library('upload', $config);
		$this->upload->initialize($config);
		
		if(!$this->upload->do_upload($uploadFile))
		{
			$resultArr['success'] = false;
			$resultArr['error'] = $this->upload->display_errors();
		}	
		else
		{
			$resArr = $this->upload->data();
			$resultArr['success'] = true;
			if(substr($folder,0,17)=='application/views')
				$resultArr['path'] = $folder.'/'.$resArr['file_name'];
			else
				$resultArr['path'] = "uploads/".$folder."/".$resArr['file_name'];
		}
		return $resultArr;
	}
 
	

	function sendMail($to,$from,$subject,$message,$attachmentName='',$attachmentFiled='')
	{
        if($_SERVER['HTTP_HOST'] == 'localhost'){
                
			require_once "dSendMail2.inc.php";			  
			$m = new dSendMail2;
			
			$from = 'ds.sparkle018@gmail.com';
			$m->setTo($to);
			
			//'"Destinatario" <no-reply@cred3.com>';
			//$m->setFrom($from,'akash.italiya@artoonsolutions.com'); /*akash*/
			$m->setFrom($from,'Keatlog'); 
			$m->setSubject($subject);
			$m->setMessage($message);     		 
			
			 if($attachmentFiled){ 
				// Attach the file.
				$m->autoAttachFile($attachmentName, file_get_contents($attachmentFiled));
			 }
			
			// Real GMail example: 
          
			$m->sendThroughSMTP("smtp.gmail.com", 465, 'ds.sparkle018@gmail.com', 'sparkle1#', true);/*akash*/ 
			$m->send();  
			
		} else {
			
			//headers
			$headers = "MIME-version: 1.0\n";
			$headers.= "Content-type: text/html; charset= iso-8859-1\n"; 
			$headers .= "From: Cybella Applications <".$from .">\n"; 
					
			mail($to,$subject,$message,$headers);
		}
	
	}
        
        
        function sendAndroidNotification($message, $deviceTokens, $pushType = array(), $data = array(), $userInfo = array()) {

        define('GOOGLE_API_KEY', '');  // - android dev changed server

        $pushData = array(
            'hangMessage' => $message,
            'pushType' => $pushType,
            'data' => $data
        );

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' => array($deviceTokens),
            'data' => $pushData
        );
        $headers = array(
            'Authorization:key=' . GOOGLE_API_KEY,
            'Content-Type:application/json'
        );


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        $result = curl_exec($ch);
        //print_r($result);
        if ($result === false) {
            //echo('Curl failed ' . curl_error());
        }

        curl_close($ch);
    }
    function sendIosNotification($message, $deviceTokens, $pushType = array(), $data = array(), $userInfo = array()) {   
        
        // --- For testing ----
        //$pemFile = 'pushcertDev.pem';  //Testing Sandbox
        //$appleUrl = 'ssl://gateway.sandbox.push.apple.com:2195'; //Testing Sandbox
        
        $pemFile = 'pushcertProd.pem';
        $appleUrl = 'ssl://gateway.push.apple.com:2195';

        $passphrase = '';

        $ctx = stream_context_create();
        stream_context_set_option($ctx, 'ssl', 'local_cert', $pemFile);
        stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client($appleUrl, $err, $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);


        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);

  
        $notification_count = 1;


        // Create the payload body
        $body['aps'] = array(
            'alert' => $message,
            'badge' => $notification_count,
            'sound' => 'default',
            'pushType' => $pushType,
            'data' => $data
        );


        // Encode the payload as JSON
        $payload = json_encode($body);
        $payload = str_replace('\\\\', urldecode('%5C'), $payload);

        // Build the binary notification  
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceTokens) . pack('n', strlen($payload)) . $payload;
        $resultIOS = fwrite($fp, $msg, strlen($msg));

        if (!$resultIOS) {
            //echo 'Message not delivered' . PHP_EOL;
        } else {
            //echo 'Message successfully delivered' . PHP_EOL;
        }
        // Close the connection to the server
        fclose($fp);
    }
}

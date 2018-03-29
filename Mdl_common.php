<?php
class Mdl_common extends CI_Model
{
 	
	function checkAdminSession(){		 
		if($this->session->userdata('admin_id') == "" ){
			redirect('admin/admin');
		}		  
	}
	
	function check_session(){	

		if($this->session->userdata('eb-user') == "" ){
			redirect('/');
		}else{
			
			$accountArr = $this->session->userdata('eb-user');
			//pr($accountArr);
			if($accountArr['user_service_type'] == '0'){
				
                $usertype = 'provider';
                if($this->uri->segment(1) == 'seeker'){
            		redirect('/');
            	}
            }else{

                $usertype = 'seeker';
                if($this->uri->segment(1) == 'provider'){
            		redirect('/');
            	}
            }
	           if($accountArr['user_setup'] == 'STEP1' && $accountArr['user_service_type'] == '0'){
                
	           		if($this->uri->segment(2) != 'profile'){
	            		redirect(''.$usertype.'/profile');
	            	}
	        	}else if($accountArr['user_setup'] == 'STEP2' && $accountArr['user_service_type'] == '0'){

	        		if($this->uri->segment(2) != 'taxid' && $this->uri->segment(2) != 'profile'){
	            		redirect(''.$usertype.'/taxid');
	            	}
	        	}else if($accountArr['user_setup'] == 'STEP3' && $accountArr['user_service_type'] == '0'){
	        		if($this->uri->segment(2) != 'paymentinformation' && $this->uri->segment(2) != 'taxid' && $this->uri->segment(2) != 'profile'){
	            		redirect(''.$usertype.'/paymentinformation');
	            	}
	        	}else if($accountArr['user_setup'] == 'STEP4' && $accountArr['user_service_type'] == '0'){
	        		if($this->uri->segment(2) != 'securityquestion' && $this->uri->segment(2) != 'paymentinformation' && $this->uri->segment(2) != 'taxid' && $this->uri->segment(2) != 'profile'){
	            		redirect(''.$usertype.'/securityquestion');
	            	}
	        	}

                if($accountArr['user_setup'] == 'STEP1' && $accountArr['user_service_type'] == '1'){

                    if($this->uri->segment(2) == 'securityquestion' || $this->uri->segment(2) == 'paymentinformation' || $this->uri->segment(2) == 'taxid'){
                        redirect(''.$usertype.'/profile');
        	}
                }else if($accountArr['user_setup'] == 'STEP3' && $accountArr['user_service_type'] == '1'){
                    if($this->uri->segment(2) == 'taxid' || $this->uri->segment(2) == 'securityquestion'){
                        redirect(''.$usertype.'/paymentinformation');
	}
                }else if($accountArr['user_setup'] == 'STEP4' && $accountArr['user_service_type'] == '1'){
                    if($this->uri->segment(2) == 'taxid' || $this->uri->segment(2) == 'changepassword' || $this->uri->segment(2) == 'notificationsetting'){
                        redirect(''.$usertype.'/securityquestion');
                    }
                }
        	}
	}
	function check_user_type(){
		$accountArr = $this->session->userdata('eb-user');
		if($accountArr['user_service_type'] == 0){
            if($this->uri->segment(1) == 'seeker'){
            	redirect('/');
            }
        }else{
             if($this->uri->segment(1) == 'provider'){
            	redirect('/');
            }
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
        
        function sendNotification($seeker='',$provider='',$job,$sendTo,$sentby,$type,$status='',$toWhome,$job_id){
            //echo $toWhome; 
          //  1=seeker;  0=provider
            if($toWhome == '1' || $toWhome == 'seeker'){
                $seekerId = $sendTo;
                $providerId = $sentby;
                if($type == 'post_jon_in_area'){
                    $title= 'The offered service \''.$job.'\' was just posted in your area!';
                }
                else if($type == 'job_approve_decline' && $status == 'accepted'){
                    $title = $provider.' approved your service request for \''.$job.'\'.';
                }
                else if($type == 'job_approve_decline' && $status == 'decline'){
                    $title = $provider.' declined your service request for \''.$job.'\'.';
                }
                else if($type == 'requested_job_close'){
                    $title = $provider.' closed their service \''.$job.'\'.';
                }
                else if($type == 'receive_service_request'){
                    $title = $provider.' requested your offered service \''.$job.'\'.';
                }
                else if($type == 'schedule_submitted'){
                    $title = $provider.' submitted a schedule for \''.$job.'\'.';
                }
                else if($type == 'confirm_decline_schedule' && $status == 'accepted'){
                    $title = $provider.' approved your schedule for \''.$job.'\'.';
                }
                else if($type == 'confirm_decline_schedule' && $status == 'decline'){
                    $title = $provider.' declined your schedule for \''.$job.'\'.';
                }
                else if($type == 'ended_engagement'){
                    $pieces = explode(" ", $provider);
                    $title = $provider.' resigned. '.$pieces[0].' will no longer be working on \''.$job.'\'.'; //You
                }
                else if($type == 'rating_review'){
                    $title = $provider.' rated and reviewed their experience working with you!'; 
                }
                else if($type == 'job_completion'){
                    $title = $provider.' completed the service \''.$job.'\'.'; 
                }
                else if($type == 'payment_reminder'){
                    $title = $provider.' sent you a payment reminder for the service \''.$job.'\'.'; 
                }
                else if($type == 'filed_dispute'){
                	$title=$provider.' filed a dispute claim for the service \''.$job.'\'.';
                }
                else if($type == 'cancel_dispute'){
                	$title=$provider.' canceled their dispute claim for the service \''.$job.'\'.';
                }
                else if($type == 'resolve_dispute'){
                	$title=$provider.' resolved their dispute claim for the service \''.$job.'\'.';
                }
                else if($type == 'Enable_Pay_Option'){
                	$title=$provider.' enabled you to edit the price for the completed service \''.$job.'\'.';
                }
                else if($type == 'hide_post'){
                    $title=$provider.' closed their service \''.$job.'\'.';   
                }
                else if($type == 'transaction_fail')
                {
                    $title=' Your automatic payment failed for the following service: \''.$job.'\'.';
                }
                else if($type == 'send_dispute_msg' && $status == 'MATCHED')
                {
                     $title=$provider.' sent a reply on your dispute claim.';
                }
                else if($type == 'send_dispute_msg' && $status == 'NOT MATCHED')
                {
                     $title=$provider.' sent a reply on their dispute claim.';
                }
                else if($type == 'custom_refundpayment' && $status == 'history')
                {
                     $title=$provider.' sent you a refund for the service they provided.';
                }
                 else if($type == 'custom_refundpayment' && $status == 'dispute')
                {
                     $title=$provider.' sent you a refund for the service they provided.';
                }
            
            }
            else if($toWhome == '0' || $toWhome == 'provider'){
                $seekerId = $sendby;
                $providerId = $sentTo;
                if($type == 'post_jon_in_area'){
                    $title= 'The wanted service '.$job.' was just posted in your area!';
                }
                else if($type == 'job_approve_decline' && $status == 'accepted'){
                    $title = $seeker.' hired you for their wanted service \''.$job.'\'.';
                }
                else if($type == 'job_approve_decline' && $status == 'decline'){
                    $title = $seeker.' declined your offered service for \''.$job.'\'.';
                }
                else if($type == 'requested_job_close'){
                    $title = $seeker.' closed their wanted service \''.$job.'\'.';
                }
                else if($type == 'receive_service_request'){
                    $title = $seeker.' offered to provide your wanted service \''.$job.'\'.';
                }
                else if($type == 'schedule_submitted'){
                    $title = $seeker.' submitted a schedule for \''.$job.'\'.';
                }
                else if($type == 'confirm_decline_schedule' && $status == 'accepted'){
                    $title = $seeker.' approved your schedule for \''.$job.'\'.';
                }
                else if($type == 'confirm_decline_schedule' && $status == 'decline'){
                    $title = $seeker.' declined your schedule for \''.$job.'\'.';
                }
                else if($type == 'hold_job' && $status == 'hold'){
                    $title = $seeker.' put the service \''.$job.'\' on hold.';
                }
                else if($type == 'ended_engagement'){
                    $title = $seeker.' ended your agreement. You will no longer be working on  \''.$job.'\'.';
                }
                else if($type == 'payment_issue'){
                    $title = 'You received payment from '.$seeker.' for your completed service \''.$job.'\'.';
                }
                else if($type == 'rating_review'){
                    $title = $seeker.' rated and reviewed their experience working with you!'; 
                }
                else if($type == 'rehire'){
                    $title = $seeker.' has rehire you for a job.'; 
                }
                else if($type == 'payment_dispute'){
                    $title = $seeker.' has dispute you for a job \''.$job.'\'.'; 
                }
                 else if($type == 'filed_dispute'){
                	$title=$seeker.' filed a dispute claim for the service \''.$job.'\'.';
                }
                 else if($type == 'cancel_dispute'){
                	$title=$seeker.' canceled their dispute claim.';
                }
                else if($type == 'resolve_dispute'){
                	$title=$seeker.' resolved their dispute claim for the service \''.$job.'\'.';
                }
                else if($type == 'diff_pay'){
                	$title=$seeker.' sent a payment and resolved the dispute claim filed against you.';
                }
                else if($type == 'custom_pay'){
                	$title=' You received payment from '.$seeker.' for your completed service \''.$job.'\'.';
                }
                else if($type == 'transaction_fail')
                {
                    $title=' Transaction Failed.  '.$seeker.' owes you payment for the service you provided.';
                }
                else if($type == 'custom_payment' && $status == 'BONUS'){ 
                    $title=$seeker.' sent you a bonus for your work on \''.$job.'\'.  Well done!';
                }
                 else if($type == 'custom_payment' && $status == 'REIMBURSEMENT'){
                    $title=$seeker.' sent you a reimbursement.';
                }
                else if($type == 'custom_payment' && $status == 'SEND_CUSTOM_PAYMENT'){
                    $title=$seeker.' sent you an additional payment for your work on \''.$job.'\'.';
                }
                else if($type == 'payment_refund'){
                    $title=$seeker.' removed the hold on \''.$job.'\'. You can now resume working on this service.';   
                }
                else if($type == 'hold_job' && $status == 'resume'){
                    $title=$seeker.' removed the hold on \''.$job.'\'. You can now resume working on this service.';   
                }
               else if($type == 'send_dispute_msg' && $status == 'MATCHED')
                {
                     $title=$seeker.' sent a reply on your dispute claim.';
                }
                else if($type == 'send_dispute_msg' && $status == 'NOT MATCHED')
                {
                     $title=$seeker.' sent a reply on their dispute claim.';
                }


            }
            //date_default_timezone_set('UTC');
            $notification['notification_title'] = $title;
            $notification['notification_sent_to'] = $sendTo;
            $notification['notification_sentby'] = $sentby;
            $notification['notification_type'] = $type;
            $notification['notification_flag'] = 0;
            $notification['job_id'] = $job_id;
			$notification['notification_datetime'] = date('Y-m-d H:i:s');           
           //print_r($notification);
            
            $this->db->insert('eb_notification', $notification);
            $notification_id = $this->db->insert_id();

            $userArr = dbQueryRow('eb_user',array('user_id'=>$sendTo));
            if($userArr['mobile_token_id'] != '' && $userArr['mobile_notify'] == 1){
               
                if($userArr['mobile_token_type'] == 0){
                     
                        $this->sendAndroidNotification($title,$userArr['mobile_token_id'],$type,array('notification_id'=>$notification_id,'jobId'=>$job_id,'provider_job'=>'','provider_id'=>$providerId)); //,'seeker_id'=>$seekerId   
                   
                }
                if($userArr['mobile_token_type'] == 1){
                    
                        $this->sendIosNotification($title,$userArr['mobile_token_id'],$type,array('notification_id'=>$notification_id,'jobId'=>$job_id,'provider_job'=>'')); //,'seeker_id'=>$seekerId
                   
                }
            }
        }
        
        //calender details
        function getCalenderDetails($userType,$userId,$date=''){
            $ndate=date('Y-m-d',strtotime($date));
            //to get dot on schedule add this querys.
            //status='SCHEDULING_PENDING' or status='SCHEDULING_ACTION_REQ' or
            if($userType == 'provider'){
                $getJobQuery="select * from eb_services_job where del_in=0  and sj_id in (select sj_id from eb_services_job_application where user_id='".$userId."' and (status='ONGOING_IN_PROGRESS')) and '".$ndate."' between date(STR_TO_DATE(schedule_start_date,'%m/%d/%Y')) and date(STR_TO_DATE(schedule_end_date,'%m/%d/%Y')) ";
                
            }
            else{
                //and '".$date."' between schedule_start_date and schedule_end_date;
                $getJobQuery="select * from eb_services_job where sj_id in(select sj_id from eb_services_job_application where (status='ONGOING_IN_PROGRESS')) and user_id=$userId and del_in=0 and '".$ndate."' between STR_TO_DATE(schedule_start_date,'%m/%d/%Y') and STR_TO_DATE(schedule_end_date,'%m/%d/%Y') ";
                
            }
            $data=$this->db->query($getJobQuery);
            $result=$data->result_array();
            return $result;
            
        }
        
        function getCount($userType,$userId,$date=''){
            $ndate=date('Y-m-d',strtotime($date));
            //to get dot on schedule add this querys.
            //status='SCHEDULING_PENDING' or status='SCHEDULING_ACTION_REQ' or 
            if($userType == 'provider'){
                $getJobQuery="select count(*) from eb_services_job where del_in=0  and sj_id in (select sj_id from eb_services_job_application where user_id='".$userId."' and (status='ONGOING_IN_PROGRESS')) and '".$ndate."' between date(STR_TO_DATE(schedule_start_date,'%m/%d/%Y')) and date(STR_TO_DATE(schedule_end_date,'%m/%d/%Y')) ";
            }
            else{
                //and '".$date."' between schedule_start_date and schedule_end_date;
                $getJobQuery="select count(*) from eb_services_job where sj_id in(select sj_id from eb_services_job_application where (status='ONGOING_IN_PROGRESS')) and user_id=$userId and del_in=0 and '".$ndate."' between STR_TO_DATE(schedule_start_date,'%m/%d/%Y') and STR_TO_DATE(schedule_end_date,'%m/%d/%Y') ";
                
            }
          
            $data=$this->db->query($getJobQuery);
            $result=$data->row_array();
            return $result;
        }
        function sendAndroidNotification($message, $deviceTokens, $pushType = array(), $data = array(), $userInfo = array()) {

        define('GOOGLE_API_KEY', 'AIzaSyBJUtXEtmvGkLa5lTbQyWC9CDCRJuqqGyE');  // - android dev changed server
        ///FOR ANDROID  server key : AIzaSyCL5W-M17f053EFtZlcThNB6p5iraLpTlo

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

        // -------- For live -------
         //midlal_production_push.pem
        //$appleUrl = 'ssl://gateway.push.apple.com:2195'; //ssl://gateway.push.apple.com:2195

        // --- For testing ----
        //$pemFile = 'midlal_development_push.pem';  
        
        
        // --- For testing ----
        //$pemFile = 'pushcertDev.pem';  //Testing Sandbox
        //$appleUrl = 'ssl://gateway.sandbox.push.apple.com:2195'; //Testing Sandbox
        
        $pemFile = 'pushcertProd.pem';
        $appleUrl = 'ssl://gateway.push.apple.com:2195';

        $passphrase = 'ws@@2015';

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



<!DOCTYPE html>
<html>
<head>
	<title>Tiny Chat..</title>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">
	<style>
		.bubble-recv
		{
			position: relative;
			width: 330px;
			height: 75px;
			padding: 10px;
			background: #AEE5FF;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
			border: #000000 solid 1px;
			margin-bottom: 10px;  
		}
		.bubble-recv:after
		{
			content: '';
			position: absolute;
			border-style: solid;
			border-width: 15px 15px 15px 0;
			border-color: transparent #AEE5FF;
			display: block;
			width: 0;
			z-index: 1;
			left: -15px;
			top: 12px;
		}
		.bubble-recv:before
		{
			content: '';
			position: absolute;
			border-style: solid;
			border-width: 15px 15px 15px 0;
			border-color: transparent #000000;
			display: block;
			width: 0;
			z-index: 0;
			left: -15px;
			top: 12px;

		}
		.bubble-sent
		{
			position: relative;
			width: 330px;
			height: 75px;
			padding: 10px;
			background: #00E500;
			-webkit-border-radius: 10px;
			-moz-border-radius: 10px;
			border-radius: 10px;
			border: #000000 solid 1px;
			margin-bottom: 10px;
		}

		.bubble-sent:after
		{
			content: '';
			position: absolute;
			border-style: solid;
			border-width: 15px 0 15px 15px;
			border-color: transparent #00E500;
			display: block;
			width: 0;
			z-index: 1;
			right: -15px;
			top: 12px;
		}
		.bubble-sent:before
		{
			content: '';
			position: absolute;
			border-style: solid;
			border-width: 15px 0 15px 15px;
			border-color: transparent #000000;
			display: block;
			width: 0;
			z-index: 0;
			right: -15px;
			top: 12px;
		}
		.spinner
		{
			display: inline-block;
			opacity: 0;
			width: 0;
			-webkit-transition: opacity 0.25s, width 0.25s;
			-moz-transition: opacity 0.25s, width 0.25s;
			-o-transition: opacity 0.25s, width 0.25s;
			transition: opacity 0.25s, width 0.25s;
		}
		.has-spinner.active
		{
			cursor: progress;
		}

		.has-spinner.active.spinner
		{
			opacity: 1;
			width: auto;
		}
		.has-spinner.btn-mini.active.spinner
		{
			width: 10px;
		}
		.has-spinner.btn-small.active.spinner
		{
			width: 13px;
		}
		.has-spinner.btn.active.spinner
		{
			width: 16px;
		}
		.has-spinner.btn-large.active.spinner
		{
			width: 19px;
		}
		.panel-body
		{
			padding-right: 35ps;
			padding-left: 35px;
			background-color: #000000;
		}
	</style>
</head>
<body style="background-color: black;">
<?php 
    session_start();
    $profile_pic=$_SESSION['profile'];
    $name_pic=$_SESSION['user_name'];
    require('backend/functions.php');
    if(isset($_GET['msg']))
    {
        $msg=$_GET['msg'];
        if($msg==$_SESSION['user_ID'])
        {
            header("location:home_page.php");
            exit;
        }

     $name=get_name_by_user_id($msg);
     $profile=get_profile_pic_by_id($msg);
     $banner=get_banner_pic_by_id($msg);
     $_SESSION['sent_to']=$msg;
     echo '<input type="hidden" id=msg value='.$msg.'>';
     $relation=get_relation($_SESSION['user_ID'],$msg);    

    }
    else
        exit;
?>

		
		<?php echo '<h1 style="text-align: center; background-color: yellow;">'.$name.'</h1>';?>
	<h1 color="white" align="center" >Trial Version of FindSol chat: text Only</h1>

  <div>
  	<header style="background-image: url(hacker_2.jpg)">
	<div class="container">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h2 class="panel-title">Let's Chat</h2>
			</div>
			<div class="panel-body" id="chatPanel">			
			</div>
			<div class="panel-footer">
				<div class="input-group">
					<input type="text" class="form-control" id="chatMessage" placeholder="send a message here"/>
					<span class="input-group-btn">
						<button id="sendMessageBtn" class="btn btn-primary has-spinner" type="button">
							<span class="spinner"><i class="icon-spin icon-refresh"></i>
								</span>send
							
						</button>
						
					</span>
					
				</div>
				
			</div>
			
		</div>
		
	</div>
</header>
</div>
	<script src="//code.jquery.com/jquery-2.2.3.min.js"></script>
	<script type="text/javascript">
		
		alert("chat server has been intiated...ENJOY");

// chat_get server will get old chats and chat_rec will save new chat in the database;
var pollserver = function() // this function will be repeatedly call on this page so that we can get new chats 
{
	//alert('pollserver is called');
	$.get('backend/chat_backend.php',function(result)    //getting messages from chats
	{
		if(!result.success)
		{
			//console.log("ERROR FOR NEW:::");
			return;
		}
		//alert("pollserver got a result");
		$.each(result.messages,function(idx)
		{
			var chatBubble;
			//alert('this is nice');
			if(this.sender=='self')
			{
				//alert('this is now');
				chatBubble=$('<div class ="row bubble-sent pull-right">'+this.msg+'<br/></div><div class="clearfix"></div>');
			}	
			else  //If message is send by other it will be append to left side
			{
				//alert('how is this ');
				chatBubble=$('<div class="row bubble-recv">'+this.msg+'<br/> by</div><div class="clearfix"></div>');
			}
			//alert('this is nice');
			$('#chatPanel').append(chatBubble);
		});
		setTimeout(pollserver,5000); //checking for new messages// CAlling repeatedly
	});
}


var begin = function()   //loading preveous chat // only one time it will be called 
{
	$.get('backend/chat_backend.php',function(result)
	{
		if(!result.success)
		{
			console.log("ERROR FOR NEW:::");
			return;
		}
		$.each(result.oldmessages,function(idx)
		{
			var chatBubble;
			if(this.sender=='self')
			{
				chatBubble=$('<div class ="row bubble-sent pull-right">'+this.msg+'<br/></div><div class="clearfix"></div>');
			}
			else
			{
				chatBubble=$('<div class="row bubble-recv">'+this.msg+'<br/></div><div class="clearfix"></div>');
			}
			$('#chatPanel').append(chatBubble);
		});
	});
}



$(document).on('ready',function()
{
	pollserver();
	begin();
	$('button').click(function()
	{
		$(this).toggleClass('active');
	});
});


$('#sendMessageBtn').on('click',function(event)  //Working of send message button 
{
	event.preventDefault();
	var url = $('#sendMessageBtn').attr('id');
	var message=$('#chatMessage').val();
	//alert(message);
	$.post('backend/chat_backend.php',{'message' :message},function(result)  // Posting message to the chat server (chat_rec.php)
	{
		$('#sendMessageBtn').toggleClass('active');
		if(!result.success)
		{
			alert("AN ERROR");
		}
		else
		{
			//console.log("Message_sent");
			$('#chatMessage').val('');
		}
	});
});
	</script>
</body>
</html>
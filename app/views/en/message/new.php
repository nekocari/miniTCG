<h1>Messages</h1>

<div class="text-right"><a class="btn btn-dark" href="<?php echo Routes::getUri('messages_recieved')?>">go to inbox</a></div>

<form name="new-message" method="POST" action="">
    <div class="card my-4">
    	<div class="card-header">
    		New Message
    	</div>
    	<div class="card-body">
        	<div class="row">
        		<div class="col-4">Recipient</div>
        		<div class="col-8">
        			<select class="form-control" name="recipient" required>
        				<?php foreach($recipients as $recipient){ if($recipient->getId() != $this->login->getUser()->getId()) { ?>
        					<option value="<?php echo $recipient->getId(); ?>" 
        					<?php if(isset($_GET['member_id']) AND $recipient->getId() == $_GET['member_id']){ echo 'selected'; }?>>
        						<?php echo $recipient->getName(); ?>
        					</option>
        				<?php } } ?>
        			</select>
        		</div>
        	</div>
        	<hr>
        	<div class="my-2">Your Message:</div>
        	<textarea class="form-control" rows="8" style="max-height: 75vh;" name="text" placeholder="schreib etwas..." required><?php 
        	if(isset($message)){ echo PHP_EOL.PHP_EOL.'- - - - -   '.PHP_EOL.'  **'.$message->getSender()->getName().':**  '.PHP_EOL.$message->getTextPlain(); }
        	?></textarea>
        	<small>You can use <a href="https://de.wikipedia.org/wiki/Markdown#Auszeichnungsbeispiele" target="_blank">Markdown</a>. HTML is not allowed!</small>
    	</div>
    </div>
    
    <div class="text-center">
    	<button class="btn btn-dark" type="reset" name="action" value="reset">reset</button>
    	&bull;
    	<button class="btn btn-primary" type="submit" name="action" value="submit">send</button>
    </div>
    
    <input type="hidden" name="sender" value="<?php echo $this->login->getUser()->getId(); ?>">
</form>
				</div>
				
				<!-- SIDEBAR -->
				<div class="col-12 col-lg-3" id="nav">
                    

            			<?php if(!Login::loggedIn()) { ?>
            			<!-- Navigation if NOT logged in -->
            			<div class="card my-4">
            				<div class="card-header">Hallo <b>Gast</b></div>
        					<div class="list-group list-group-flush">
                				<a class="list-group-item" href="<?php echo ROUTES::getUri('signup');?>">Registrierung</a>
                				<a class="list-group-item" href="<?php echo ROUTES::getUri('signin');?>">Einloggen</a>
                			</div>
            			</div>
            			
            			<?php }else{ ?>
            			<!-- Navigation if logged in -->
            			<div class="card my-4">
            				<div class="card-header" data-toggle="collapse" data-target="#member-links" aria-expanded="false" aria-controls="member-links">Hallo <b><?php echo Login::getUser()->getName(); ?></b></div>
        					<div class="list-group list-group-flush collapse" id="member-links">
        						<a class="list-group-item" href="<?php echo ROUTES::getUri('member_dashboard');?>">Mitgliedsbereich</a>
                				<a class="list-group-item" href="<?php echo ROUTES::getUri('messages_recieved');?>">Nachrichten (<?php echo $tcg_notifications->getMessageCountNew(); ?>)</a>
                				<a class="list-group-item" href="<?php echo ROUTES::getUri('trades_recieved');?>">Tauschanfragen (<?php echo $tcg_notifications->getTradeCountNew(); ?>)</a>
                				<a class="list-group-item" href="<?php echo ROUTES::getUri('signout');?>">Ausloggen</a>
        					</div>
            			</div>
            			
    					<?php if(count(Login::getUser()->getRights()) > 0){ ?>
            			<!-- Link to ACP if user has any special rights -->
    					<div class="my-4">
    						<a class="btn btn-block btn-primary text-center" href="<?php echo ROUTES::getUri('admin_dashboard');?>">Administation</a>
    					</div>
    					<?php } ?>
            				
            			<?php } ?>
            			
            			<div class="card my-4">
            				<div class="card-header" data-toggle="collapse" data-target="#members-online" aria-expanded="false" aria-controls="members-online">
            					Mitglieder online (<?php echo count($tcg_members_online->getOnlineMembers()); ?>)</div>
            				<div class="card-body collapse" id="members-online">
            					<?php $tcg_members_online->display(); ?>
            				</div>
            			</div>
            			
            		</div>
        			
        		
			</div>
			
			<!-- Footer -->
			<div class="text-center">
    			<p><hr>
    				<a href="https://github.com/nekocari/miniTCG/" target="_blank">miniTCG</a> by <a href="https://www.heavenspell.de" target="_blank">Cari</a>
    			</p>
			</div>
		
		</div>  
		
		

	</body>

</html>
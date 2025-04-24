			</main>
			<!-- Content end -->
    		
    		<footer id="footer" class="text-center border-top">
				<small class="d-block">
					<a href="<?php echo Routes::getUri('imprint'); ?>">Imprint</a><br> 
					powered by <a href="https://github.com/nekocari/miniTCG/" target="_blank">miniTCG <small><?php echo APP_VERSION; ?></small></a>
				</small>
			</footer>
			
			<?php if(($mo_count = count($mo = MemberOnline::getVisible($this->login->getUser()))) > 0){ ?>
	        	<div id="members-online" class="dropup">
					<a class="dropdown-toggle btn btn-dark" data-bs-toggle="dropdown" aria-expanded="false">
						<i class="fas fa-user"></i> <?php echo $mo_count; ?> Members online
					</a>
					<div class="dropdown-menu dropdown-menu-end text-center">
						<?php foreach($mo as $m){ ?>
							<a class="btn text-decoration none" href="<?php echo $m->getMember()->getProfilLink(); ?>">
								<i class="far fa-user"></i> <?php echo $m->getMember()->getName(); ?>
							</a>
						<?php }  ?> 
					</div>	 
	        	</div>
			<?php } ?>
    	</div>
    	
    	
    	
    	<!-- custom jquery and javascript -->
    	<?php echo $this->getJsLinks(); ?>
    	
    	<script>
    		function toggleSidebar(){
    			navElement = document.getElementById('sidebar');
    			navElement.classList.toggle('visible');
    			return false;
    		}
    	</script>
			
	</body>
</html>
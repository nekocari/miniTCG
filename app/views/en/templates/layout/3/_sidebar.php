		<!-- Sidebar Navigation -->
		<nav id="sidebar" class="border-end text-bg-dark">
			<!-- Sidebar Close Button -->
			<div id="sidebar-close">
				<a class="btn btn-outline-light" href="#" onclick="return toggleSidebar()">
					<i class="fas fa-times"></i>
				</a>
			</div>
			<div id="logo"class="text-center mb-4">
				<!-- L O G O -->
				<span class="rounded-circle bg-secondary bg-opacity-25 d-inline-block" style="font-size: 3.5rem; width: 6rem; padding: 1.25rem 0; aspect-ratio: 1;">
					<i class="fas fa-seedling"></i>
				</span>
			</div>
			<div id="brand" class="text-center mb-4">
				<!-- B R A N D (home link) -->
				<a class="brand" href="<?php echo BASE_URI; ?>">
					<?php 	if(($name = Setting::getByName('app_name')->getValue()) != 'miniTCG'){ echo $name; }
						else{ ?>mini<span class="text-primary fw-bold">TCG</span><br>
						<small><?php echo APP_VERSION; ?></small>
					<?php } ?>
				</a>
			</div>
			
			<!-- M A I N  Links -->
			<div class="nav-header">
				Main
			</div>
			<!-- link color is changeable if you like for each ul.nav element -->
			<ul class="nav" style="--nav-link-color: var(--bs-gray-400); --nav-link-color-hover: var(--bs-gray-100)">
				<li class="nav-item">
					<a class="nav-link" href="<?php echo Routes::getUri('home');?>">
						<i class="nav-link-icon fas fa-home text-primary"></i> Home</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo Routes::getUri('member_index');?>">
						<i class="nav-link-icon fas fa-users text-primary"></i> Members</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="<?php echo Routes::getUri('faq');?>">
						<i class="nav-link-icon fas fa-question-circle text-primary"></i> F.A.Q</a>
				</li>
			</ul>
			
			<div class="nav-header">Cards</div>
			<ul class="nav" style="--nav-link-color: var(--bs-gray-400); --nav-link-color-hover: var(--bs-gray-100)">
				<!-- main category links -->
				<?php if(count($tcg_categories = Category::getAll(['name'=>'ASC'])) > 0){ foreach($tcg_categories as $tcg_category){ ?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo $tcg_category->getLinkUrl();?>">
							<i class="nav-link-icon fas fa-folder opacity-25"></i> <?php echo $tcg_category->getName();?></a>
					</li>
				<?php } } ?>
				<!-- upcoming link -->
				<?php if(Setting::getByName('cards_decks_upcoming_public')->getValue() == 1){ ?>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo Routes::getUri('decks_list_upcoming');?>">
							<i class="nav-link-icon far fa-folder opacity-25"></i> Upcoming Decks</a>
					</li>
				<?php } ?>
				<!-- full list link -->
				<li class="nav-item">
					<a class="nav-link" href="<?php echo Routes::getUri('deck_index');?>">
						<i class="nav-link-icon fas fa-folder-plus opacity-25"></i> All Decks</a>
				</li>
			</ul>
		</nav>
		
<h1>Important Classes</h1>
<h2>and their methodes</h2>

<p>Static methods use the double colon syntax <code>::</code>. They can be used anywhere in the Code.<br>
Methods that use the arrow <code>-></code> can only be used on instances of an object.</p>

<p>For example:<br>
<code>$member = Member::getById(1);</code> Fetches date for the member with the ID 1 fron the Database
and stores an instance of a Member object in the variable <code>$member</code>, you can work with<br>
<code>echo $member->getName();</code> retuns the name of the member with ID 1 </p>

<?php 
$class_names= ["Carddeck","Card","Trade","Member","Level","Tradelog","Message"];

foreach($class_names as $class_name){
	echo '<hr><details class="class-ref">';
	echo '<summary>' . $class_name . '</summary>';
	echo '<ul id="method-reference">';
	$reflection = new ReflectionClass($class_name);
	foreach($reflection->getMethods() as $method){
		if($method->getName() != '__construct'){
			$ref_method = new ReflectionMethod($class_name,$method->getName());
			if($ref_method->isStatic() OR substr($method->getName(), 0,3) == 'get'){
				echo '<li><span class="monospace">'. $class_name ;
				echo (!$ref_method->isStatic())? '->' : '::';
				echo $method->getName().'(';
				if(count($method->getParameters())){ 
					$param_str = '';
					foreach($method->getParameters() as $param){
						$param_str.= '$'.$param->getName();
						if($param->isDefaultValueAvailable()){
							$param_str.='<span class="text-primary">=';
							$arrtostr = '';
							switch(gettype($param->getDefaultValue())){
								case 'array':
									$param_str.= '[';
									if(count($param->getDefaultValue())){
										foreach($param->getDefaultValue() as $def_key => $def_value){
											$arrtostr.= '"'.$def_key.'"=>"'.$def_value.'",';
										}
										$arrtostr = substr($arrtostr,0,-1);
									}
									$param_str.= $arrtostr.']';
									break;
								case 'boolean':
									$param_str.= ($param->getDefaultValue())? 'TRUE':'FALSE';
									break;
								case 'NULL':
									$param_str.= 'NULL';
									break;
								default: 
									$param_str.= '"'.$param->getDefaultValue().'"';
									break;
							}
							
							$param_str.='</span>';
						}
						$param_str.=', ';
					}
					echo substr($param_str,0,-2);
				}
				echo ')</span>';
				if (!empty($ref_method->getDocComment())){ 
					echo '<details class="doc-comment"><summary class="small text-muted">Details</summary><code><pre>';
					echo str_replace(['/**',' * ',' */',' *','*\n',], '', $ref_method->getDocComment());
					echo "</pre></code></details>";
				}
				echo '</li>';
			}
		}
	}
	echo "</details>"; 
}
?>

<style>
	ul#method-reference span.monospace {
		font-family: var(--bs-font-monospace);
	}
	ul#method-reference li {
		margin: 0.75rem auto;
	}
	ul#method-reference li > pre {
		margin-bottom: 0;
	}
	ul#method-reference li > code {
		margin: 0 auto;
	}
	ul#method-reference details.doc-comment > summary {
		list-style-type: '+ ';
		font-size: .8rem;
	}
</style>
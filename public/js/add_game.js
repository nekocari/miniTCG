let isCustomCode = false;
let customCodeSwitches = document.querySelectorAll('input[name="no_custom_code"]');
let cutomGameInputs = document.querySelectorAll('.custom-game-inputs');
customCodeSwitches.forEach((checkbox) => { 
	checkbox.addEventListener('change', (event) => {
		if (event.target.checked) {
			changeCustomInputVisibility(event.target.value);
		}
	})
});
function changeCustomInputVisibility(val){ 
	if(val == 1){
		cutomGameInputs.forEach((element) => { 
			element.classList.remove('d-none');
		});
	}else{
		cutomGameInputs.forEach((element) => { 
			element.classList.add('d-none');
		});
	}	
}
let isDaily = false;
let dailySwitches = document.querySelectorAll('input[name="daily_game"]');
let waitTimeInput = document.getElementById('wait-time-input');
dailySwitches.forEach((checkbox) => { 
	checkbox.addEventListener('change', (event) => {
		if (event.target.checked) {
			changeInputVisibility(event.target.value);
		}
	})
});
function changeInputVisibility(val){ 
	if(val == 1){
		waitTimeInput.classList.add('d-none');
	}else{
		waitTimeInput.classList.remove('d-none');
	}	
}
let luckyChoices = document.getElementById('lucky-choices');
let luckyResults = document.getElementById('lucky-results');
let customResults = document.getElementById('custom-results');
let customViewFile = document.getElementById('custom-view-file');
let customJsFile = document.getElementById('custom-js-file');
let customMethod = document.getElementById('custom-method');
let gameType = document.getElementById('game-type');
gameType.addEventListener("change", choices);
function choices(){ 
	if(gameType.value != 'lucky'){
		luckyChoices.classList.add('d-none');
		luckyResults.classList.add('d-none');
		customResults.classList.remove('d-none');
		customViewFile.classList.remove('d-none');
		customJsFile.classList.remove('d-none');
		customMethod.classList.remove('d-none');
	}else{
		luckyChoices.classList.remove('d-none');
		luckyResults.classList.remove('d-none');
		customResults.classList.add('d-none');
		customViewFile.classList.add('d-none');
		customJsFile.classList.add('d-none');
		customMethod.classList.add('d-none');
	}	
}
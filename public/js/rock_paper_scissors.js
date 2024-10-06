document.addEventListener("DOMContentLoaded", function() {
  let game = new rps;
  game.init();  
});

class rps {
	gameButtonElement;
	gameElement;
	
	playerChoice;
	comChoice;
	
	roundsLeft = 5;
	
	pointsPlayer = 0;
	pointsCom = 0;
	
	choiceOptions = {rock:'\u270a',paper:'\u270b',scissors:'\u270c'};
	
	
	init() {
		var game = this;
		this.gameElement = document.getElementById('rps-options');
		
		
		let optionsHTML = document.createElement('div');
		optionsHTML.setAttribute('id','game-buttons');
		
		for (let option in this.choiceOptions) {
			let button = document.createElement('button');
			button.value = option;
			button.innerHTML = '<span class="h1">'+this.choiceOptions[option]+'</span>';
			button.classList.add('btn','btn-outline-secondary','m-2','btn-rps');
			optionsHTML.append(button);
			button.addEventListener('click',function(event){ game.choose(button); event.preventDefault() });
		}
		
		this.gameElement.append(optionsHTML);
		
		
		let displayHTML = document.createElement('div');
		displayHTML.classList.add('display-1');
		displayHTML.innerHTML = '<span id="playerChoice"></span> <span id="comChoice"></span>';
		this.gameElement.append(displayHTML);
		
		let counterHTML = document.createElement('div');
		counterHTML.innerHTML = '<span class="btn btn-outline-primary disabled m-2">PLAYER: <span id="playerScore">0</span></span>';
		counterHTML.innerHTML+= '<span class="btn btn-outline-dark disabled m-2"> COM: <span id="comScore">0</span></span>';
		this.gameElement.append(counterHTML);
	}
	
	choose(element) {
		this.playerChoice = element.value;
		
		this.determineWinner();
		
		document.getElementById('playerChoice').innerHTML = this.choiceOptions[this.playerChoice];
		document.getElementById('comChoice').innerHTML = this.choiceOptions[this.comChoice];
		
		document.getElementById('playerScore').innerText = this.pointsPlayer;
		document.getElementById('comScore').innerText = this.pointsCom;
		
		this.roundsLeft--;
		
		if(this.roundsLeft == 0){
			document.getElementById('game-buttons').innerText = '';
			let end;
			if(this.pointsCom <= this.pointsPlayer){ end = 'won'; }else
			if(this.pointsCom >= this.pointsPlayer){ end = 'lost'; }else
			{ end = 'tied'; }
			document.getElementById('result-input').value = end;
			let game=this;
			setTimeout(function() { game.gameElement.submit() }, 1000);
		}
		
	}
	
	comChoose() {
    	let keys = Object.keys(this.choiceOptions);
    	return this.comChoice = keys[ keys.length * Math.random() << 0];
	}
	
	determineWinner() {
		this.comChoose();
		if(this.playerChoice == this.comChoice){
			this.pointsPlayer++;
			this.pointsCom++;
			return 'tied';
		}else
		if((this.playerChoice == 'rock' && this.comChoice == 'scissors') || 
		(this.playerChoice == 'scissors' && this.comChoice == 'paper') ||
		(this.playerChoice == 'paper' && this.comChoice == 'rock')){
			this.pointsPlayer+=2;
			return 'player';
		}else{ 
			this.pointsCom+=2;
			return 'com'; 
		}
	}
}
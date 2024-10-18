document.addEventListener("DOMContentLoaded", function() {
  let game = new hangman;
  game.init();  
});

class hangman {

    mistakesAllowed = 6;
    mistakesMade = 0;
    alphabet = String.fromCharCode(...Array(123).keys()).slice(97).split('');
    words;
    word;
    wordArray;
    lettersGuessed = [];
    lettersRight = [];
	gameElement;
	
	
	init() {
		var game = this;
        this.getWords();
        this.getRandomWord();
		this.gameElement = document.getElementById('hangman');

        
		let stateHTML = document.createElement('div');
		stateHTML.setAttribute('id','game-state');
        stateHTML.classList.add('h1','no-wrap','overflow-auto');

        this.wordArray.forEach((letter,i) => {
            let element = document.createElement('span');
            if(letter !== ' '){
                element.innerText = '_';
            }else{ 
                element.innerText = ' ';
            }
			element.classList.add('px-2','hangman_pos_'+i);
            stateHTML.append(element);
        });

		this.gameElement.append(stateHTML);
		
		
		let optionsHTML = document.createElement('div');
		optionsHTML.setAttribute('id','game-buttons');
		
		this.alphabet.forEach((letter,i) => {
            let button = document.createElement('button');
			button.value = letter;
			button.innerHTML = '<span>'+letter.toUpperCase()+'</span>';
			button.classList.add('btn','btn-outline-secondary','m-2','btn-letter');
			optionsHTML.append(button);
			button.addEventListener('click',function(event){ game.guess(button); event.preventDefault() });
        });
		
		this.gameElement.append(optionsHTML);

		
		let counterHTML = document.createElement('div');
		counterHTML.innerHTML = '<span class="btn btn-outline-primary disabled m-2">Fehler <span id="mistakeCounter">0</span> / '+this.mistakesAllowed+'</span>';
		this.gameElement.append(counterHTML);
	}

    getRandomWord() {
        this.word = this.words[Math.floor(Math.random() * this.words.length)];
        this.wordArray = this.word.toLowerCase().split("");
        this.wordArray.forEach((letter,i) => {
            if(letter !== ' ' && !this.lettersRight.includes(letter)){
                this.lettersRight.push(letter);
            }
        });
    }
    getWords() {
        let reqest = new XMLHttpRequest();
        reqest.open( "GET", 'config/hangman_words_'+document.getElementById('game-language').value+'.txt', false ); // false for synchronous request
        reqest.send( null );
        this.words = reqest.responseText.split(/\r?\n|\r|\n/g);
    }
	
	guess(element) {

        // letter guessed
		let letter = element.value;
        element.setAttribute('disabled','true');
        
        if(this.lettersRight.includes(letter)){
            
            this.lettersGuessed.push(letter);
            let keys = Object.keys(this.wordArray).filter(k=>this.wordArray[k].toLowerCase()===letter.toLowerCase());
            keys.forEach((position,i) => {
                document.querySelector('.hangman_pos_'+position).innerText = this.wordArray[position].toUpperCase();
            });
            
            if(this.lettersRight.length === this.lettersGuessed.length){
                document.getElementById('game-buttons').innerText = '';
                document.getElementById('result-input').value = 'won';
                let game=this;
                setTimeout(function() { game.gameElement.submit() }, 250);
            }

        }else{
            this.mistakesMade++;
            document.getElementById('mistakeCounter').innerText = this.mistakesMade;
            if(this.mistakesMade == this.mistakesAllowed){
                document.getElementById('game-buttons').innerText = '';
                document.getElementById('result-input').value = 'lost';
                let game=this;
                setTimeout(function() { game.gameElement.submit() }, 250);
            }
        }
		
	}
}
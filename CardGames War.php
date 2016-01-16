<?php
/****  General File for Card Game Application
*****  War Specific
*****  Author:   Louis Taylor
*****  This Application is designed to define the functionality for card games.
*****  It includes a class for defining individual card objects
*****  These card objects are defined and used in a Deck Class.
*****  These Deck objects are 'divided' used to create stack objects.
*****  
*****  There is also a class for specifying each player called the Player class.
*****
*****  There is an abstract base class for games and then a specific extended class for each game defined.  
*****  In this case, the game defined is 'War'
*****  Stacks for each player are defined when the War objects are created for each player.
*****  The above mentioned Player and Deck objects are passed into the War object constructor and deck objects are created and associated with each player object
*****  Via the player ID property which is used as an index in an array of stacks defined in the War class.
*****  
*****  The individual game classes create single objects for each pair of players and pass them to each object pointer. 
*****  Each pair of players is associated with a particular game by the DeckID of the Deck Object that gets passed into the War constructor
****/
class Card  {
	/**** Class defining each game piece unit   
	*****  
	****/
	private $suit;

	private $card_rank;



	protected function __contstruct($suit, $cardrank)  {
	/**** Function for constructing object for each game piece unit   
	*****  Inputs:   
	*****  A specification for the card, 'suit'
	*****  A numerical ranking with respect to other cards of it's type/deck, 'cardrank'
	*****  Output:
	*****  A card object
	****/

	$this->card_rank = $cardrank;
	  self::setFace();
	}

	private function setFace() {
	/**** Function for specifying the distinguishing particulars of each game piece unit   
	*****  Inputs:   
	*****  None
	*****  
	*****  Output:
	*****  None
	****/
	  if(($this->card_rank >= '0') && ($this->card_rank <= '9'))  // ace through ten
		$this->suit = false;
	  else
		$this->suit = true;
	}

	protected function getRank() {
	/**** Function for retrieving the relative deck value of each game piece unit   
	*****  Inputs:   
	*****  None
	*****  
	*****  Output:
	*****  The specific deck value of each game piece unit
	****/
	  return $this->card_rank;
	}

}


Class Deck { // extends Card  {  ennumeration does not justify extending a class, specification Does
	/**** Class defining each collection of game piece units for each tournament   
	*****  
	****/
	private static $deck;  // the same deck is used by multiple objects
	private $deckID;

	const DECK_LIMIT = 52;

	const SUIT1 = '';   //  Definitions for each Card Value
	const RANK_1 = 0;
	/*
	.
	.
	.
	.
	  */
	const SUITLAST = '';
	const RANK_LAST = 0;

	private $card_defs = array($suit => $rank)

	public function __construct()  {
	/**** Function for constructing object for the collections of game piece units used for each game   
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  A deck object
	****/
	  self::setDeck();
	  self::setDeckID();
	}

	public function __destruct()  {
	/**** Function for destroying the card objects associated with each deck object 
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  None
	****/
	  foreach ($this->deck as $card) { 
  	    unset($card); 
      } 
	}

	public function setDeck()  {  //  Shuffle Cards
	/**** Function for creating the card objects associated with each deck object 
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  None
	****/
	if(!is_array($deck) || (count($this->deck) < DECK_LIMIT))  {
	   $suit_rank = Array('1h', '1s', '1d', '1c', '2h', ....);
	   $suit_rank = array_combine($suit_rank,$suit_rank);
	   for($i = 0; $i < DECK_LIMIT; $i++)  {
		 $sr = rand($suit_rank);
		 $rank = substr($sr, 0, 1);
		 $suit = substr($sr, 1, 1);
		 $this->deck[$i] = new Card($suit, $rank);    
		 unset($suit_rank[$sr]);
	  }
	}

	public function getDeck()  {
	/**** Function for returning the deck array of card objects   
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  A deck object
	****/
	  return $this->deck;
	}

	public function getDeckID()  {
	/**** Function for returning the deck object ID
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  A deck object ID
	****/
	  return $this->deckID;
	}

	public function getCard()  {   //  Called from game   $$$$$$ 
	/**** Function for removing the top card Object of the deck array and returning it 
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  A card object
	****/
	  return array_shift($this->deck);
	}
}


Class Stack  //  Extends Deck    not quite a specification, more like a sub-division
{
	/**** Class defining the subdivision of each deck of game piece units specific to each user   
	*****  
	****/
	private $stack_limit;  //  stores information about how many cards a stack can have
	private $user_stack;  //  stores an array of card objects dealt from a Deck object passed into the dealCard method
	private $player_id;  //  stores an integer associated with the 

	public function __construct($gameDeck, $player)  { //  Different game subclasses deal card limits are passed in as arguments
	/**** Function for constructing object for each user specific game piece unit collection  
	*****  Inputs:   
	*****  A game piece unit collection, 'Deck'
	*****  An object identifying a specific player, 'player'
	*****  Output:
	*****  A deck sub-unit specific to that player, a Stack object
	****/
	  self::$stack_limit = $gameDeck::DECK_LIMIT/2;
	  self::setStack($gameDeck);
     $this->player_id = $player->id;  ///  $$$$  associate individual stacks with individual hands
	}

	public function __destruct()  {
	/**** Function for destroying the card objects associated with each stack object 
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  None
	****/
      foreach ($this->user_stack as $card) { 
	    unset($card); 
	  } 
	}

	public function setStack($gameDeck)  {  //  deal a hand
	/**** Function for creating the card objects associated with each stack object 
	*****  Inputs:   
	*****  The Deck object that a player is using to create a stack object
	*****
	*****  Output:
	*****  None
	****/
	//  if(is_object($gameDeck)  {
	   
	  for($i = 0; $i < self::$stack_limit; $i++)  {
		 self::dealCard($gameDeck);  
	  }
	// }

	}

	public function getStack()  {
	/**** Function for returning the stack array of card objects   
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  A deck object
	****/
	  return $this->user_stack;
	}

	public function getCard()  {   //  Called from game   $$$$$$ 
	/**** Function for removing the top card Object of the stack array and returning it 
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  A card object
	****/
	  return array_shift($this->user_stack);
	}

	public function dealCard($gameDeck)  {  //  hit
	/**** Function for adding a card Object to the bottom of a stack array  
	*****  Inputs:   
	*****  A deck object (or could be another stack object)
	*****
	*****  Output:
	*****  None
	****/
		 $this->user_stack[] = $gameDeck->getCard();  ///  $$$$  Where card of Deck object is dealt
	}

}




abstract class Game {
	/****  An abstract base class for specifying general information about a game
	*****  
	****/
	protected $name;
	protected $deal_card_limit;  // number of cards in a hand

	protected function __construct($name, $cardnum)  {
	/**** Function for defining general information for Game related objects   
	*****  Inputs:   
	*****  The Name of a particular game, 'name' 
	*****
	*****  The total number of cards that a Deck contains for this particular game, 'cardnum' 
	*****
	*****  Output:
	*****  A game object 
	*****  This is only ever specified in the creation of an object of an extended class
	****/
	   self::setGame($name, $cardnum);
	}

	protected function setGame($name, $cardnum)  {
	/**** Function for setting the name and card count properties associated with each game  
	*****  Inputs:   
	*****  The Name of a particular game, 'name' 
	*****
	*****  The total number of cards that a Deck contains for this particular game, 'cardnum' 
	*****
	*****  Output:
	*****  None
	****/
	  $this->name = $name;
	  $this->deal_card_limit = $cardnum;
	}

	protected function getNumbertoDeal() {
	/**** Function for returning the number of cards in a deck for the associated game object   
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  A count of cards in a deck 
	****/
	  return $this->deal_card_limit;
	}


}


///  Different Game Classes are extended off of Game


Class War extends Game  
{
	/****  An extended class for specifying particulars about a game
    *****  In this case, the game defined is 'War'
	*****  This class would be used for specifying and driving most of the specific functionality for the game
    *****  This involves calling the relevant methods in the Hand class as well as the Stack class	
	****/
	const GAME_NAME = 'War';
	const DEAL_LIMIT = 2;
//	private static $players = array();  Dont really need this if the user id is explicitly associated with the stack property via 
	private static $stacks = array();
	//  I would try to have the specific game object shared by both players and store the stack objects for both using a Singleton
	private static $game_instance = array();
	
	public function __construct()){
	/**** Function for defining general information for Game related objects   
	*****  Inputs:   
	*****  None 
	*****
	*****  Output:
	*****  A game specific object 
	*****  This is only ever called from inside this class and the object is assigned to a cell in static array $game_instance
	****/
	//	$this->players[$player->id] = $player; 
		Game::__construct();
	}
	    
    // the getInstance() method returns a single instance of the object
    public static function getInstance($Deck, $player){
	/**** Function called directly for instantiating a Game specific object and returning it   
	*****  Inputs:   
	*****  An object for a specific deck, 'Deck' 
	*****
	*****  An object for a particular player, 'player' 
	*****
	*****  Output:
	*****  A pointer to a game specific object 
	*****  The object returned is specific to 2 player objects based upon their connection to the specific Deck object passed in
	****/
        //  We want to create and pass back a Single game instance for each Pair of Players 
        if(!is_object(self::$game_instance[$Deck->getDeckID()])){
           self::$instance[$Deck->getDeckID()] = self::__construct();  //  Tie specific deck to specific player
        }
        if(!isset($this->stacks[$player->id])){
				$this->stacks[$player->id] = new Stack($Deck, $player);  //  Tie specific stack to specific player
		}
        return self::$instance;
    }

	public static function contest()  {
	/**** Function for carrying out the game mechanics for contests between 2 players involving single cards 
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  None
	****/
	  $winner = 'tie';
	  
	  //$winner = self::gameMatch();
	  foreach($stacks as $playerid => $cards)  {
		
		$card[$playerid] = new Hand(1, $cards, $playerid);
	  }
	  $player_ids = array_keys($card);
	  if($card[0] > $card[1])
		  $winner = $player_ids[0]
	  if($card[1] > $card[0])
		  $winner = $player_ids[1]
	  
	  if($winner !='tie')  {
		  
		  $stacks[$winner] = $stacks[$winner] + $card[$winner];
	  }
	  else
	  {
			
		self::gameWar();	
			
		}
	  }
	  }
		//  write round victory to database
		
	  foreach($stacks as $playerid => $cards)  {
		if(count($cards) == 0)
		// possibly write game victory to database
	}
	
	
	public static function gameWar()  {
	/**** Function for carrying out the game mechanics for contests between 2 players involving hands containing multiple cards 
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  None
	****/
		$winner = "tie";
	  for($stacks as $playerid => $cards)  {
		   $hand = new Hand(War::DEAL_LIMIT, );
		
	  foreach($stacks as $playerid => $cards)  {
		
		self::dealCard($gameStack);  
		if()  {
	}
	
}


// 
class Hand {  // extends Stack  {
	/****  Class defined for usage of groups of cards in contests between two players (Wars)
	*****
	****/
	private $user_hand;  //  stores an array of card objects dealt from a Stack object passed into the dealCard method
	private $player_id;

	public function __construct($number_to_deal, $gameStack, $player)  { //  Different game subclasses deal card limits are passed in as arguments
	/****  Function for creation of player specific hand objects containing multiple cards 
	*****  Inputs:   
	*****  Variable specifying the number of cards to be dealt from the stack, 'number_to_deal'
	*****  A stack object from which the cards will be dealt, 'gameStack'
	*****  The player object associated with the stack, 'player'
	*****
	*****  Output:
	*****  None
	****/
	  self::setHand($number_to_deal, $gameStack);
		 $this->player_id = $player->id;  ///  $$$$  associate individual players with individual hands
	}

   	public function __destruct()  {
	/**** Function for destroying the card objects associated with each hand object 
	*****  Inputs:   
	*****  None
	*****
	*****  Output:
	*****  None
	****/
	  foreach ($this->user_hand as $card) { 
	    unset($card); 
	  } 
	}

	public function setHand($game_object_hand_limit, $gameStack)  {  //  deal a hand
	/**** Function for creating the card objects associated with each hand object 
	*****  Inputs:   
	*****  The number of card objects to deal to create a hand object, 'game_object_hand_limit'
	*****  The stack object that a player is using to create a hand object, 'gameStack'
	*****
	*****  Output:
	*****  None
	****/
	//  if(is_object($game_object)  {
	   
	  for($i = 0; $i < $game_object_hand_limit; $i++)  {
		 self::dealCard($gameStack);  
	  }
	// }

	}

	public function getCard($cardNum)  {  // Either returns a card of prints it out, not sure
	/**** Function for returning a particular card in a hand   
	*****  Inputs:   
	*****  An index value for a particular hand array, 'cardNum'
	*****
	*****  Output:
	*****  None 
	****/

	}

	public function disCard($cardNum)  {  //  get rid of a card
	/**** Function for getting rid of a card object in a hand array   
	*****  Inputs:   
	*****  An index value for a particular hand array, 'cardNum'
	*****
	*****  Output:
	*****  None 
	****/
		unset($this->user_hand[$cardNum]);
	}

	public function dealCard($gameStack)  {  //  hit
	/**** Function for adding a card Object to a Hand array  
	*****  Inputs:   
	*****  A stack object
	*****
	*****  Output:
	*****  None
	****/
		 $this->user_hand[] = $gameStack->getCard();  ///  $$$$  Where card of Stack object is dealt
	}

}



class Player {  
	/**** Class defining the name, id and other details specific to each user   
	*****  
	****/
	private $name;
	public $id;
	
}


// Cards associated with Stacks via the Deck and then the Stack classes

// Stack cards associated with Hands in the Hands class

// Players associated with stacks in the 'War' class




//Driving Script
//The way that the classes are used is as follows:
//  Individual Player objects are instantiated
//Then a Deck object is instantiated.
//This Object is then used to create an Object of the specific Game class that people are playing.
//This single object is stored as a singleton and passed to individual pointers for each player. 
//These pointers storing the object are then used by each user to invoke the functionality of the game.
//In the case of the War simulation, this is done by calling the 'contest' method


	$player1 = new Player();
	$player2 = new Player();

    $our_deck = new Deck();
	
	$player1_war_object = War::getInstance($our_deck, $player1);
	$player2_war_object = War::getInstance($our_deck, $player2);  //  both of these return the same object

$player1_war_object->contest();
$player1_war_object->contest();

$player2_war_object->contest();
$player2_war_object->contest();

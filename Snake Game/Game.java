public class Game
{

// ----------------------------------------------------------------------
// Part a: the score message

  private int Score=0;
  private String ScoreMessage=("Score: "+Score);
  private final int GridSize;
  private final Cell[][] GridCell;
  public String getScoreMessage()
  {
    return ScoreMessage;
  } // getScoreMessage


  public void setScoreMessage(String message)
  {
    ScoreMessage=message;
  } // setScoreMessage


  public String getAuthor()
  {
    return "Vlad Geana";
  } // getAuthor


// ----------------------------------------------------------------------
// Part b: constructor and grid accessors


  public Game(int requiredGridSize)
  {
    GridSize=requiredGridSize;
    GridCell=new Cell[GridSize][GridSize];
    for(int i=0;i<GridSize;i++)
      for(int j=0;j<GridSize;j++)
        GridCell[i][j]=new Cell();
  } // Game


  public int getGridSize()
  {
    return GridSize;
  } // getGridSize


  public Cell getGridCell(int x, int y)
  {
    return GridCell[x][y];
  } // getGridCell


// ----------------------------------------------------------------------
// Part c: initial game state

// Part c-1: setInitialGameState method

  public void setInitialGameState(int requiredTailX, int requiredTailY,
                                  int requiredLength, int requiredDirection)
  {
    for(int i=0;i<GridSize;i++)
      for(int j=0;j<GridSize;j++)
      {
        GridCell[i][j].setClear();
      }
    placeSnake(requiredTailX,requiredTailY,requiredLength,requiredDirection);
    placeFood();
  } // setInitialGameState


// ----------------------------------------------------------------------
// Part c-2 place food
  private int xFood=0,yFood=0;
  public void placeFood()
  {
    boolean store=moveFoodOn;
    moveFoodOn=false;
    do
    {
      xFood=(int) (Math.random() * GridSize);
      yFood=(int) (Math.random() * GridSize); 
    }
    while(GridCell[xFood][yFood].getType() != Cell.CLEAR);
    GridCell[xFood][yFood].setFood();
    moveFoodOn=store;
  }

// ----------------------------------------------------------------------
// Part c-3: place snake
   
  private int xTail,yTail,xHead,yHead,snakeLength,snakeDirection;
  public void placeSnake(int requiredTailX, int requiredTailY,
                                  int requiredLength, int requiredDirection)
  { 
    xTail=requiredTailX;
    yTail=requiredTailY;
    snakeLength=requiredLength;
    snakeDirection=requiredDirection;

    GridCell[requiredTailX][requiredTailY].setSnakeTail(
                     Direction.opposite(requiredDirection),requiredDirection); //placeTail
    int nextX=requiredTailX + Direction.xDelta(requiredDirection);
    int nextY=requiredTailY + Direction.yDelta(requiredDirection);
    int copySnakeLength=snakeLength-2;
    while(copySnakeLength>0) //while loop to place entire initial body
    {
      GridCell[nextX][nextY].setSnakeBody(Direction.opposite(requiredDirection),
                                            requiredDirection);
                          
      nextX+=Direction.xDelta(requiredDirection);
      nextY+=Direction.yDelta(requiredDirection);
      copySnakeLength--;
    }
    GridCell[nextX][nextY].setSnakeHead(Direction.opposite(requiredDirection)
                          ,requiredDirection);
    xHead=nextX;
    yHead=nextY;
  }

// ----------------------------------------------------------------------
// Part d: set snake direction


  public void setSnakeDirection(int requiredDirection)
  {
    if(requiredDirection!=Direction.opposite(snakeDirection))
    {
      snakeDirection=requiredDirection;
      GridCell[xHead][yHead].setSnakeHead(Direction.opposite(requiredDirection),
                                                      requiredDirection);
    }
    else
    {
      setScoreMessage("You cannot eat yourself!");
    }
    
  } // setSnakeDirection


// ----------------------------------------------------------------------
// Part e: snake movement
  public boolean isInsideGrid(int xHead,int yHead)
  { 
    if(yHead==0 && GridCell[xHead][yHead].getSnakeOutDirection()==Direction.NORTH)
      return false;
    if(yHead==(GridSize-1) && GridCell[xHead][yHead].getSnakeOutDirection()==Direction.SOUTH)
      return false;
    if(xHead==0 && GridCell[xHead][yHead].getSnakeOutDirection()==Direction.WEST)
      return false;
    if(xHead==(GridSize-1) && GridCell[xHead][yHead].getSnakeOutDirection()==Direction.EAST)
      return false;
    return true;
  } //check for border crashes

  public boolean isInside(int x,int y) //to check if food is inside grid
  {
    return (x>=0 && x<GridSize && y>=0 && y<GridSize); 
  }  

// Part e-1: move method
  
  public boolean isFood=false,noCrash=true;
  public int increment,xHeadCopy,yHeadCopy;

  public void move(int moveValue)  //main move method
  {
    xHeadCopy=xHead;
    yHeadCopy=yHead;
    isFood=false;
    //snakeLength++;
    increment=moveValue * ((snakeLength/(GridSize*GridSize / 36 + 1)) + 1);
    if(noOfTrees!=0)
      increment*=noOfTrees; //modify increment value to add to Score
                                                         
    if(GridCell[xHead][yHead].isSnakeBloody()==false)
    {
      noCrash=moveSnakeHead(xHead+Direction.xDelta(snakeDirection),
                                           yHead+Direction.yDelta(snakeDirection)); 
      if(noCrash==true && isFood==false)
      {
        moveSnakeTail();    //visually increment the snake length  
        if(trailOn)
        {
          decrementOtherCells();
        }
      }
    }
    if(moveFoodOn)
      moveFood();
  } // move


// ----------------------------------------------------------------------
// Part e-2: move the snake head
  public boolean moveSnakeHead(int newXHead,int newYHead)
  {
    noCrash=true;
    if(isInsideGrid(xHead,yHead)==false)  //check that snakeHead is inside the grid
    {
      ScoreMessage="You cannot step out of the grid!";
      noCrash=false;
      
      if(isCountDownOn==false)
        isCountDownOn=true;
      decrementCountDown();  //COUNTDOWN

      return false; //return false if crashed the grid
    }
    if(GridCell[newXHead][newYHead].isSnakeType()==true)
    {
      ScoreMessage="You cannot eat yourself, eh you cannibal!";
      noCrash=false;
      
      if(isCountDownOn==false)
        isCountDownOn=true;
      decrementCountDown();      

      return false;
    }
    if(GridCell[newXHead][newYHead].getType()==Cell.TREE)
    {
      ScoreMessage="Watch out for treeeees!";
      noCrash=false;
      if(isCountDownOn==false)
        isCountDownOn=true;
      decrementCountDown();
      return false;  // TREE CRASH
    }
    if(noCrash==true)
    {
      if(isCountDownOn==true)
        resetCountDown();

      int oldDirection=GridCell[xHead][yHead].getSnakeInDirection();
      xHead=newXHead;
      yHead=newYHead; //get coordinates for next snake head position

      if(GridCell[xHead][yHead].getType()==Cell.FOOD)
      { 
        snakeLength++;
        eat();  //EAT
      }
      GridCell[xHead][yHead].setSnakeHead(oldDirection,snakeDirection); //CHANGE SNAKE HEAD
                                            
      GridCell[xHead-Direction.xDelta(snakeDirection)]
           [yHead-Direction.yDelta(snakeDirection)].setClear();                         
           //CLEAR PREVIOUS CELL
      GridCell[xHead-Direction.xDelta(snakeDirection)]
           [yHead-Direction.yDelta(snakeDirection)].setSnakeBody(
                                   Direction.opposite(snakeDirection),snakeDirection);
          //TURN PREVIOUS HEAD CELL INTO BODY CELL
    }
   return noCrash;
  }

// ----------------------------------------------------------------------
// Part e-3: move the snake tail
  public void moveSnakeTail()
  {
    int TailOutDirection=GridCell[xTail][yTail].getSnakeOutDirection();
    int TailInDirection=GridCell[xTail][yTail].getSnakeInDirection();
    int HeadOutDirection=GridCell[xHead][yHead].getSnakeOutDirection();
    int HeadInDirection=GridCell[xHead][yHead].getSnakeInDirection();
    
    if(trailOn)
      GridCell[xTail][yTail].setOther(50);    
    else 
      GridCell[xTail][yTail].setClear();
    
    
    xTail+=Direction.xDelta(TailOutDirection);
    yTail+=Direction.yDelta(TailOutDirection);
    
    GridCell[xTail][yTail].setSnakeTail(); //Set new snake tail
  }

// ----------------------------------------------------------------------
// Part e-5: eat the food
  public void eat()
  { 
    snakeLength++;
    ScoreMessage="The score has been incremented!";
    Score+=increment;        
    isFood=true;
    placeFood();
    if(treesOn==true)
      placeTree();
  } //eat

  public int getScore()
  {
    return Score;
  } // getScore


// ----------------------------------------------------------------------
// Part f: cheat

  public void cheat()
  {
    Score/=2;
    ScoreMessage="The scor has been halved!";
    for(int i=0;i<GridSize;i++)
      for(int j=0;j<GridSize;j++) 
      if(GridCell[i][j].isSnakeBloody()==true)
      {
        GridCell[i][j].setSnakeBloody(false);
      }
  } // cheat


// ----------------------------------------------------------------------
// Part g: trees
  private int noOfTrees=0;
  private boolean treesOn=false;

  public void placeTree()
  {
    int xRandom,yRandom;
    do
    {
      xRandom=(int) (Math.random() * GridSize);
      yRandom=(int) (Math.random() * GridSize); 
    }
    while(GridCell[xRandom][yRandom].getType() != Cell.CLEAR);  //Search for a clear cell

    GridCell[xRandom][yRandom].setTree();
  }
  // place a single tree at random coordinates
  public void toggleTrees()
  {
    if(treesOn==true)
    {
      setScoreMessage("Trees OFF!");
      treesOn=false;
      noOfTrees=0;
      for(int i=0;i<GridSize;i++)
        for(int j=0;j<GridSize;j++)
        if(GridCell[i][j].getType()==Cell.TREE)
          GridCell[i][j].setClear();  //Erase all existing trees
    }
    else
    {
      setScoreMessage("Trees ON!");
      treesOn=true;     
      placeTree(); //place one tree in a random clear cell
    }
  } // toggleTrees


// ----------------------------------------------------------------------
// Part h: crash countdown
  private final int countDownStart=5;
  private int currCountDown=5;
  private boolean isCountDownOn=false;

  private void resetCountDown()
  {
    if(currCountDown>0)
      ScoreMessage="You were "+ currCountDown +" moves close to death but escaped!";
       //Escaped death
    isCountDownOn=false;  //Turn countdown on
    currCountDown=countDownStart;
  }
  private boolean decrementCountDown()
  {
    if(isCountDownOn==false)
      return false;

    currCountDown--;
    if(currCountDown > 0)
    {  
      ScoreMessage=("Hurry! " + currCountDown +" moves left!");
      return false;  //Warn player that countdown is enabled
    }       
    else
    {
      GridCell[xHead][yHead].setSnakeBloody(true);
      GridCell[xHeadCopy][yHeadCopy].setSnakeBloody(true);
      Score=0;
      toggleTrees();
      ScoreMessage="You weren't able to escape death! :( Press 'c' to cheat :)";
      moveFoodOn=false;
      resetCountDown();
      //setInitialGameState(xHead,yHead,GridSize/2,Direction.opposite(snakeDirection));
      return true;
    }
  } 
  //decrementCountDown

// ----------------------------------------------------------------------
// Part i: optional extras


  public String optionalExtras()
  {
    return "  No optional extras defined\n";
  } // optionalExtras

  private boolean trailOn=false;
  private void decrementOtherCells()
  { 
    for(int i=0;i<GridSize;i++)
      for(int j=0;j<GridSize;j++)
        if(GridCell[i][j].getType()==Cell.OTHER)
      { 
        int level=GridCell[i][j].getOtherLevel();
        if(level>=10)
          GridCell[i][j].setOther(level-7);
        else
          GridCell[i][j].setClear();
      }
  }
  private boolean moveFoodOn=false;
  private int foodDirection=snakeDirection;
  public void turnFoodLeft()
  {
    switch(foodDirection)
    {
      case 1: foodDirection=4;  break;
      case 2: foodDirection=1;  break;
      case 3: foodDirection=2;  break;
      case 4: foodDirection=3;  break;
    }
  }
  public void turnFoodRight()
  {
    switch(foodDirection)
    {
      case 1: foodDirection=2;  break;
      case 2:  foodDirection=3; break;
      case 3: foodDirection=4;  break;
      case 4:  foodDirection=1; break;
    }
  } // change food direction
  private void moveFood()
  {
    foodDirection=snakeDirection;
    int nextXFood=xFood+Direction.xDelta(foodDirection);
    int nextYFood=yFood+Direction.yDelta(foodDirection);
    int dx=nextXFood-xHead;
    int dy=nextYFood-yHead;
    double dist=Math.sqrt(dx*dx + dy*dy);
    dx=xFood-xHead;
    dy=yFood-yHead;
    double currDistance=Math.sqrt(dx*dx + dy*dy);
    if(dist < currDistance || isInside(nextXFood,nextYFood)
            ==false || GridCell[nextXFood][nextYFood].getType()!=Cell.CLEAR)//turn if true
    {
      turnFoodRight();
      nextXFood=xFood+Direction.xDelta(foodDirection);
      nextYFood=yFood+Direction.yDelta(foodDirection);
      if( isInside(nextXFood,nextYFood) && GridCell[nextXFood][nextYFood]
                           .isSnakeType()==false )
      {
        GridCell[xFood][yFood].setClear();
        GridCell[nextXFood][nextYFood].setFood();
        xFood=nextXFood;
        yFood=nextYFood;           
      }
      else if(isInside(nextXFood,nextYFood))
      { 
        turnFoodLeft();
        turnFoodLeft();
        nextXFood=xFood+Direction.xDelta(foodDirection);
        nextYFood=yFood+Direction.yDelta(foodDirection);
        if( isInside(nextXFood,nextYFood) && GridCell[nextXFood][nextYFood]
                           .isSnakeType()==false )
        {
          GridCell[xFood][yFood].setClear();
          GridCell[nextXFood][nextYFood].setFood();
          xFood=nextXFood;
          yFood=nextYFood;
        }
      }  
    }
    else
    {
      GridCell[xFood][yFood].setClear();
      GridCell[nextXFood][nextYFood].setFood();
      xFood=nextXFood;
      yFood=nextYFood;
    } 
    
  }
  public void optionalExtraInterface(char c)
  {
    if (c > ' ' && c <= '~' &&  c!='g' && c!='b' && c!='m')
      setScoreMessage("Key " + new Character(c).toString()
                      + " is unrecognised (try h)");
    if(c=='g')
    { 
      if(trailOn==false)
      {
        trailOn=true;
        setScoreMessage("Gutter Trail ON!");
      }
      else
      {
        trailOn=false;
        setScoreMessage("Gutter Trail OFF!");
      }
    }
    else if(c=='b')
    {
      int x=xHead+Direction.xDelta(snakeDirection);
      int y=yHead+Direction.yDelta(snakeDirection);
      if(isInsideGrid(x,y) && GridCell[x][y].getType()==Cell.TREE)
      {
        GridCell[x][y].setClear();
        setScoreMessage("TREE BURNT!!!");
      }
    }
    else if(c=='m')
    {
      if(moveFoodOn==false)
      {
        moveFoodOn=true;
        ScoreMessage="Food is moving!";
      }
      else
      {
        moveFoodOn=false;
        ScoreMessage="Food stopped moving";
      }
    }
  } // optionalExtraInterface

} // class Game

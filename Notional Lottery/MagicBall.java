import java.awt.Color;

public class MagicBall extends Ball
{
  public static final int NORMAL = 0;
  public static final int INVISIBLE = 1;
  public static final int FLASHING = 2;
  public static final int COUNTING = 3;

  private int magicBallType;
  private int countingValue;
 
  // | ----------- part E 
  private MagicBall next=null;
  private MagicBall previous=null;

  public MagicBall getNextBall()
  {
    return next;
  }
  public MagicBall getPrevBall()
  {
    return previous;
  }
  public void setNextBall(MagicBall nextBall)
  {
    next=nextBall;
  }
  public void setPreviousBall(MagicBall previousBall)
  {
    previous=previousBall; 
  } 
  // part E  ------------|

  public MagicBall(int paramInt, Color paramColor)
  {
    super(paramInt,paramColor);
    magicBallType=NORMAL;
  }
  public BallImage makeImage()
  {
    return new MagicBallImage(this);
  }//override
  public int getValue()
  {
    if(magicBallType==COUNTING)
    {
      if(countingValue==99)
        countingValue=0; //reset
      else
        countingValue++; //increment
      return countingValue;
    }
    else
      return super.getValue(); //return ball value from superclass
  }//override
 
  public void doMagic(int spellNumber)
  {
    switch (spellNumber)
    {
      case 1: //change state
        if(magicBallType==3)
          magicBallType=0; 
        else
        {
          magicBallType++;
          if(magicBallType==3)
            countingValue=0;
        }
        getImage().update();
        break;
      case 2: //set ball to NORMAL
      {
        magicBallType=NORMAL;
        getImage().update(); //call update method from MagicBallImage class
        break;
      }
      case 3:
      {
        if(magicBallType==3)
        {
          magicBallType=0; 
          next.magicBallType=0; 
          previous.magicBallType=0;
        }
        else
        {
          magicBallType++;
          next.magicBallType=magicBallType;
          previous.magicBallType=magicBallType;
          if(magicBallType==3)
          {
            countingValue=0;
            next.countingValue=0;
            previous.countingValue=0;
          }
        }
        if(next!=null)
         next.getImage().update();
        if(previous!=null)
         previous.getImage().update();
        break;
      }
    }
  }
  public boolean isVisible()
  {
    return (magicBallType!=1); 
    //isVisible
  }
  public boolean isFlashing()
  {
    return (magicBallType==2 || magicBallType==3); 
    //isFlashing
  }
 
}

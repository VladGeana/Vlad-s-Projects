import java.awt.Color;
public class MagicWorker extends Worker
{
  private int arraySize=10, lastItemIndex=0;
  private MagicBall[] magicBallsCreated=new MagicBall[arraySize];
  public MagicWorker(String name)
  {
    super(name);
  }

  public PersonImage makeImage()
  {
    return new MagicWorkerImage(this);
  }//override
  
  public void doMagic(int spellNumber)
  {
    for(int index=0;index<lastItemIndex;index++)
      magicBallsCreated[index].doMagic(spellNumber);
  }// doMagic method for every magic ball made by this worker
  
  public Ball makeNewBall(int number, Color colour)
  {
    MagicBall ball=new MagicBall(number,colour);
    addToArray(ball);
    return ball;
  } // makeNewBall

  public void addToArray(MagicBall magicBall)
  {
    if(lastItemIndex < arraySize -1)
    {
      magicBallsCreated[lastItemIndex] = magicBall;
      lastItemIndex++;
    }
    else
    {
      MagicBall toCopyMagicBall[]=new MagicBall[arraySize*2];
      for(int index=0;index<arraySize;index++)
        toCopyMagicBall[index]=magicBallsCreated[index];
      arraySize*=2;
      magicBallsCreated=toCopyMagicBall;
    }
  }
  public String getClassHierarchy()
  { 
    return ("Magic "+ super.getClassHierarchy());
  }
}

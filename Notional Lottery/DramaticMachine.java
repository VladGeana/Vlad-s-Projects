public class DramaticMachine extends Machine
{
  public DramaticMachine(String name,int size)
  {
    super(name,size);
  }
  public Ball ejectBall()
  {
    if (getNoOfBalls() <= 0)
      return null;
    else
    {
      // Math.random() * getNoOfBalls yields a number
      // which is >= 0 and < number of balls.
      int ejectedBallIndex = (int) (Math.random() * getNoOfBalls());
      for(int index=0;index<ejectedBallIndex;index++)
      {
        Ball currBall=getBall(index);
        currBall.flash(4,5);
      }

      Ball ejectedBall = getBall(ejectedBallIndex);
      ejectedBall.flash(4, 5);
      swapBalls(ejectedBallIndex, getNoOfBalls() - 1);
      removeBall();
      return ejectedBall;
    }
  } // ejectBall
  public String getType()
  {
    return "DramaticMachine";
  }
}


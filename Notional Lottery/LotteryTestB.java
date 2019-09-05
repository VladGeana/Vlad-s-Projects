public class LotteryTestB
{
  public static void main(String[] args)
  {
    SpeedController speedController= new SpeedController(SpeedController.HALF_SPEED);
    LotteryGUI gui = new LotteryGUI("Dramatic TV Studio", speedController);

    DramaticGame game1 = new DramaticGame(" O’Luck", 49,"Slippery’s Mile", 7);
    gui.addGame(game1);
    speedController.delay(40);

    new Worker("").fillMachine(game1);
    
    for (int count = 1; count <= game1.getRackSize(); count ++)
    {
      speedController.delay(40);
      game1.ejectBall();      
    } 
  }
}

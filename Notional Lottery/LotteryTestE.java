import java.awt.Color;
public class LotteryTestE
{
  public static void main(String[] args)
  {
    SpeedController speedController= new SpeedController(SpeedController.HALF_SPEED);
    LotteryGUI gui = new LotteryGUI("TV Studio", speedController);

    Game game1 = new Game(" O’Luck", 9,"Slippery’s Mile", 3);
    //create game
    gui.addGame(game1);
    //add game to GUI
    speedController.delay(40);

    //new Worker("").fillMachine(game1);
    int count=1;
    MagicBall magicBall=new MagicBall(count,new Color(0,255,0,255));
    game1.machineAddBall(magicBall);
    count++;     

    MagicBall previous=magicBall;
    MagicBall next=null;
    MagicBall first=previous;

    while(count<=9)
    {
      magicBall=new MagicBall(count,new Color(0,255,0,255));
      game1.machineAddBall(magicBall);
      count++;      

      previous.setNextBall(magicBall);
      magicBall.setPreviousBall(previous);

      previous=magicBall;
    }
    magicBall.setNextBall(first);
    first.setPreviousBall(magicBall);  //create a loop
    //creates 9 linked MagicBalls and adds them to the game
    
  }
}

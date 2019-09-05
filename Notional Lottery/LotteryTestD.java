public class LotteryTestD
{
  public static void main(String[] args)
  { 
    SpeedController speedController= new SpeedController(SpeedController.HALF_SPEED);
    LotteryGUI gui = new LotteryGUI("TV Studio", speedController);

    MagicWorker magicWorkerBill=new MagicWorker("Magic Worker Bill");
    MagicWorker magicWorkerTim=new MagicWorker("Magic Worker Tim");
    gui.addPerson(magicWorkerBill);
    gui.addPerson(magicWorkerTim);

    Game game1 = new Game(" O’Luck", 9,"Slippery’s Mile", 3);
    //create game
    gui.addGame(game1);
    //add game to GUI
    
    Game game2 = new Game(" Vlad Popescu", 9,"Vlad Geana", 3);
    //create game
    gui.addGame(game2);
    //add game to GUI
    
    magicWorkerBill.fillMachine(game1);
    speedController.delay(40);
    magicWorkerTim.fillMachine(game2);

    for (int count = 1; count <= game1.getRackSize(); count ++)
    {
      game1.ejectBall();    
      speedController.delay(40);
    }
    for (int count = 1; count <= game2.getRackSize(); count ++)
    {
      game2.ejectBall(); 
      speedController.delay(40);
    }
    game1.rackSortBalls();
    game2.rackSortBalls(); 
  }
}

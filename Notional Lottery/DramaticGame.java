public class DramaticGame extends Game
{
  public DramaticGame(String machineName, int machineSize,String rackName, int rackSize) 
  {
    //machine = makeMachine(machineName, machineSize);
    //rack = makeRack(rackName, rackSize);
    super(machineName,machineSize,rackName,rackSize);
  }
 
  public DramaticMachine makeMachine(String machineName, int machineSize)
  {
    return new DramaticMachine(machineName, machineSize);
  } // makeMachine
}
//Dramatic Game

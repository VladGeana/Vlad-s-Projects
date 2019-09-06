package stopclock;

import java.awt.Container;
import java.awt.GridLayout;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import javax.swing.JButton;
import javax.swing.JFrame;
import javax.swing.JTextField;
import javax.swing.JLabel;

/**
 *
 * @author Vlad
 */
public class StopClock extends JFrame implements ActionListener
{
    private boolean isRunning=false;
    private long startTime=0;
    private long stopTime=0;
    private long splitTime=0;
    
    private final JTextField startTimeJTextField=new JTextField("Not started");
    private final JTextField stopTimeJTextField=new JTextField("Not started");
    private final JTextField elapsedTimeJTextField=new JTextField("Not started");
    private final JTextField splitTimeJTextField=new JTextField("Not started");
    
    private final JButton startStopJButton=new JButton("Start");
    private final JButton splitJButton=new JButton("Split");
    //Constructor
    public StopClock()
    {
        setTitle("Stop clock");
        Container contents=getContentPane();
        // Use a grid layput with one column
        contents.setLayout(new GridLayout(0,1));
        
        contents.add(new JLabel("Started at:"));
        contents.add(startTimeJTextField);
        startTimeJTextField.setEnabled(false);
        
        contents.add(new JLabel("Stopped at:"));
        stopTimeJTextField.setEnabled(false);
        contents.add(stopTimeJTextField);
        
        contents.add(new JLabel("Split time"));
        splitTimeJTextField.setEnabled(false);
        contents.add(splitTimeJTextField);
        
        contents.add(new JLabel("Elapsed time (seconds):"));
        elapsedTimeJTextField.setEnabled(false);
        contents.add(elapsedTimeJTextField);
        
        splitJButton.addActionListener(this);
        contents.add(splitJButton);
        
        startStopJButton.addActionListener(this);
        contents.add(startStopJButton);
        
        setDefaultCloseOperation(EXIT_ON_CLOSE);
        pack();
    }//StopClock
    public void actionPerformed(ActionEvent event)
    {
        if(!isRunning)
        {
            if (event.getSource() == startStopJButton)
            {
                //Start the clock
                startTime=System.currentTimeMillis();
                startTimeJTextField.setText("" + startTime);
                stopTimeJTextField.setText("Running...");
                elapsedTimeJTextField.setText("Running...");
                splitJButton.setEnabled(true);
                startStopJButton.setText("Stop");
                isRunning=true;
            }
        }
        else
        {
            if (event.getSource() == startStopJButton)
            {
                //Stop the clock and show the  updated times
                stopTime=System.currentTimeMillis();
                stopTimeJTextField.setText("" + stopTime);
                long elapsedMilliSeconds=stopTime-startTime;
                elapsedTimeJTextField.setText(""+ elapsedMilliSeconds /1000.0);
                splitJButton.setEnabled(false);
                startStopJButton.setText("Start");
                isRunning=false;
            }
            else
            {
                splitTime=System.currentTimeMillis();
                splitTimeJTextField.setText(""+splitTime);
                long elapsedMilliSeconds=splitTime-startTime;
                elapsedTimeJTextField.setText(""+ elapsedMilliSeconds /1000.0);
            }
        }
        pack();
    }
    public static void main(String[] args)
    {
        StopClock theStopClock = new StopClock();
        theStopClock.setVisible(true);
    }
    
}
    


import java.io.InputStreamReader;
import java.io.IOException;
import java.io.FileWriter;
import java.io.PrintWriter;
import java.io.File;
import java.io.FileReader;
import java.io.FileInputStream;

public class Uuencode
{
  private static FileInputStream input;
  public static void writeByteAsChar(int byte1)
  {
    System.out.print((char) (byte1==0 ? 96 : byte1 + 32)); 
  }
  public static void main(String[] args)
  {    
    try
    {
      if(args.length==1)
        input=new FileInputStream(args[0]);       
      else
        throw new IllegalArgumentException("Please suply exactly one argument,"
                + "the name of the file to be encoded");
      int noOfBytes=0,currentByte;
      int[] bytes=new int[100];
      while((currentByte=input.read())!=-1)
      {
        //code here to deal with byte
        bytes[noOfBytes++]=currentByte;
      }
      int index=0;
      while(noOfBytes>=3)
      {
        noOfBytes-=3;
        int byte1=bytes[index] >> 2;
        int byte2=(bytes[index] & 0x3) <<4 | (bytes[index+1] >>4);
        int byte3=(bytes[index+1] & 0xf) <<2 | bytes[index+2] >>6;
        int byte4=bytes[index+2] & 0x3f;
        
        //now write the result bytes
        writeByteAsChar(byte1);
        writeByteAsChar(byte2);
        writeByteAsChar(byte3);
        writeByteAsChar(byte4);
        index+=3;
      }
    }//try
    catch(IOException exception)
    {
      System.err.println(exception);
    }//catch
    finally
    {
      if(input!=null)
        try
        {
          input.close();
        }
        catch(IOException exception)
        {
          System.err.println("Could not close input "+exception);  
        }
    }//finally
  }//main    
}//Uuencode
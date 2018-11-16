
import java.sql.*;

import com.mysql.jdbc.*;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;
import java.io.BufferedReader;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.IOException;

class Main {

    public static void main(String[] args) {
        BufferedReader br = null;
        String strLine = "";
        Insert inserter = new Insert();
        int i = 0;

        try {

            Connection conn = inserter.createConn();

            br = new BufferedReader( new FileReader("part-r-000001"));
            while( (strLine = br.readLine()) != null){
                String separator = "--separator--";
                String[] parts = strLine.split(separator);
                String[] parts2 = parts[1].split("\t");
                System.out.println("Inserted:"+i+"\n");
                inserter.insertDB(parts2[0],parts[0],Integer.parseInt(parts2[1]),conn);
                i++;
            }
            br.close();
        } catch (FileNotFoundException e) {
            System.err.println("File not found");
        } catch (IOException e) {
            System.err.println("Error: "+e);
        }
    }
}
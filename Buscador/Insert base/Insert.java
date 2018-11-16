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

public class Insert {

    Insert(){}

    Connection createConn(){
        String JDBC_DRIVER = "com.mysql.jdbc.Driver";
        String DB_URL = "jdbc:mysql://localhost:3306/webcrawler";

        // Database credentials
        String USER = "wc";
        String PASS = "webcrawler";
        Connection conn = null;
        try {
            conn = DriverManager.getConnection(DB_URL, USER, PASS);
        } catch (SQLException ex) {
            System.out.println("Error: " + ex);
        }
        return conn;
    }

    void insertDB(String word, String url, int quantity, Connection conn){
        try {
            String sql = "INSERT INTO data (url, word, quantity)"
                    + "VALUES(?, ?, ?)";
            java.sql.PreparedStatement preparedStatement = conn.prepareStatement(sql);
            preparedStatement.setString(1, word);
            preparedStatement.setString(2, url);
            preparedStatement.setInt(3, quantity);
            preparedStatement.executeUpdate();
        } catch (SQLException ex) {
            System.out.println("Error: " + ex);
        }
    }
}
